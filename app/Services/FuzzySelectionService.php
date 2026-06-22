<?php

namespace App\Services;

use App\Models\Application;
use App\Models\FuzzyMembership;
use App\Models\Selection;
use Illuminate\Support\Collection;

/**
 * Fuzzy Tsukamoto Scholarship Selection Service
 *
 * Menggunakan 243 rule (3^5) yang dihasilkan otomatis dari 5 variabel input,
 * masing-masing dengan 3 himpunan linguistik. Output ditentukan berdasarkan
 * sistem penilaian bobot yang konsisten dengan 15 rule awal:
 *
 *   Penghasilan : low=2, mid=1, high=0  (inverse – kecil = baik)
 *   Tanggungan  : low=0, mid=1, high=2  (increasing – banyak = baik)
 *   DTKS        : low=2, mid=1, high=0  (inverse – Desil1 = baik)
 *   Prestasi    : low=0, mid=1, high=2  (increasing – tinggi = baik)
 *   Wawancara   : low=0, mid=1, high=2  (increasing – tinggi = baik)
 *
 *   Total max = 10. Layak jika total >= 5.
 */
class FuzzySelectionService
{
    /**
     * 15 Rule Fuzzy Tsukamoto (dari tabel rule yang diberikan).
     * Setiap rule memiliki field 'score' (0–10) yang digunakan sebagai
     * nilai z tetap: z = score × 10  →  Σ(α·z)/Σα sebagai skor akhir.
     *
     * Scoring per himpunan (konsisten dengan 15 rule):
     *   Penghasilan : low=2, mid=1, high=0  (inverse)
     *   Tanggungan  : low=0, mid=1, high=2  (increasing)
     *   DTKS        : low=2, mid=1, high=0  (inverse)
     *   Prestasi    : low=0, mid=1, high=2  (increasing)
     *   Wawancara   : low=0, mid=1, high=2  (increasing)
     *
     * @var array<int, array{out: string, score: int, cond: array<string, string>}>
     */
    private array $rules = [
        // R1 : Rendah, Banyak, Desil1, Tinggi, SangatBaik → Layak (score=10)
        ['out' => 'layak', 'score' => 10, 'cond' => ['penghasilan' => 'low', 'tanggungan' => 'high', 'dtks' => 'low', 'prestasi' => 'high', 'wawancara' => 'high']],
        // R2 : Rendah, Banyak, Desil1, Sedang, Baik → Layak (score=8)
        ['out' => 'layak', 'score' => 8,  'cond' => ['penghasilan' => 'low', 'tanggungan' => 'high', 'dtks' => 'low', 'prestasi' => 'mid', 'wawancara' => 'mid']],
        // R3 : Rendah, Sedang, Desil1, Tinggi, Baik → Layak (score=8)
        ['out' => 'layak', 'score' => 8,  'cond' => ['penghasilan' => 'low', 'tanggungan' => 'mid', 'dtks' => 'low', 'prestasi' => 'high', 'wawancara' => 'mid']],
        // R4 : Sedang, Banyak, Desil1, Tinggi, Baik → Layak (score=8)
        ['out' => 'layak', 'score' => 8,  'cond' => ['penghasilan' => 'mid', 'tanggungan' => 'high', 'dtks' => 'low', 'prestasi' => 'high', 'wawancara' => 'mid']],
        // R5 : Sedang, Sedang, Desil2, Tinggi, SangatBaik → Layak (score=7)
        ['out' => 'layak', 'score' => 7,  'cond' => ['penghasilan' => 'mid', 'tanggungan' => 'mid', 'dtks' => 'mid', 'prestasi' => 'high', 'wawancara' => 'high']],
        // R6 : Rendah, Banyak, Desil2, Sedang, SangatBaik → Layak (score=8)
        ['out' => 'layak', 'score' => 8,  'cond' => ['penghasilan' => 'low', 'tanggungan' => 'high', 'dtks' => 'mid', 'prestasi' => 'mid', 'wawancara' => 'high']],
        // R7 : Rendah, Sedang, Desil2, Tinggi, Baik → Layak (score=7)
        ['out' => 'layak', 'score' => 7,  'cond' => ['penghasilan' => 'low', 'tanggungan' => 'mid', 'dtks' => 'mid', 'prestasi' => 'high', 'wawancara' => 'mid']],
        // R8 : Sedang, Banyak, Desil2, Tinggi, SangatBaik → Layak (score=8)
        ['out' => 'layak', 'score' => 8,  'cond' => ['penghasilan' => 'mid', 'tanggungan' => 'high', 'dtks' => 'mid', 'prestasi' => 'high', 'wawancara' => 'high']],
        // R9 : Tinggi, Sedikit, Desil3, Rendah, Kurang → Tidak Layak (score=0)
        ['out' => 'tidak layak', 'score' => 0, 'cond' => ['penghasilan' => 'high', 'tanggungan' => 'low', 'dtks' => 'high', 'prestasi' => 'low', 'wawancara' => 'low']],
        // R10: Tinggi, Sedikit, Desil3, Sedang, Kurang → Tidak Layak (score=1)
        ['out' => 'tidak layak', 'score' => 1, 'cond' => ['penghasilan' => 'high', 'tanggungan' => 'low', 'dtks' => 'high', 'prestasi' => 'mid', 'wawancara' => 'low']],
        // R11: Tinggi, Sedang, Desil3, Rendah, Kurang → Tidak Layak (score=1)
        ['out' => 'tidak layak', 'score' => 1, 'cond' => ['penghasilan' => 'high', 'tanggungan' => 'mid', 'dtks' => 'high', 'prestasi' => 'low', 'wawancara' => 'low']],
        // R12: Sedang, Sedikit, Desil3, Rendah, Kurang → Tidak Layak (score=1)
        ['out' => 'tidak layak', 'score' => 1, 'cond' => ['penghasilan' => 'mid', 'tanggungan' => 'low', 'dtks' => 'high', 'prestasi' => 'low', 'wawancara' => 'low']],
        // R13: Tinggi, Sedikit, Desil2, Rendah, Kurang → Tidak Layak (score=1)
        ['out' => 'tidak layak', 'score' => 1, 'cond' => ['penghasilan' => 'high', 'tanggungan' => 'low', 'dtks' => 'mid', 'prestasi' => 'low', 'wawancara' => 'low']],
        // R14: Sedang, Sedikit, Desil2, Rendah, Kurang → Tidak Layak (score=2)
        ['out' => 'tidak layak', 'score' => 2, 'cond' => ['penghasilan' => 'mid', 'tanggungan' => 'low', 'dtks' => 'mid', 'prestasi' => 'low', 'wawancara' => 'low']],
        // R15: Tinggi, Banyak, Desil1, Tinggi, SangatBaik → Layak (score=8)
        ['out' => 'layak', 'score' => 8,  'cond' => ['penghasilan' => 'high', 'tanggungan' => 'high', 'dtks' => 'low', 'prestasi' => 'high', 'wawancara' => 'high']],
    ];

