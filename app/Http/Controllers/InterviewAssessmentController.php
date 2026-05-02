<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\InterviewAssessment;
use Illuminate\Http\Request;

class InterviewAssessmentController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
        ];

        $query = InterviewAssessment::query()
            ->with(['interview.application.student', 'interview.application.scholarship'])
            ->when($filters['search'], function ($q, $search) {
                return $q->where('interviewer', 'like', "%{$search}%")
                    ->orWhereHas('interview.application.student', function ($qStudent) use ($search) {
                        $qStudent->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('interview-assessments.index', compact('data', 'filters'));
    }

    public function create(Request $request)
    {
        $assessment = new InterviewAssessment;
        $assessment->interview_id = $request->integer('interview_id') ?: null;

        return view('interview-assessments.form', [
            'assessment' => $assessment,
            'action' => route('interview-assessments.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan Penilaian',
            'interviews' => Interview::with('application.student', 'application.scholarship')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $assessment = InterviewAssessment::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Penilaian berhasil disimpan',
                'data' => $assessment->load(['interview', 'interview.application']),
                'redirect' => route('interview-assessments.index'),
            ], 201);
        }

        return redirect()->route('interview-assessments.index')->with('success', 'Penilaian berhasil disimpan.');
    }

    public function show(InterviewAssessment $interviewAssessment)
    {
        $interviewAssessment->load(['interview.application.student', 'interview.application.scholarship']);

        if (request()->wantsJson()) {
            return response()->json(['data' => $interviewAssessment]);
        }

        return view('interview-assessments.show', ['assessment' => $interviewAssessment]);
    }

    public function edit(InterviewAssessment $interviewAssessment)
    {
        return view('interview-assessments.form', [
            'assessment' => $interviewAssessment,
            'action' => route('interview-assessments.update', $interviewAssessment),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
            'interviews' => Interview::with('application.student', 'application.scholarship')->latest()->get(),
        ]);
    }

    public function update(Request $request, InterviewAssessment $interviewAssessment)
    {
        $validated = $request->validate($this->rules());

        $interviewAssessment->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Penilaian berhasil diupdate',
                'data' => $interviewAssessment->load(['interview', 'interview.application']),
                'redirect' => route('interview-assessments.index'),
            ]);
        }

        return redirect()->route('interview-assessments.index')->with('success', 'Penilaian berhasil diupdate.');
    }

    public function destroy(InterviewAssessment $interviewAssessment)
    {
        $interviewAssessment->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Penilaian berhasil dihapus',
                'redirect' => route('interview-assessments.index'),
            ]);
        }

        return redirect()->route('interview-assessments.index')->with('success', 'Penilaian berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'interview_id' => 'required|integer|exists:interviews,id',
            'score' => 'required|numeric|between:0,999.99',
            'notes' => 'nullable|string',
            'interviewer' => 'required|string|max:100',
        ];
    }
}
