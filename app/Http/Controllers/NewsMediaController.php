<?php

namespace App\Http\Controllers;

use App\Models\NewsMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsMediaController extends Controller
{
    public function index()
    {
        $data = NewsMedia::query()->with('news')->latest()->get();
        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('news-media.index', ['data' => $data]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $media = NewsMedia::create($validated);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $media->load('news'),
        ], 201);
    }

    public function show(NewsMedia $newsMedia): JsonResponse
    {
        return response()->json([
            'data' => $newsMedia->load('news'),
        ]);
    }

    public function update(Request $request, NewsMedia $newsMedia): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $newsMedia->update($validated);

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'data' => $newsMedia->load('news'),
        ]);
    }

    public function destroy(NewsMedia $newsMedia): JsonResponse
    {
        Storage::disk('public')->delete($newsMedia->file);
        $newsMedia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dihapus',
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'news_id' => 'required|integer|exists:news,id',
            'file' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }
}