    public function runBatch(): array
    {
        $selectionIds = Selection::where('status', 'siap di proses')
            ->pluck('application_id');

        $applications = Application::with([
            'student',
            'scholarship.fuzzyMemberships.criteria',
            'requirementValues.requirement',
            'selection',
            'interviews.assessments',
        ])->whereIn('id', $selectionIds)->get();

        return $applications->map(fn ($app) => $this->evaluate($app))->toArray();
    }

    public function evaluate(Application $application): array
    {
        $memberships = FuzzyMembership::with('criteria')
            ->where('scholarship_id', $application->scholarship_id)
            ->get();

        if ($memberships->isEmpty()) {
            return $this->fallbackEvaluation($application);
        }

        $requirementValues = $application->requirementValues
            ->keyBy(fn ($rv) => strtolower($rv->requirement->requirement_name ?? ''));

        $interviewScore = $this->averageInterviewScore($application);

        // ----------------------------------------------------------------
        // Step 1 – Fuzzifikasi: hitung μ_low, μ_mid, μ_high per variabel
        // ----------------------------------------------------------------
        $muPerVar = [];   // ['penghasilan' => ['low'=>x,'mid'=>x,'high'=>x], ...]
        $inputValues = [];   // nilai aktual per variabel
        $detailList = [];   // untuk tampilan UI

        foreach ($memberships as $m) {
            $name = strtolower($m->criteria->criteria_name ?? '');
            $varKey = $this->varKey($name);

            $a = (float) $m->min_value;
            $b = (float) $m->mid_value;
            $c = (float) $m->max_value;

            // Ambil nilai pelamar
            if ($varKey === 'wawancara') {
                $val = $interviewScore;
            } else {
                $val = $this->extractNumericValue($requirementValues, $name, $m->criteria->criteria_name ?? '');
            }

            // DTKS: nilai 0 = tidak terdaftar → paling buruk (Desil 3 / high)
            if ($varKey === 'dtks' && $val == 0) {
                $val = $c;
            }

            $muLow = $this->leftShoulderMembership($val, $a, $b);
            $muMid = $this->triangularMembership($val, $a, $b, $c);
            $muHigh = $this->rightShoulderMembership($val, $b, $c);

            $muPerVar[$varKey] = ['low' => $muLow, 'mid' => $muMid, 'high' => $muHigh];
            $inputValues[$varKey] = $val;

            $sets = $this->setNames($varKey);
            $detailList[] = [
                'criteria_name' => $m->criteria->criteria_name ?? '',
                'var_key' => $varKey,
                'applicant_value' => $val,
                'min_value' => $a,
                'mid_value' => $b,
                'max_value' => $c,
                'mu_low' => round($muLow, 4),
                'mu_mid' => round($muMid, 4),
                'mu_high' => round($muHigh, 4),
                'set_low' => $sets['low'],
                'set_mid' => $sets['mid'],
                'set_high' => $sets['high'],
                'is_inverse' => $this->isInverse($varKey),
                'is_interview' => $varKey === 'wawancara',
                'is_increasing' => ! $this->isInverse($varKey),
            ];
        }

        // Jika variabel wawancara tidak ada di DB, tambahkan otomatis
        if (! isset($muPerVar['wawancara'])) {
            $a = 0.0;
            $b = 50.0;
            $c = 100.0;
            $muL = $this->leftShoulderMembership($interviewScore, $a, $b);
            $muM = $this->triangularMembership($interviewScore, $a, $b, $c);
            $muH = $this->rightShoulderMembership($interviewScore, $b, $c);
            $muPerVar['wawancara'] = ['low' => $muL, 'mid' => $muM, 'high' => $muH];
            $inputValues['wawancara'] = $interviewScore;
            $sets = $this->setNames('wawancara');
            $detailList[] = [
                'criteria_name' => 'Nilai Wawancara',
                'var_key' => 'wawancara',
                'applicant_value' => $interviewScore,
                'min_value' => $a, 'mid_value' => $b, 'max_value' => $c,
                'mu_low' => round($muL, 4),
                'mu_mid' => round($muM, 4),
                'mu_high' => round($muH, 4),
                'set_low' => $sets['low'], 'set_mid' => $sets['mid'], 'set_high' => $sets['high'],
                'is_inverse' => false, 'is_interview' => true, 'is_increasing' => true,
            ];
        }

        // ----------------------------------------------------------------
        // Step 2 – Inference: hitung α per rule = min dari antecedent
        // ----------------------------------------------------------------
        $ruleResults = [];

        foreach ($this->rules as $idx => $rule) {
            $alpha = 1.0;
            foreach ($rule['cond'] as $var => $set) {
                if (! isset($muPerVar[$var])) {
                    $alpha = 0.0;
                    break;
                }
                $alpha = min($alpha, $muPerVar[$var][$set] ?? 0.0);
            }

            if ($alpha <= 0.0) {
                continue;
            }

            // ----------------------------------------------------------------
            // Step 3 – Consequent (Tsukamoto):
            //   z = nilai output TETAP per rule = score × 10
            //   (score sudah dihitung saat buildAllRules, range 0–10)
            //   → z ∈ [0, 100], independen dari α
            //   Pola ini sama dengan contoh.php: z[$i] = ceil(($i+1)/N)
            // ----------------------------------------------------------------
            $z = ($rule['score'] ?? 0) * 10;  // range 0–100, tetap per rule

            $ruleResults[] = [
                'rule' => 'R'.($idx + 1),
                'output' => $rule['out'],
                'score' => $rule['score'] ?? 0,
                'alpha' => round($alpha, 4),
                'z' => $z,
            ];
        }

        // ----------------------------------------------------------------
        // Step 4 – Defuzzifikasi: weighted average (pola contoh.php)
        //
        //   hasil = Σ(α × z) / Σα
        //
        //   z adalah nilai TETAP per rule (bukan fungsi α).
        //   Ini identik dengan: $hasil = $sumAlphaZ / $sumAlpha;
        //   dari contoh.php.
        //
        //   Threshold: hasil > 50 → LAYAK, ≤ 50 → TIDAK LAYAK
        //   (z LAYAK ∈ [50–100], z TIDAK LAYAK ∈ [0–40])
        // ----------------------------------------------------------------
        $sumAlphaZ = 0.0;
        $sumAlpha = 0.0;
        $alphaLayakMax = 0.0;
        $alphaTidakMax = 0.0;
        $winnerRule = null;
        $winnerAlpha = -1.0;

        foreach ($ruleResults as $r) {
            $sumAlphaZ += $r['alpha'] * $r['z'];
            $sumAlpha += $r['alpha'];

            if ($r['output'] === 'layak') {
                $alphaLayakMax = max($alphaLayakMax, $r['alpha']);
            } else {
                $alphaTidakMax = max($alphaTidakMax, $r['alpha']);
            }

            if ($r['alpha'] > $winnerAlpha) {
                $winnerAlpha = $r['alpha'];
                $winnerRule = $r;
            }
        }

        if ($sumAlpha > 0) {
            $fuzzyScore = round($sumAlphaZ / $sumAlpha, 2);
        } else {
            $fuzzyScore = $this->fallbackScore($muPerVar);
        }

        $status = $fuzzyScore > 50 ? 'layak' : 'tidak layak';

        return [
            'application_id' => $application->id,
            'student_name' => $application->student->name ?? 'N/A',
            'student_number' => $application->student->student_number ?? '-',
            'scholarship_name' => $application->scholarship->scholarship_name ?? 'N/A',
            'fuzzy_score' => $fuzzyScore,
            'sum_alpha_z' => round($sumAlphaZ, 4),
            'sum_alpha' => round($sumAlpha, 4),
            'sum_alpha_layak' => round($sumAlphaZ, 4),  // alias agar blade tidak error
            'sum_alpha_tidak' => round($sumAlpha - ($sumAlphaZ / 10), 4),
            'alpha_layak' => round($alphaLayakMax, 4),
            'alpha_tidak' => round($alphaTidakMax, 4),
            'z_layak' => round($alphaLayakMax * 100, 2),
            'z_tidak' => round((1 - $alphaTidakMax) * 100, 2),
            'recommended_status' => $status,
            'winning_rule' => $winnerRule,
            'criteria_details' => $detailList,
            'rule_results' => $ruleResults,
            'has_existing_selection' => $application->selection !== null,
            'existing_selection_id' => $application->selection?->id,
            'interview_score' => $interviewScore,
            'criteria_count' => count($detailList),
        ];
    }

