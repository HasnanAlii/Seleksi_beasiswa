<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipType;
use Illuminate\Http\Request;

class ScholarshipTypeController extends Controller
{
    public function index(Request $request)
    {
        $filters = ['search' => $request->get('search', '')];

        $data = ScholarshipType::query()
            ->when($filters['search'], fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('scholarship-types.index', compact('data', 'filters'));
    }

    public function create()
    {
        return view('scholarship-types.form', [
            'scholarshipType' => new ScholarshipType,
            'action' => route('scholarship-types.store'),
            'method' => 'POST',
            'submitLabel' => 'Tambah Jenis Beasiswa',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        ScholarshipType::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jenis beasiswa berhasil ditambahkan.',
                'redirect' => route('scholarship-types.index'),
            ], 201);
        }

        return redirect()->route('scholarship-types.index')
            ->with('success', 'Jenis beasiswa berhasil ditambahkan.');
    }

    public function show(ScholarshipType $scholarshipType)
    {
        return view('scholarship-types.show', compact('scholarshipType'));
    }

    public function edit(ScholarshipType $scholarshipType)
    {
        return view('scholarship-types.form', [
            'scholarshipType' => $scholarshipType,
            'action' => route('scholarship-types.update', $scholarshipType),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ]);
    }

    public function update(Request $request, ScholarshipType $scholarshipType)
    {
        $validated = $request->validate($this->rules($scholarshipType->id));

        $scholarshipType->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jenis beasiswa berhasil diperbarui.',
                'redirect' => route('scholarship-types.index'),
            ]);
        }

        return redirect()->route('scholarship-types.index')
            ->with('success', 'Jenis beasiswa berhasil diperbarui.');
    }

    public function destroy(ScholarshipType $scholarshipType)
    {
        $scholarshipType->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jenis beasiswa berhasil dihapus.',
                'redirect' => route('scholarship-types.index'),
            ]);
        }

        return redirect()->route('scholarship-types.index')
            ->with('success', 'Jenis beasiswa berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function rules(?int $ignoreId = null): array
    {
        return [
            'name' => 'required|string|max:100|unique:scholarship_types,name'.($ignoreId ? ",{$ignoreId}" : ''),
            'description' => 'nullable|string',
        ];
    }
}
