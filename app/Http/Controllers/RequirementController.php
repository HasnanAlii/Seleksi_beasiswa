<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
        ];

        $query = Requirement::query()
            ->with(['scholarshipRequirements', 'scholarshipRequirements.scholarship'])
            ->when($filters['search'], function ($q, $search) {
                return $q->where('requirement_name', 'like', "%{$search}%");
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('requirements.index', compact('data', 'filters'));
    }

    public function create()
    {
        return view('requirements.form', [
            'requirement' => new Requirement,
            'action' => route('requirements.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan Persyaratan',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $requirement = Requirement::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Persyaratan berhasil disimpan',
                'data' => $requirement->load(['scholarshipRequirements', 'scholarshipRequirements.scholarship']),
                'redirect' => route('requirements.index'),
            ], 201);
        }

        return redirect()->route('requirements.index')->with('success', 'Persyaratan berhasil disimpan.');
    }

    public function show(Requirement $requirement)
    {
        $requirement->load(['scholarshipRequirements', 'scholarshipRequirements.scholarship']);

        if (request()->wantsJson()) {
            return response()->json(['data' => $requirement]);
        }

        return view('requirements.show', compact('requirement'));
    }

    public function edit(Requirement $requirement)
    {
        return view('requirements.form', [
            'requirement' => $requirement,
            'action' => route('requirements.update', $requirement),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ]);
    }

    public function update(Request $request, Requirement $requirement)
    {
        $validated = $request->validate($this->rules());

        $requirement->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Persyaratan berhasil diupdate',
                'data' => $requirement->load(['scholarshipRequirements', 'scholarshipRequirements.scholarship']),
                'redirect' => route('requirements.index'),
            ]);
        }

        return redirect()->route('requirements.index')->with('success', 'Persyaratan berhasil diupdate.');
    }

    public function destroy(Requirement $requirement)
    {
        $requirement->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Persyaratan berhasil dihapus',
                'redirect' => route('requirements.index'),
            ]);
        }

        return redirect()->route('requirements.index')->with('success', 'Persyaratan berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'requirement_name' => 'required|string|max:100',
        ];
    }
}