    // ====================================================================
    // MEMBERSHIP FUNCTIONS
    // ====================================================================

    /** Left-shoulder: μ=1 jika x≤a, turun ke 0 di b */
    private function leftShoulderMembership(float $x, float $a, float $b): float
    {
        if ($x <= $a) {
            return 1.0;
        }
        if ($x >= $b) {
            return 0.0;
        }

        return ($b - $a) > 0 ? ($b - $x) / ($b - $a) : 0.0;
    }

    /** Triangular: puncak 1 di b, nol di a dan c */
    private function triangularMembership(float $x, float $a, float $b, float $c): float
    {
        if ($x <= $a || $x >= $c) {
            return 0.0;
        }
        if ($x == $b) {
            return 1.0;
        }
        if ($x < $b) {
            return ($b - $a) > 0 ? ($x - $a) / ($b - $a) : 0.0;
        }

        return ($c - $b) > 0 ? ($c - $x) / ($c - $b) : 0.0;
    }

    /** Right-shoulder: μ=0 jika x≤b, naik ke 1 di c */
    private function rightShoulderMembership(float $x, float $b, float $c): float
    {
        if ($x <= $b) {
            return 0.0;
        }
        if ($x >= $c) {
            return 1.0;
        }

        return ($c - $b) > 0 ? ($x - $b) / ($c - $b) : 0.0;
    }

