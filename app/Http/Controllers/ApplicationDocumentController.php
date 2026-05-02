<?php

namespace App\Http\Controllers;

use App\Models\ApplicationDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationDocumentController extends Controller
{
    public function index()
    {
        $data = ApplicationDocument::query()->with('application')->latest()->get();
        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('application-documents.index', ['data' => $data]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $doc = ApplicationDocument::create($validated);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $doc->load('application'),
        ], 201);
    }

    public function show(ApplicationDocument $applicationDocument): JsonResponse
    {
        return response()->json([
            'data' => $applicationDocument->load('application'),
        ]);
    }

    public function update(Request $request, ApplicationDocument $applicationDocument): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $applicationDocument->update($validated);

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'data' => $applicationDocument->load('application'),
        ]);
    }

    public function destroy(ApplicationDocument $applicationDocument): JsonResponse
    {
        $applicationDocument->delete();

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
            'application_id' => 'required|integer|exists:applications,id',
            'document_type' => 'required|string|max:255',
            'file_path' => 'required|string|max:255',
        ];
    }
}
