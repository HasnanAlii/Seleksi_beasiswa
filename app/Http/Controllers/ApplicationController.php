<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationRequirementValue;
use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
            'scholarship_id' => $request->get('scholarship_id', ''),
        ];

        $query = Application::query()
            ->with(['student', 'scholarship', 'interviews', 'selection'])
            ->when($filters['search'], function ($q, $search) {
                return $q->whereHas('student', function ($qStudent) use ($search) {
                    $qStudent->where('name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%");
                });
            })
            ->when($filters['scholarship_id'], function ($q, $scholarshipId) {
                return $q->where('scholarship_id', $scholarshipId);
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();
        $scholarships = Scholarship::orderBy('scholarship_name')->get();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('applications.index', compact('data', 'filters', 'scholarships'));
    }

    public function create()
    {
        $scholarshipRequirements = $this->scholarshipRequirementsMap();

        return view('applications.form', [
            'application' => new Application,
            'action' => route('applications.store'),
            'method' => 'POST',
            'submitLabel' => 'Tambah Pendaftaran',
            'students' => Student::orderBy('name')->get(),
            'scholarships' => Scholarship::orderBy('scholarship_name')->get(),
            'scholarshipRequirements' => $scholarshipRequirements,
            'existingRequirementValues' => [],
        ]);
    }

    public function store(Request $request)
    {
        if ($request->boolean('is_new_student')) {
            $validated = $request->validate([
                'new_student_name' => 'required|string|max:100',
                'new_student_number' => 'required|string|max:20|unique:students,student_number',
                'new_student_study_program' => 'required|string|max:100',
                'new_student_email' => 'nullable|email|unique:users,email',
                'scholarship_id' => 'required|integer|exists:scholarships,id',
                'status' => 'required|string|in:menunggu,diproses,diterima,ditolak',
                'description' => 'nullable|string',
                'requirement_values' => 'nullable|array',
                'requirement_values.*.requirement_id' => 'required|integer|exists:requirements,id',
                'requirement_values.*.term' => 'nullable|string',
                'requirement_values.*.applicant_value' => 'nullable|string|max:255',
            ]);

            $application = DB::transaction(function () use ($request, $validated) {
                $student = Student::create([
                    'name' => $request->new_student_name,
                    'student_number' => $request->new_student_number,
                    'study_program' => $request->new_student_study_program,
                ]);

                $email = $request->filled('new_student_email')
                    ? $request->new_student_email
                    : $request->new_student_number.'@student.ac.id';

                $user = User::create([
                    'name' => $request->new_student_name,
                    'email' => $email,
                    'password' => Hash::make($request->new_student_number),
                ]);

                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('mahasiswa');
                }

                $application = Application::create([
                    'student_id' => $student->id,
                    'scholarship_id' => $request->scholarship_id,
                    'status' => $request->status,
                    'description' => $request->description,
                ]);

                $this->syncRequirementValues($application, $validated['requirement_values'] ?? []);

                return $application;
            });
        } else {
            $validated = $request->validate($this->rules());
            $application = DB::transaction(function () use ($validated) {
                $application = Application::create([
                    'student_id' => $validated['student_id'],
                    'scholarship_id' => $validated['scholarship_id'],
                    'status' => $validated['status'],
                    'description' => $validated['description'] ?? null,
                ]);

                $this->syncRequirementValues($application, $validated['requirement_values'] ?? []);

                return $application;
            });
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil disimpan',
                'data' => $application->load(['student', 'scholarship', 'interviews', 'selection']),
                'redirect' => route('applications.index'),
            ], 201);
        }

        return redirect()->route('applications.index')->with('success', 'Pendaftaran berhasil disimpan.');
    }

    public function show(Application $application)
    {
        $application->load([
            'student',
            'scholarship.requirements.requirement',
            'requirementValues.requirement',
            'interviews.assessments',
            'selection'
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'data' => $application,
            ]);
        }

        return view('applications.show', compact('application'));
    }

    public function edit(Application $application)
    {
        $scholarshipRequirements = $this->scholarshipRequirementsMap();
        $existingRequirementValues = $application->requirementValues()
            ->get(['requirement_id', 'term', 'applicant_value'])
            ->map(fn ($item) => [
                'requirement_id' => $item->requirement_id,
                'term' => $item->term,
                'applicant_value' => $item->applicant_value,
            ])
            ->values()
            ->all();

        return view('applications.form', [
            'application' => $application,
            'action' => route('applications.update', $application),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
            'students' => Student::orderBy('name')->get(),
            'scholarships' => Scholarship::orderBy('scholarship_name')->get(),
            'scholarshipRequirements' => $scholarshipRequirements,
            'existingRequirementValues' => $existingRequirementValues,
        ]);
    }

    public function update(Request $request, Application $application)
    {
        $validated = $request->validate($this->rules());

        DB::transaction(function () use ($application, $validated) {
            $application->update([
                'student_id' => $validated['student_id'],
                'scholarship_id' => $validated['scholarship_id'],
                'status' => $validated['status'],
                'description' => $validated['description'] ?? null,
            ]);

            $this->syncRequirementValues($application, $validated['requirement_values'] ?? []);
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pendaftaran berhasil diupdate',
                'data' => $application->load(['student', 'scholarship', 'interviews', 'selection']),
                'redirect' => route('applications.index'),
            ]);
        }

        return redirect()->route('applications.index')->with('success', 'Data pendaftaran berhasil diupdate.');
    }

    public function destroy(Application $application)
    {
        $application->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pendaftaran berhasil dihapus',
                'redirect' => route('applications.index'),
            ]);
        }

        return redirect()->route('applications.index')->with('success', 'Data pendaftaran berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'scholarship_id' => 'required|integer|exists:scholarships,id',
            'status' => 'required|string|in:menunggu,diproses,diterima,ditolak',
            'description' => 'nullable|string',
            'requirement_values' => 'nullable|array',
            'requirement_values.*.requirement_id' => 'required|integer|exists:requirements,id',
            'requirement_values.*.term' => 'nullable|string',
            'requirement_values.*.applicant_value' => 'nullable|string|max:255',
        ];
    }

    /**
     * @return array<int, array<int, array<string, mixed>>>
     */
    private function scholarshipRequirementsMap(): array
    {
        return ScholarshipRequirement::query()
            ->with('requirement:id,requirement_name')
            ->get()
            ->groupBy('scholarship_id')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'requirement_id' => $item->requirement_id,
                        'requirement_name' => $item->requirement?->requirement_name,
                        'term' => $item->terms,
                    ];
                })->values()->all();
            })
            ->toArray();
    }

    /**
     * @param  array<int, array<string, mixed>>  $requirementValues
     */
    private function syncRequirementValues(Application $application, array $requirementValues): void
    {
        $application->requirementValues()->delete();

        $rows = collect($requirementValues)
            ->filter(fn ($row) => filled($row['applicant_value'] ?? null) || filled($row['term'] ?? null))
            ->map(function ($row) use ($application) {
                return [
                    'application_id' => $application->id,
                    'requirement_id' => $row['requirement_id'],
                    'term' => $row['term'] ?? null,
                    'applicant_value' => $row['applicant_value'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->values()
            ->all();

        if (! empty($rows)) {
            ApplicationRequirementValue::insert($rows);
        }
    }
}