    /**
     * Fallback score ketika tidak ada rule Tsukamoto yang aktif (sumAlpha = 0).
     *
     * Menghitung rata-rata μ_good per variabel, dimana:
     *   - Variabel INVERSE (penghasilan, DTKS): μ_good = μ_low + 0.5 × μ_mid
     *   - Variabel INCREASING (tanggungan, prestasi, wawancara): μ_good = μ_high + 0.5 × μ_mid
     *
     * Skor = rata-rata μ_good × 100 (skala 0–100)
     *
     * @param  array<string, array{low:float, mid:float, high:float}>  $muPerVar
     */
    private function fallbackScore(array $muPerVar): float
    {
        if (empty($muPerVar)) {
            return 0.0;
        }

        $muGoods = [];

        foreach ($muPerVar as $varKey => $sets) {
            if ($this->isInverse($varKey)) {
                // Inverse: low = baik, high = buruk
                $muGoods[] = min(1.0, $sets['low'] + 0.5 * $sets['mid']);
            } else {
                // Increasing: high = baik, low = buruk
                $muGoods[] = min(1.0, $sets['high'] + 0.5 * $sets['mid']);
            }
        }

        return round((array_sum($muGoods) / count($muGoods)) * 100, 2);
    }

    // ====================================================================
    // HELPERS
    // ====================================================================

