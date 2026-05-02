<?php

namespace App\Http\Controllers;

use App\Models\FuzzyCriteria;
use App\Models\FuzzyMembership;
use Illuminate\Http\Request;

class FuzzyMembershipController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
            'criteria_id' => $request->get('criteria_id', ''),
            'label' => $request->get('label', ''),
        ];

        $query = FuzzyMembership::query()
            ->with('criteria')
            ->when($filters['search'], function ($q, $search) {
                return $q->whereHas('criteria', function ($qC) use ($search) {
                    $qC->where('criteria_name', 'like', "%{$search}%");
                });
            })
            ->when($filters['criteria_id'], function ($q, $criteriaId) {
                return $q->where('criteria_id', $criteriaId);
            })
            ->when($filters['label'], function ($q, $label) {
                return $q->where('label', $label);
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();
        $criteriaList = FuzzyCriteria::orderBy('criteria_name')->get();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('fuzzy-memberships.index', compact('data', 'filters', 'criteriaList'));
    }

    public function create()
    {
        return view('fuzzy-memberships.form', [
            'membership' => new FuzzyMembership,
            'action' => route('fuzzy-memberships.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan Keanggotaan',
            'criteriaList' => FuzzyCriteria::orderBy('criteria_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $membership = FuzzyMembership::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Fungsi keanggotaan berhasil disimpan',
                'data' => $membership->load('criteria'),
                'redirect' => route('fuzzy-memberships.index'),
            ], 201);
        }

        return redirect()->route('fuzzy-memberships.index')->with('success', 'Fungsi keanggotaan berhasil disimpan.');
    }

    public function show(FuzzyMembership $fuzzyMembership)
    {
        $fuzzyMembership->load('criteria');

        if (request()->wantsJson()) {
            return response()->json(['data' => $fuzzyMembership]);
        }

        return view('fuzzy-memberships.show', ['membership' => $fuzzyMembership]);
    }

    public function edit(FuzzyMembership $fuzzyMembership)
    {
        return view('fuzzy-memberships.form', [
            'membership' => $fuzzyMembership,
            'action' => route('fuzzy-memberships.update', $fuzzyMembership),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
            'criteriaList' => FuzzyCriteria::orderBy('criteria_name')->get(),
        ]);
    }

    public function update(Request $request, FuzzyMembership $fuzzyMembership)
    {
        $validated = $request->validate($this->rules());

        $fuzzyMembership->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Fungsi keanggotaan berhasil diupdate',
                'data' => $fuzzyMembership->load('criteria'),
                'redirect' => route('fuzzy-memberships.index'),
            ]);
        }

        return redirect()->route('fuzzy-memberships.index')->with('success', 'Fungsi keanggotaan berhasil diupdate.');
    }

    public function destroy(FuzzyMembership $fuzzyMembership)
    {
        $fuzzyMembership->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Fungsi keanggotaan berhasil dihapus',
                'redirect' => route('fuzzy-memberships.index'),
            ]);
        }

        return redirect()->route('fuzzy-memberships.index')->with('success', 'Fungsi keanggotaan berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'criteria_id' => 'required|integer|exists:fuzzy_criteria,id',
            'label' => 'required|string|in:rendah,sedang,tinggi',
            'min_value' => 'required|numeric',
            'mid_value' => 'required|numeric',
            'max_value' => 'required|numeric',
        ];
    }
}
