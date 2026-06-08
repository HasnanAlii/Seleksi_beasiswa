<?php

namespace App\Services;

use App\Models\Application;
use App\Models\FuzzyMembership;
use App\Models\Selection;
use Illuminate\Support\Collection;

class FuzzySelectionService
{
    /**
     * Run Tsukamoto fuzzy logic selection on applications whose Selection
     * record has status 'siap di proses'.
     *
     * Tsukamoto method:
     *  1. Fuzzification  : compute membership degrees μ(x) per criterion.
     *  2. Inference      : firing strength α = min(μ_i)  [AND aggregation].
     *  3. Consequent     : each rule has a monotonic output function → crisp z_i.
     *     - Rule LAYAK       : monotonically increasing  → z_layak = α_layak × 100
     *     - Rule TIDAK LAYAK : monotonically decreasing  → z_tidak  = (1 − α_tidak) × 100
     *  4. Defuzzification: weighted average z = Σ(α_i × z_i) / Σ(α_i).
     *
     * @return array<int, array<string, mixed>>
     */
    public function runBatch(): array
    {
        // Process semua selection yang belum final (belum ada keputusan AI).
        // Status final (tidak diproses ulang): 'layak', 'diterima', 'tidak diterima'
        $finalStatuses = ['layak', 'diterima', 'tidak diterima'];

        $selectionIds = Selection::whereNotIn('status', $finalStatuses)
            ->pluck('application_id');

        $applications = Application::with([
            'student',
            'scholarship.fuzzyMemberships.criteria',
            'requirementValues.requirement',
            'selection',
            'interviews.assessments',
        ])->whereIn('id', $selectionIds)->get();

        $results = [];

        foreach ($applications as $application) {
            $results[] = $this->evaluate($application);
        }

        return $results;
    }