    /**
     * Petakan nama kriteria → kunci variabel standar yang digunakan di $rules.
     * Kunci: penghasilan | tanggungan | dtks | prestasi | wawancara
     */
    private function varKey(string $lower): string
    {
        if (str_contains($lower, 'penghasilan') || str_contains($lower, 'income')) {
            return 'penghasilan';
        }
        if (str_contains($lower, 'tanggungan') || str_contains($lower, 'beban')) {
            return 'tanggungan';
        }
        if (str_contains($lower, 'dtks')) {
            return 'dtks';
        }
        if (str_contains($lower, 'prestasi') || str_contains($lower, 'sertifikat') || str_contains($lower, 'ipk')) {
            return 'prestasi';
        }
        if (str_contains($lower, 'wawancara') || str_contains($lower, 'interview')) {
            return 'wawancara';
        }

        return $lower;
    }

    /** Apakah variabel bersifat inverse (kecil = baik)? */
    private function isInverse(string $varKey): bool
    {
        return in_array($varKey, ['penghasilan', 'dtks']);
    }

    /** Nama himpunan per variabel */
    private function setNames(string $varKey): array
    {
        return match ($varKey) {
            'dtks' => ['low' => 'Desil 1', 'mid' => 'Desil 2', 'high' => 'Desil 3'],
            'tanggungan' => ['low' => 'Sedikit',  'mid' => 'Sedang',  'high' => 'Banyak'],
            'wawancara' => ['low' => 'Kurang',    'mid' => 'Baik',    'high' => 'Sangat Baik'],
            default => ['low' => 'Rendah',    'mid' => 'Sedang',  'high' => 'Tinggi'],
        };
    }

