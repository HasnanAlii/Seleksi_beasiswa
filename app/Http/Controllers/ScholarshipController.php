<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScholarshipController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
        ];

        $query = Scholarship::query()
            ->when($filters['search'], function ($q, $search) {
                return $q->where('scholarship_name', 'like', "%{$search}%")
                    ->orWhere('scholarship_type', 'like', "%{$search}%");
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('scholarships.index', compact('data', 'filters'));
    }

    public function create()
    {
        return view('scholarships.form', [
            'scholarship' => new Scholarship,
            'scholarshipTypes' => ScholarshipType::query()->orderBy('name')->get(),
            'requirements' => Requirement::query()->orderBy('requirement_name')->get(),
            'selectedRequirementIds' => [],
            'requirementTerms' => [],
            'action' => route('scholarships.store'),
            'method' => 'POST',
            'submitLabel' => 'Tambah Beasiswa',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $scholarship = DB::transaction(function () use ($validated) {
            $scholarship = Scholarship::create([
                'scholarship_name' => $validated['scholarship_name'],
                'scholarship_type' => $validated['scholarship_type'],
                'description' => $validated['description'] ?? null,
                'quota' => $validated['quota'],
                'validity_period' => $validated['validity_period'],
            ]);

            $requirementRows = collect($validated['requirement_ids'] ?? [])
                ->map(fn ($requirementId) => [
                    'scholarship_id' => $scholarship->id,
                    'requirement_id' => $requirementId,
                    'terms' => data_get($validated, "requirement_terms.{$requirementId}"),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
                ->all();

            if (! empty($requirementRows)) {
                ScholarshipRequirement::insert($requirementRows);
            }

            return $scholarship;
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Beasiswa berhasil ditambahkan.',
                'data' => $scholarship->load(['announcements', 'applications', 'requirements']),
                'redirect' => route('scholarships.index'),
            ], 201);
        }

        return redirect()->route('scholarships.index')->with('success', 'Beasiswa berhasil ditambahkan.');
    }

    public function show(Scholarship $scholarship)
    {
        $scholarship->load(['announcements', 'applications', 'requirements']);

        if (request()->wantsJson()) {
            return response()->json(['data' => $scholarship]);
        }

        return view('scholarships.show', compact('scholarship'));
    }

    public function edit(Scholarship $scholarship)
    {
        $selectedRequirementIds = $scholarship->requirements()
            ->pluck('requirement_id')
            ->all();

        $requirementTerms = $scholarship->requirements()
            ->pluck('terms', 'requirement_id')
            ->toArray();

        return view('scholarships.form', [
            'scholarship' => $scholarship,
            'scholarshipTypes' => ScholarshipType::query()->orderBy('name')->get(),
            'requirements' => Requirement::query()->orderBy('requirement_name')->get(),
            'selectedRequirementIds' => $selectedRequirementIds,
            'requirementTerms' => $requirementTerms,
            'action' => route('scholarships.update', $scholarship),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ]);
    }

    public function update(Request $request, Scholarship $scholarship)
    {
        $validated = $request->validate($this->rules());
        DB::transaction(function () use ($validated, $scholarship) {
            $scholarship->update([
                'scholarship_name' => $validated['scholarship_name'],
                'scholarship_type' => $validated['scholarship_type'],
                'description' => $validated['description'] ?? null,
                'quota' => $validated['quota'],
                'validity_period' => $validated['validity_period'],
            ]);

            ScholarshipRequirement::query()
                ->where('scholarship_id', $scholarship->id)
                ->delete();

            $requirementRows = collect($validated['requirement_ids'] ?? [])
                ->map(fn ($requirementId) => [
                    'scholarship_id' => $scholarship->id,
                    'requirement_id' => $requirementId,
                    'terms' => data_get($validated, "requirement_terms.{$requirementId}"),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
                ->all();

            if (! empty($requirementRows)) {
                ScholarshipRequirement::insert($requirementRows);
            }
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Beasiswa berhasil diperbarui.',
                'data' => $scholarship->load(['announcements', 'applications', 'requirements']),
                'redirect' => route('scholarships.index'),
            ]);
        }

        return redirect()->route('scholarships.index')->with('success', 'Beasiswa berhasil diperbarui.');
    }

    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Beasiswa berhasil dihapus.',
                'redirect' => route('scholarships.index'),
            ]);
        }

        return redirect()->route('scholarships.index')->with('success', 'Beasiswa berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'scholarship_name' => 'required|string|max:100',
            'scholarship_type' => 'required|string|max:50|exists:scholarship_types,name',
            'description' => 'nullable|string',
            'quota' => 'required|integer|min:1',
            'validity_period' => 'required|date',
            'requirement_ids' => 'nullable|array',
            'requirement_ids.*' => 'integer|exists:requirements,id',
            'requirement_terms' => 'nullable|array',
            'requirement_terms.*' => 'nullable|string',
        ];
    }
}
