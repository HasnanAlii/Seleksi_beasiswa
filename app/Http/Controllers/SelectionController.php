<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Scholarship;
use App\Models\Selection;
use App\Services\FuzzySelectionService;
use Illuminate\Http\Request;

class SelectionController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
            'status' => $request->get('status', ''),
            'scholarship_id' => $request->get('scholarship_id', ''),
            'stage' => $request->get('stage', ''),
        ];

        $query = Selection::query()
            ->with(['application.student', 'application.scholarship'])
            ->when($filters['search'], function ($q, $search) {
                return $q->whereHas('application.student', function ($qStudent) use ($search) {
                    $qStudent->where('name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'], function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when($filters['scholarship_id'], function ($q, $scholarshipId) {
                return $q->whereHas('application', function ($qApp) use ($scholarshipId) {
                    $qApp->where('scholarship_id', $scholarshipId);
                });
            })
            ->when($filters['stage'], function ($q, $stage) {
                return $q->where('stage', $stage);
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();
        $scholarships = Scholarship::orderBy('scholarship_name')->get();
        $stages = Selection::distinct()->pluck('stage')->filter();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('selections.index', compact('data', 'filters', 'scholarships', 'stages'));
    }

    public function create()
    {
        return view('selections.form', [
            'selection' => new Selection,
            'action' => route('selections.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan Data Seleksi',
            'applications' => Application::with('student', 'scholarship')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $selection = Selection::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data seleksi berhasil disimpan',
                'data' => $selection->load(['application.student', 'application.scholarship']),
                'redirect' => route('selections.index'),
            ], 201);
        }

        return redirect()->route('selections.index')->with('success', 'Data seleksi berhasil disimpan.');
    }

    public function show(Selection $selection)
    {
        $selection->load([
            'application.student',
            'application.scholarship.requirements.requirement',
            'application.requirementValues.requirement',
            'application.interviews.assessments',
        ]);

        if (request()->wantsJson()) {
            return response()->json(['data' => $selection]);
        }

        return view('selections.show', compact('selection'));
    }

    public function edit(Selection $selection)
    {
        return view('selections.form', [
            'selection' => $selection,
            'action' => route('selections.update', $selection),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
            'applications' => Application::with('student', 'scholarship')->latest()->get(),
        ]);
    }

    public function update(Request $request, Selection $selection)
    {
        $validated = $request->validate($this->rules());

        $selection->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data seleksi berhasil diupdate',
                'data' => $selection->load(['application.student', 'application.scholarship']),
                'redirect' => route('selections.index'),
            ]);
        }

        return redirect()->route('selections.index')->with('success', 'Data seleksi berhasil diupdate.');
    }

    public function destroy(Selection $selection)
    {
        $selection->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data seleksi berhasil dihapus',
                'redirect' => route('selections.index'),
            ]);
        }

        return redirect()->route('selections.index')->with('success', 'Data seleksi berhasil dihapus.');
    }

    /**
     * Preview fuzzy logic selection results (without applying).
     */
    public function previewFuzzy(FuzzySelectionService $service)
    {
        $results = $service->runBatch();

        $layakCount = collect($results)->where('recommended_status', 'layak')->count();
        $tidakLayakCount = collect($results)->where('recommended_status', 'tidak layak')->count();

        return view('selections.fuzzy-preview', compact(
            'results',
            'layakCount',
            'tidakLayakCount',
        ));
    }

    /**
     * Apply fuzzy logic results to create/update Selection records.
     */
    public function applyFuzzy(Request $request, FuzzySelectionService $service)
    {
        $results = $service->runBatch();
        $applied = $service->applyResults($results);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Seleksi AI selesai. {$applied} data kelayakan berhasil diperbarui.",
                'applied' => $applied,
                'redirect' => route('selections.index'),
            ]);
        }

        return redirect()->route('selections.index')
            ->with('success', "Seleksi AI (Fuzzy Logic) selesai! {$applied} data kelayakan telah diperbarui.");
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'application_id' => 'required|integer|exists:applications,id',
            'stage' => 'required|string|max:255',
            'status' => 'required|string|in:verifikasi,wawancara,siap di proses,diterima,tidak diterima',
            'notes' => 'nullable|string',
            'date' => 'required|date',
        ];
    }
}