    /**
     * Evaluate a single application using Fuzzy Tsukamoto.
     *
     * @return array<string, mixed>
     */
    public function evaluate(Application $application): array
    {
        $scholarshipId = $application->scholarship_id;

        /** @var \Illuminate\Database\Eloquent\Collection<int, FuzzyMembership> $memberships */
        $memberships = FuzzyMembership::with('criteria')
            ->where('scholarship_id', $scholarshipId)
            ->get();

        // If no fuzzy memberships configured, fall back to document validation check
        if ($memberships->isEmpty()) {
            return $this->fallbackEvaluation($application);
        }

        $requirementValues = $application->requirementValues
            ->keyBy(fn ($rv) => strtolower($rv->requirement->requirement_name ?? ''));

        $muGoodList = [];   // μ_good per criterion
        $criteriaDetails = [];

        // Check apakah sudah ada kriteria wawancara di fuzzy memberships
        $hasInterviewCriteria = $memberships->contains(
            fn ($m) => str_contains(strtolower($m->criteria->criteria_name ?? ''), 'wawancara')
                || str_contains(strtolower($m->criteria->criteria_name ?? ''), 'interview')
        );

        // Hitung rata-rata skor wawancara dari interview_assessments
        $interviewScore = $this->averageInterviewScore($application);

        foreach ($memberships as $membership) {
            $criteriaName = $membership->criteria->criteria_name ?? '';
            $criteriaKey = strtolower($criteriaName);
            $isInterviewCriterion = str_contains($criteriaKey, 'wawancara')
                || str_contains($criteriaKey, 'interview');
            $isInverseCriterion = $this->isInverseCriteria($criteriaName);
            $isIncreasingCriterion = $this->isIncreasingCriteria($criteriaName);

            // Untuk kriteria wawancara: gunakan skor interview assessment sebagai nilai
            // Untuk kriteria lain: ambil dari requirement values seperti biasa
            if ($isInterviewCriterion && $interviewScore > 0) {
                $applicantValue = $interviewScore;
            } else {
                $applicantValue = $this->extractNumericValue($requirementValues, $criteriaKey, $criteriaName);
            }

            // Normalisasi khusus Status DTKS:
            // Nilai 0 (tidak terdaftar) dipetakan ke max_value (3) agar memberi μ_good = 0
            if (str_contains($criteriaKey, 'dtks') && $applicantValue == 0) {
                $applicantValue = (float) $membership->max_value;
            }

            // Step 1 – Fuzzification
            // - INCREASING (semakin besar = semakin baik): right-shoulder
            // - INVERSE    (semakin kecil = semakin baik): left-shoulder
            // - NORMAL     (puncak di tengah)            : triangular
            if ($isIncreasingCriterion) {
                $muGood = $this->increasingMembership(
                    (float) $applicantValue,
                    (float) $membership->min_value,
                    (float) $membership->max_value,
                );
            } elseif ($isInverseCriterion) {
                $muGood = $this->decreasingMembership(
                    (float) $applicantValue,
                    (float) $membership->min_value,
                    (float) $membership->max_value,
                );
            } else {
                $muGood = $this->triangularMembership(
                    (float) $applicantValue,
                    (float) $membership->min_value,
                    (float) $membership->mid_value,
                    (float) $membership->max_value,
                );
            }

            // Complement: membership for the BAD region
            $muBad = round(1.0 - $muGood, 4);

            $muGoodList[] = $muGood;

            $criteriaDetails[] = [
                'criteria_name' => $criteriaName,
                'applicant_value' => $applicantValue,
                'min_value' => (float) $membership->min_value,
                'mid_value' => (float) $membership->mid_value,
                'max_value' => (float) $membership->max_value,
                'mu_good' => round($muGood, 4),
                'mu_bad' => $muBad,
                'is_interview' => $isInterviewCriterion,
                'is_inverse' => $isInverseCriterion,
                'is_increasing' => $isIncreasingCriterion,
            ];
        }

        // Jika TIDAK ada kriteria wawancara di fuzzy memberships,
        // tambahkan "Nilai Wawancara" sebagai kriteria ekstra
        if (! $hasInterviewCriteria) {
            $interviewMinVal = 0.0;
            $interviewMidVal = 50.0;
            $interviewMaxVal = 100.0;

            $muGoodInterview = $this->triangularMembership(
                $interviewScore,
                $interviewMinVal,
                $interviewMidVal,
                $interviewMaxVal,
            );
            $muBadInterview = round(1.0 - $muGoodInterview, 4);

            $muGoodList[] = $muGoodInterview;

            $criteriaDetails[] = [
                'criteria_name' => 'Nilai Wawancara',
                'applicant_value' => $interviewScore,
                'min_value' => $interviewMinVal,
                'mid_value' => $interviewMidVal,
                'max_value' => $interviewMaxVal,
                'mu_good' => round($muGoodInterview, 4),
                'mu_bad' => $muBadInterview,
                'is_interview' => true,
            ];
        }

        // Step 2 – Inference (AND → min):
        //   α_LAYAK     = min of all μ_good  (all criteria must be satisfied)
        //   α_TIDAK     = min of all μ_bad   = 1 − max(μ_good)
        $alphaLayak = count($muGoodList) > 0 ? min($muGoodList) : 0.0;
        $alphaTidak = count($muGoodList) > 0 ? (1.0 - max($muGoodList)) : 1.0;

        // Step 3 – Consequent crisp values (monotonic output functions on [0, 100]):
        //   Rule LAYAK (increasing)  : z_layak = α_layak × 100
        //   Rule TIDAK LAYAK (decr.) : z_tidak  = (1 − α_tidak) × 100
        $zLayak = $alphaLayak * 100.0;
        $zTidak = (1.0 - $alphaTidak) * 100.0;

        // Step 4 – Defuzzification: weighted average
        $denominator = $alphaLayak + $alphaTidak;
        $fuzzyScore = $denominator > 0
            ? round(($alphaLayak * $zLayak + $alphaTidak * $zTidak) / $denominator, 2)
            : 0.0;

        $status = $this->determineStatus($fuzzyScore);

        return [
            'application_id' => $application->id,
            'student_name' => $application->student->name ?? 'N/A',
            'student_number' => $application->student->student_number ?? '-',
            'scholarship_name' => $application->scholarship->scholarship_name ?? 'N/A',
            'fuzzy_score' => $fuzzyScore,
            'alpha_layak' => round($alphaLayak, 4),
            'alpha_tidak' => round($alphaTidak, 4),
            'z_layak' => round($zLayak, 2),
            'z_tidak' => round($zTidak, 2),
            'recommended_status' => $status,
            'criteria_details' => $criteriaDetails,
            'has_existing_selection' => $application->selection !== null,
            'existing_selection_id' => $application->selection?->id,
            'interview_score' => $interviewScore,
            'criteria_count' => count($muGoodList),
        ];
    }

