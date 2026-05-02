<?php

namespace App\Http\Controllers;

use App\Models\FuzzyCriteria;
use Illuminate\Http\Request;

class FuzzyCriteriaController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
        ];

        $query = FuzzyCriteria::query()
            ->with('memberships')
            ->when($filters['search'], function ($q, $search) {
                return $q->where('criteria_name', 'like', "%{$search}%");
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('fuzzy-criteria.index', compact('data', 'filters'));
    }

    public function create()
    {
        return view('fuzzy-criteria.form', [
            'fuzzyCriteria' => new FuzzyCriteria,
            'action' => route('fuzzy-criteria.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan Kriteria',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $criteria = FuzzyCriteria::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kriteria fuzzy berhasil disimpan',
                'data' => $criteria->load('memberships'),
                'redirect' => route('fuzzy-criteria.index'),
            ], 201);
        }

        return redirect()->route('fuzzy-criteria.index')->with('success', 'Kriteria fuzzy berhasil disimpan.');
    }

    public function show(FuzzyCriteria $fuzzy_criterium)
    {
        $fuzzy_criterium->load('memberships');

        if (request()->wantsJson()) {
            return response()->json(['data' => $fuzzy_criterium]);
        }

        return view('fuzzy-criteria.show', ['criteria' => $fuzzy_criterium]);
    }

    public function edit(FuzzyCriteria $fuzzy_criterium)
    {
        return view('fuzzy-criteria.form', [
            'fuzzyCriteria' => $fuzzy_criterium,
            'action' => route('fuzzy-criteria.update', $fuzzy_criterium),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ]);
    }

    public function update(Request $request, FuzzyCriteria $fuzzy_criterium)
    {
        $validated = $request->validate($this->rules());

        $fuzzy_criterium->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kriteria fuzzy berhasil diupdate',
                'data' => $fuzzy_criterium->load('memberships'),
                'redirect' => route('fuzzy-criteria.index'),
            ]);
        }

        return redirect()->route('fuzzy-criteria.index')->with('success', 'Kriteria fuzzy berhasil diupdate.');
    }

    public function destroy(FuzzyCriteria $fuzzy_criterium)
    {
        $fuzzy_criterium->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kriteria fuzzy berhasil dihapus',
                'redirect' => route('fuzzy-criteria.index'),
            ]);
        }

        return redirect()->route('fuzzy-criteria.index')->with('success', 'Kriteria fuzzy berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'criteria_name' => 'required|string|max:255',
        ];
    }
}
