<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
            'scholarship_id' => $request->get('scholarship_id', ''),
        ];

        $query = Announcement::query()->with('scholarship')
            ->when($filters['search'], function ($q, $search) {
                return $q->where('title', 'like', "%{$search}%");
            })
            ->when($filters['scholarship_id'], function ($q, $scholarship_id) {
                return $q->where('scholarship_id', $scholarship_id);
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();
        $scholarships = Scholarship::orderBy('scholarship_name')->get();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('announcements.index', compact('data', 'filters', 'scholarships'));
    }

    public function create()
    {
        return view('announcements.form', [
            'announcement' => new Announcement,
            'action' => route('announcements.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan Pengumuman',
            'scholarships' => Scholarship::orderBy('scholarship_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $announcement = Announcement::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil ditambahkan.',
                'data' => $announcement->load('scholarship'),
                'redirect' => route('announcements.index'),
            ], 201);
        }

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function show(Announcement $announcement)
    {
        $announcement->load('scholarship');

        if (request()->wantsJson()) {
            return response()->json([
                'data' => $announcement,
            ]);
        }

        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.form', [
            'announcement' => $announcement,
            'action' => route('announcements.update', $announcement),
            'method' => 'PUT',
            'submitLabel' => 'Perbarui Pengumuman',
            'scholarships' => Scholarship::orderBy('scholarship_name')->get(),
        ]);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate($this->rules());

        $announcement->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil diperbarui.',
                'data' => $announcement->load('scholarship'),
                'redirect' => route('announcements.index'),
            ]);
        }

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil dihapus.',
                'redirect' => route('announcements.index'),
            ]);
        }

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'scholarship_id' => 'required|integer|exists:scholarships,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'publish_status' => 'required|boolean',
        ];
    }
}
