<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Interview;
use App\Models\Scholarship;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
            'scholarship_id' => $request->get('scholarship_id', ''),
        ];

        $query = Interview::query()
            ->with(['application.student', 'application.scholarship', 'assessments'])
            ->when($filters['search'], function ($q, $search) {
                return $q->whereHas('application.student', function ($qStudent) use ($search) {
                    $qStudent->where('name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%");
                });
            })
            ->when($filters['scholarship_id'], function ($q, $scholarshipId) {
                return $q->whereHas('application', function ($qApp) use ($scholarshipId) {
                    $qApp->where('scholarship_id', $scholarshipId);
                });
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();
        $scholarships = Scholarship::orderBy('scholarship_name')->get();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('interviews.index', compact('data', 'filters', 'scholarships'));
    }

    public function create()
    {
        $scholarships = Scholarship::orderBy('scholarship_name')->get();

        return view('interviews.create', compact('scholarships'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scholarship_id' => 'nullable|exists:scholarships,id',
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after_or_equal:date_from',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'location' => 'nullable|string|max:255',
        ]);

        $applicationsQuery = Application::with('student', 'scholarship')
            ->whereIn('status', ['diproses', 'menunggu'])
            ->doesntHave('interviews');

        if (! empty($validated['scholarship_id'])) {
            $applicationsQuery->where('scholarship_id', $validated['scholarship_id']);
        }

        $applications = $applicationsQuery->get()->shuffle();

        if ($applications->isEmpty()) {
            $errorMessage = 'Tidak ada pendaftar yang dapat dijadwalkan (sudah terjadwal semua atau tidak ada yang berstatus diproses/menunggu).';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 422);
            }

            return back()->withInput()->with('error', $errorMessage);
        }

        // Generate time slots across the date range
        $slots = [];
        $dateFrom = Carbon::parse($validated['date_from']);
        $dateTo = Carbon::parse($validated['date_to']);
        $duration = (int) $validated['duration_minutes'];

        for ($date = $dateFrom->copy(); $date->lte($dateTo); $date->addDay()) {
            $slotStart = Carbon::parse($date->format('Y-m-d').' '.$validated['time_start']);
            $slotEnd = Carbon::parse($date->format('Y-m-d').' '.$validated['time_end']);

            while ($slotStart->copy()->addMinutes($duration)->lte($slotEnd)) {
                $currentSlotEnd = $slotStart->copy()->addMinutes($duration);

                // Jam istirahat: 11:30 - 12:30
                $breakStart = $slotStart->copy()->setTime(11, 30, 0);
                $breakEnd = $slotStart->copy()->setTime(12, 30, 0);

                // Slot dianggap bersinggungan jika:
                // Mulai sebelum istirahat selesai DAN Selesai setelah istirahat mulai
                $isOverlap = $slotStart->lt($breakEnd) && $currentSlotEnd->gt($breakStart);

                if (! $isOverlap) {
                    $slots[] = $slotStart->copy();
                }

                $slotStart->addMinutes($duration);
            }
        }

        if (empty($slots)) {
            $errorMessage = 'Rentang waktu tidak cukup untuk menghasilkan slot wawancara. Perbesar rentang tanggal atau kurangi durasi per sesi.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 422);
            }

            return back()->withInput()->with('error', $errorMessage);
        }

        $scheduledCount = min($applications->count(), count($slots));

        for ($i = 0; $i < $scheduledCount; $i++) {
            Interview::create([
                'application_id' => $applications[$i]->id,
                'schedule' => $slots[$i],
                'location' => $validated['location'] ?? null,
            ]);
        }

        $unscheduledCount = $applications->count() - $scheduledCount;
        $message = "{$scheduledCount} jadwal wawancara berhasil dibuat secara otomatis.";

        if ($unscheduledCount > 0) {
            $message .= " {$unscheduledCount} pendaftar tidak dapat dijadwalkan karena slot tidak mencukupi.";
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('interviews.index'),
            ]);
        }

        return redirect()->route('interviews.index')->with('success', $message);
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
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