    /**
     * Fallback evaluation when no fuzzy memberships are configured.
     * Uses document validation ratio as the fuzzy score.
     *
     * @return array<string, mixed>
     */
    private function fallbackEvaluation(Application $application): array
    {
        $totalDocs = $application->requirementValues->count();
        $validatedDocs = $application->requirementValues
            ->where('validation_status', 1)
            ->count();

        $ratio = $totalDocs > 0 ? ($validatedDocs / $totalDocs) : 0;
        $fuzzyScore = round($ratio * 100, 2);
        $status = $this->determineStatus($fuzzyScore);

        return [
            'application_id' => $application->id,
            'student_name' => $application->student->name ?? 'N/A',
            'student_number' => $application->student->student_number ?? '-',
            'scholarship_name' => $application->scholarship->scholarship_name ?? 'N/A',
            'fuzzy_score' => $fuzzyScore,
            'alpha_layak' => $ratio,
            'alpha_tidak' => 1.0 - $ratio,
            'z_layak' => round($ratio * 100, 2),
            'z_tidak' => round((1.0 - (1.0 - $ratio)) * 100, 2),
            'recommended_status' => $status,
            'criteria_details' => [],
            'has_existing_selection' => $application->selection !== null,
            'existing_selection_id' => $application->selection?->id,
            'criteria_count' => 0,
            'fallback_mode' => true,
        ];
    }

    /**
     * Triangular membership function μ(x) used in fuzzification step.
     *
     *  μ(x) = 0              if x ≤ a or x ≥ c
     *  μ(x) = (x-a)/(b-a)   if a < x ≤ b
     *  μ(x) = (c-x)/(c-b)   if b < x < c
     *  μ(x) = 1              if x = b
     */
    private function triangularMembership(float $x, float $a, float $b, float $c): float
    {
        if ($x <= $a || $x >= $c) {
            return 0.0;
        }

        if ($x === $b) {
            return 1.0;
        }

        if ($x < $b) {
            return ($b - $a) > 0 ? ($x - $a) / ($b - $a) : 0.0;
        }

        // $x > $b
        return ($c - $b) > 0 ? ($c - $x) / ($c - $b) : 0.0;
    }

    /**
     * Left-shoulder (decreasing) membership function μ(x).
     * Used for criteria where LOWER values are BETTER (inverse criteria).
     *
     *  μ(x) = 1              if x ≤ a  (smallest/best value)
     *  μ(x) = (c − x)/(c − a) if a < x < c
     *  μ(x) = 0              if x ≥ c  (largest/worst value)
     *
     * @param  float  $x  Applicant value
     * @param  float  $a  min_value from fuzzy membership ("best" boundary)
     * @param  float  $c  max_value from fuzzy membership ("worst" boundary)
     */
    private function decreasingMembership(float $x, float $a, float $c): float
    {
        if ($x <= $a) {
            return 1.0;
        }

        if ($x >= $c) {
            return 0.0;
        }

        return ($c - $a) > 0 ? ($c - $x) / ($c - $a) : 0.0;
    }

    /**
     * Right-shoulder (increasing) membership function μ(x).
     * Used for criteria where HIGHER values are BETTER (e.g. interview score 0–100).
     *
     *  μ(x) = 0              if x ≤ a  (lowest/worst value)
     *  μ(x) = (x − a)/(c − a) if a < x < c
     *  μ(x) = 1              if x ≥ c  (highest/best value)
     *
     * @param  float  $x  Applicant value
     * @param  float  $a  min_value from fuzzy membership ("worst" boundary)
     * @param  float  $c  max_value from fuzzy membership ("best" boundary)
     */
    private function increasingMembership(float $x, float $a, float $c): float
    {
        if ($x <= $a) {
            return 0.0;
        }

        if ($x >= $c) {
            return 1.0;
        }

        return ($c - $a) > 0 ? ($x - $a) / ($c - $a) : 0.0;
    }

    /**
     * Determine whether a criterion uses increasing (right-shoulder) membership.
     * Returns true for criteria where HIGHER values indicate better eligibility,
     * e.g. interview scores on a 0–100 scale.
     */
    private function isIncreasingCriteria(string $criteriaName): bool
    {
        $lower = strtolower($criteriaName);

        return str_contains($lower, 'wawancara')
            || str_contains($lower, 'interview');
    }

    /**
     * Determine whether a criterion uses inverse (decreasing) membership.
     * Returns true for criteria where smaller values indicate better eligibility,
     * e.g. fewer dependents, lower income, or DTKS status (registered = 1 = best).
     */
    private function isInverseCriteria(string $criteriaName): bool
    {
        $lower = strtolower($criteriaName);

        return str_contains($lower, 'tanggungan')
            || str_contains($lower, 'dtks')
            || str_contains($lower, 'hutang')
            || str_contains($lower, 'beban');
    }

    /**
     * Determine eligibility label from Tsukamoto defuzzified score.
     * Threshold: score ≥ 50 → layak, otherwise tidak layak.
     */
    private function determineStatus(float $score): string
    {
        return $score >= 50 ? 'layak' : 'tidak layak';
    }

