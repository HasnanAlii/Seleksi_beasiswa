<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
        ];

        $query = News::with('media')
            ->when($filters['search'], function ($q, $search) {
                return $q->where('title', 'like', "%{$search}%");
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('news.index', compact('data', 'filters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'additional_photos' => 'nullable|array',
            'additional_photos.*' => 'image|max:4096',
        ]);

        $news = News::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        if ($request->hasFile('additional_photos')) {
            foreach ($request->file('additional_photos') as $photoFile) {
                $path = $photoFile->store('news', 'public');
                $news->media()->create(['file' => $path]);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil ditambahkan.',
                'data' => $news->load('media'),
                'redirect' => route('news.index'),
            ], 201);
        }

        return redirect()->route('news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function create()
    {
        return view('news.form', [
            'news' => new News,
            'action' => route('news.store'),
            'method' => 'POST',
            'submitLabel' => 'Tambah Berita',
        ]);
    }

    public function show(News $news)
    {
        $news->load('media');

        if (request()->wantsJson()) {
            return response()->json(['data' => $news]);
        }

        return view('news.show', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'additional_photos' => 'nullable|array',
            'additional_photos.*' => 'image|max:4096',
        ]);

        $news->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        if ($request->hasFile('additional_photos')) {
            foreach ($request->file('additional_photos') as $photoFile) {
                $path = $photoFile->store('news', 'public');
                $news->media()->create(['file' => $path]);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil diperbarui.',
                'data' => $news->load('media'),
                'redirect' => route('news.index'),
            ]);
        }

        return redirect()->route('news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function edit(News $news)
    {
        return view('news.form', [
            'news' => $news,
            'action' => route('news.update', $news),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ]);
    }

    public function destroy(News $news)
    {
        foreach ($news->media as $media) {
            Storage::disk('public')->delete($media->file);
        }
        $news->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil dihapus',
                'redirect' => route('news.index'),
            ]);
        }

        return redirect()->route('news.index')->with('success', 'Berita berhasil dihapus.');
    }

    public function publicIndex(Request $request)
    {
        $search = $request->get('search', '');
        
        $news = News::with('media')
            ->when($search, function ($q, $search) {
                return $q->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12)->withQueryString();

        return view('news.public_index', compact('news', 'search'));
    }

    public function read(News $news)
    {
        $news->load('media');
        
        // Fetch recent news for sidebar/related
        $recentNews = News::where('id', '!=', $news->id)
            ->latest()
            ->take(5)
            ->get();

        return view('news.read', compact('news', 'recentNews'));
    }
}
