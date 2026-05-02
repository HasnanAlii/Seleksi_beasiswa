<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipRequirement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScholarshipRequirementController extends Controller
{
    public function index()
    {
        $data = ScholarshipRequirement::query()->with(['scholarship', 'requirement'])->latest()->get();
        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('scholarship-requirements.index', ['data' => $data]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $sr = ScholarshipRequirement::create($validated);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $sr->load(['scholarship', 'requirement']),
        ], 201);
    }

    public function show(ScholarshipRequirement $scholarshipRequirement): JsonResponse
    {
        return response()->json([
            'data' => $scholarshipRequirement->load(['scholarship', 'requirement']),
        ]);
    }

    public function update(Request $request, ScholarshipRequirement $scholarshipRequirement): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $scholarshipRequirement->update($validated);

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'data' => $scholarshipRequirement->load(['scholarship', 'requirement']),
        ]);
    }

    public function destroy(ScholarshipRequirement $scholarshipRequirement): JsonResponse
    {
        $scholarshipRequirement->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'scholarship_id' => 'required|integer|exists:scholarships,id',
            'requirement_id' => 'required|integer|exists:requirements,id',
            'terms' => 'nullable|string',
        ];
    }
}