    private function extractNumericValue(Collection $requirementValues, string $criteriaKey, string $criteriaName): float
    {
        $rv = $requirementValues->get($criteriaKey);
        if ($rv && is_numeric($rv->applicant_value)) {
            return (float) $rv->applicant_value;
        }

        foreach ($requirementValues as $key => $rv) {
            if (str_contains($key, $criteriaKey) || str_contains($criteriaKey, $key)) {
                if (is_numeric($rv->applicant_value)) {
                    return (float) $rv->applicant_value;
                }
            }
        }

        return 0.0;
    }

    private function averageInterviewScore(Application $application): float
    {
        $scores = [];
        foreach ($application->interviews as $interview) {
            foreach ($interview->assessments as $a) {
                $scores[] = (float) $a->score;
            }
        }

        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : 0.0;
    }

    // ====================================================================
    // FALLBACK & APPLY
    // ====================================================================

    private function fallbackEvaluation(Application $application): array
    {
        $total = $application->requirementValues->count();
        $validated = $application->requirementValues->where('validation_status', 1)->count();
        $ratio = $total > 0 ? ($validated / $total) : 0;
        $score = round($ratio * 100, 2);

        return [
            'application_id' => $application->id,
            'student_name' => $application->student->name ?? 'N/A',
            'student_number' => $application->student->student_number ?? '-',
            'scholarship_name' => $application->scholarship->scholarship_name ?? 'N/A',
            'fuzzy_score' => $score,
            'alpha_layak' => $ratio,
            'alpha_tidak' => 1.0 - $ratio,
            'z_layak' => $score,
            'z_tidak' => round((1 - $ratio) * 100, 2),
            'recommended_status' => $score >= 50 ? 'layak' : 'tidak layak',
            'criteria_details' => [],
            'rule_results' => [],
            'has_existing_selection' => $application->selection !== null,
            'existing_selection_id' => $application->selection?->id,
            'criteria_count' => 0,
            'fallback_mode' => true,
        ];
    }

    public function applyResults(array $results): int
    {
        $applied = 0;
        foreach ($results as $result) {
            $isLayak = $result['recommended_status'] === 'layak';
            $newStatus = $isLayak ? 'layak' : 'tidak diterima';

            $notes = sprintf(
                'Seleksi otomatis via Fuzzy Tsukamoto. Skor: %.2f/100. Kelayakan: %s. Rules aktif: %d.',
                $result['fuzzy_score'],
                strtoupper($result['recommended_status']),
                count($result['rule_results'] ?? [])
            );

            $selection = Selection::firstOrCreate(
                ['application_id' => $result['application_id']],
                ['stage' => 'Seleksi AI', 'status' => $newStatus, 'notes' => $notes, 'date' => now()]
            );

            if (! $selection->wasRecentlyCreated) {
                $selection->update(['stage' => 'Seleksi AI', 'status' => $newStatus, 'notes' => $notes, 'date' => now()]);
            }

            $application = $selection->application ?? Application::find($result['application_id']);
            if ($application) {
                $application->status = $isLayak ? 'diproses' : 'ditolak';
                if (! $isLayak) {
                    $application->description = 'Pendaftaran anda dinyatakan tidak layak menerima beasiswa oleh sistem karena beberapa persyaratan kurang memenuhi.';
                }
                $application->saveQuietly();
            }

            $applied++;
        }

        return $applied;
    }
}