    /**
     * Apply fuzzy results to the Selection records.
     * Maps 'layak' → 'diterima', 'tidak layak' → 'tidak diterima'.
     *
     * @param  array<int, array<string, mixed>>  $results
     */
    public function applyResults(array $results): int
    {
        $applied = 0;

        foreach ($results as $result) {
            $isLayak = $result['recommended_status'] === 'layak';

            // Map fuzzy recommendation to selection status
            $newStatus = $isLayak ? 'layak' : 'tidak diterima';

            $notes = sprintf(
                'Seleksi otomatis via Fuzzy Tsukamoto. Skor: %.2f/100. '.
                    'α_layak=%.4f, α_tidak=%.4f, z_layak=%.2f, z_tidak=%.2f. '.
                    'Kelayakan: %s.',
                $result['fuzzy_score'],
                $result['alpha_layak'],
                $result['alpha_tidak'],
                $result['z_layak'],
                $result['z_tidak'],
                strtoupper($result['recommended_status']),
            );

            // Find or create Selection for this application
            $selection = Selection::firstOrCreate(
                ['application_id' => $result['application_id']],
                [
                    'stage' => 'Seleksi AI',
                    'status' => $newStatus,
                    'notes' => $notes,
                    'date' => now(),
                ]
            );

            // If already existed, update it
            if (! $selection->wasRecentlyCreated) {
                $selection->update([
                    'stage' => 'Seleksi AI',
                    'status' => $newStatus,
                    'notes' => $notes,
                    'date' => now(),
                ]);
            }

            // Sync Application status based on fuzzy result (tanpa memicu observer
            // agar tidak menimpa Selection status yang baru saja diset oleh fuzzy):
            // 'tidak layak' → Application 'ditolak' + deskripsi penolakan
            // 'layak'       → Application 'diproses' (tetap menunggu verifikasi lanjut)
            $application = $selection->application ?? Application::find($result['application_id']);
            if ($application) {
                $appStatus = $isLayak ? 'diproses' : 'ditolak';
                $application->status = $appStatus;

                if (! $isLayak) {
                    $application->description = 'Pendaftaran anda dinyatakan tidak layak menerima beasiswa oleh sistem karena beberapa persyaratan kurang memenuhi.';
                }

                $application->saveQuietly();
            }

            $applied++;
        }

        return $applied;
    }

    /**
     * Extract a numeric applicant value from requirement values by criteria name matching.
     */
    private function extractNumericValue(
        Collection $requirementValues,
        string $criteriaKey,
        string $criteriaName
    ): float {
        // Try direct key match
        $rv = $requirementValues->get($criteriaKey);

        if ($rv !== null && is_numeric($rv->applicant_value)) {
            return (float) $rv->applicant_value;
        }

        // Try partial match
        foreach ($requirementValues as $key => $rv) {
            if (str_contains($key, $criteriaKey) || str_contains($criteriaKey, $key)) {
                if (is_numeric($rv->applicant_value)) {
                    return (float) $rv->applicant_value;
                }
            }
        }

        // Default heuristic mid-point per criteria name
        return $this->defaultValueForCriteria($criteriaName);
    }

    /**
     * Return a heuristic default value for well-known criteria names when
     * no applicant value can be extracted from requirement values.
     */
    private function defaultValueForCriteria(string $criteriaName): float
    {
        $lower = strtolower($criteriaName);

        if (str_contains($lower, 'ipk') || str_contains($lower, 'prestasi')) {
            return 3.00;
        }

        if (str_contains($lower, 'penghasilan') || str_contains($lower, 'income')) {
            return 2500000.00;
        }

        if (str_contains($lower, 'organisasi') || str_contains($lower, 'keaktifan')) {
            return 50.0;
        }

        if (str_contains($lower, 'jarak') || str_contains($lower, 'distance')) {
            return 3000000.00;
        }

        if (str_contains($lower, 'wawancara') || str_contains($lower, 'interview')) {
            return 0.0;
        }

        return 0.0;
    }

    /**
     * Calculate the average interview score for an application.
     * Returns a value on the scale 0–100.
     * If no interview assessments exist, returns 0.
     */
    private function averageInterviewScore(Application $application): float
    {
        $scores = [];

        foreach ($application->interviews as $interview) {
            foreach ($interview->assessments as $assessment) {
                $scores[] = (float) $assessment->score;
            }
        }

        if (count($scores) === 0) {
            return 0.0;
        }

        return round(array_sum($scores) / count($scores), 2);
    }
}
