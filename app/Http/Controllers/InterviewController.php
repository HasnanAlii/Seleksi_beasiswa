<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Interview;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
        ];

        $query = Interview::query()
            ->with(['application.student', 'application.scholarship', 'assessments'])
            ->when($filters['search'], function ($q, $search) {
                return $q->whereHas('application.student', function ($qStudent) use ($search) {
                    $qStudent->where('name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%");
                });
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('interviews.index', compact('data', 'filters'));
    }

    public function create()
    {
        return view('interviews.form', [
            'interview' => new Interview,
            'action' => route('interviews.store'),
            'method' => 'POST',
            'submitLabel' => 'Tambah Jadwal',
            'applications' => Application::with('student', 'scholarship')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $interview = Interview::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal wawancara berhasil disimpan',
                'data' => $interview->load(['application', 'assessments']),
                'redirect' => route('interviews.index'),
            ], 201);
        }

        return redirect()->route('interviews.index')->with('success', 'Jadwal wawancara berhasil disimpan.');
    }

    public function show(Interview $interview)
    {
        $interview->load(['application.student', 'application.scholarship', 'assessments']);

        if (request()->wantsJson()) {
            return response()->json(['data' => $interview]);
        }

        return view('interviews.show', compact('interview'));
    }

    public function edit(Interview $interview)
    {
        return view('interviews.form', [
            'interview' => $interview,
            'action' => route('interviews.update', $interview),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
            'applications' => Application::with('student', 'scholarship')->latest()->get(),
        ]);
    }

    public function update(Request $request, Interview $interview)
    {
        $validated = $request->validate($this->rules());

        $interview->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal wawancara berhasil diupdate',
                'data' => $interview->load(['application', 'assessments']),
                'redirect' => route('interviews.index'),
            ]);
        }

        return redirect()->route('interviews.index')->with('success', 'Jadwal wawancara berhasil diupdate.');
    }

    public function destroy(Interview $interview)
    {
        $interview->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal wawancara berhasil dihapus',
                'redirect' => route('interviews.index'),
            ]);
        }

        return redirect()->route('interviews.index')->with('success', 'Jadwal wawancara berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'application_id' => 'required|integer|exists:applications,id',
            'schedule' => 'required|date',
            'description' => 'nullable|string',
        ];
    }
}
