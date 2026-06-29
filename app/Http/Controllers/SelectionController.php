<?php

namespace App\Http\Controllers;

use App\Exports\SelectionExport;
use App\Imports\SelectionUpdateImport;
use App\Models\Application;
use App\Models\Scholarship;
use App\Models\Selection;
use App\Services\FuzzySelectionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SelectionController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
            'status' => $request->get('status', ''),
            'scholarship_id' => $request->get('scholarship_id', ''),
            'stage' => $request->get('stage', ''),
        ];

        $query = Selection::query()
            ->with(['application.student', 'application.scholarship'])
            ->when($filters['search'], function ($q, $search) {
                return $q->whereHas('application.student', function ($qStudent) use ($search) {
                    $qStudent->where('name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'], function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when($filters['scholarship_id'], function ($q, $scholarshipId) {
                return $q->whereHas('application', function ($qApp) use ($scholarshipId) {
                    $qApp->where('scholarship_id', $scholarshipId);
                });
            })
            ->when($filters['stage'], function ($q, $stage) {
                return $q->where('stage', $stage);
            })
            ->latest();

        $data = $query->paginate(15)->withQueryString();
        $scholarships = Scholarship::orderBy('scholarship_name')->get();
        $stages = Selection::distinct()->pluck('stage')->filter();

        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('selections.index', compact('data', 'filters', 'scholarships', 'stages'));
    }

    public function create()
    {
        return view('selections.form', [
            'selection' => new Selection,
            'action' => route('selections.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan Data Seleksi',
            'applications' => Application::with('student', 'scholarship')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $selection = Selection::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data seleksi berhasil disimpan',
                'data' => $selection->load(['application.student', 'application.scholarship']),
                'redirect' => route('selections.index'),
            ], 201);
        }

        return redirect()->route('selections.index')->with('success', 'Data seleksi berhasil disimpan.');
    }

    public function show(Selection $selection)
    {
        $selection->load([
            'application.student',
            'application.scholarship.requirements.requirement',
            'application.requirementValues.requirement',
            'application.requirementValues.documents',
            'application.interviews.assessments',
        ]);

        if (request()->wantsJson()) {
            return response()->json(['data' => $selection]);
        }

        return view('selections.show', compact('selection'));
    }

    public function edit(Selection $selection)
    {
        return view('selections.form', [
            'selection' => $selection,
            'action' => route('selections.update', $selection),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
            'applications' => Application::with('student', 'scholarship')->latest()->get(),
        ]);
    }

    public function update(Request $request, Selection $selection)
    {
        $validated = $request->validate($this->rules());

        $selection->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data seleksi berhasil diupdate',
                'data' => $selection->load(['application.student', 'application.scholarship']),
                'redirect' => route('selections.index'),
            ]);
        }

        return redirect()->route('selections.index')->with('success', 'Data seleksi berhasil diupdate.');
    }

    public function destroy(Selection $selection)
    {
        $selection->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data seleksi berhasil dihapus',
                'redirect' => route('selections.index'),
            ]);
        }

        return redirect()->route('selections.index')->with('success', 'Data seleksi berhasil dihapus.');
    }

    /**
     * Export selection data as Excel or PDF.
     */
    public function export(Request $request)
    {
        $filters = [
            'search' => $request->get('search', ''),
            'status' => $request->get('status', ''),
            'scholarship_id' => $request->get('scholarship_id', ''),
            'stage' => $request->get('stage', ''),
        ];

        $format = $request->get('format', 'excel');

        if ($format === 'pdf') {
            $data = Selection::query()
                ->with([
                    'application.student',
                    'application.scholarship',
                    'application.requirementValues.requirement',
                    'application.interviews.assessments',
                ])
                ->when($filters['search'], function ($q, $search) {
                    $q->whereHas('application.student', fn ($qs) => $qs
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%")
                    );
                })
                ->when($filters['status'], fn ($q, $s) => $q->where('status', $s))
                ->when($filters['scholarship_id'], function ($q, $id) {
                    $q->whereHas('application', fn ($qa) => $qa->where('scholarship_id', $id));
                })
                ->when($filters['stage'], fn ($q, $s) => $q->where('stage', $s))
                ->latest()
                ->get();

            $pdf = Pdf::loadView('selections.export-pdf', compact('data', 'filters'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('laporan-seleksi-'.now()->format('Ymd_His').'.pdf');
        }

        return Excel::download(
            new SelectionExport($filters),
            'laporan-seleksi-'.now()->format('Ymd_His').'.xlsx'
        );
    }

    /**
     * Preview import data from Excel
     */
    public function importPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        try {
            $rows = Excel::toArray(new SelectionUpdateImport, $file);
            $sheet = $rows[0] ?? [];
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file Excel. Pastikan format file sesuai.');
        }

        $headerRowIndex = -1;
        foreach ($sheet as $index => $row) {
            if (isset($row[0]) && strtolower(trim((string) $row[0])) === 'no') {
                $headerRowIndex = $index;
                break;
            }
        }

        if ($headerRowIndex === -1) {
            return back()->with('error', 'Format tabel tidak dikenali. Pastikan ada baris header yang diawali dengan "No".');
        }

        $headers = array_map(fn ($h) => strtolower(trim((string) $h)), $sheet[$headerRowIndex]);

        // Cari kolom dengan exact match terlebih dahulu, agar 'status dtks' tidak
        // tersalah-ambil sebagai 'status'.
        $npmIndex = array_search('npm', $headers);
        $beasiswaIndex = array_search('beasiswa', $headers);
        $tahapIndex = array_search('tahap', $headers);

        // Cari kolom nama mahasiswa ("nama mahasiswa" atau "nama")
        $namaIndex = false;
        foreach ($headers as $idx => $h) {
            if ($h === 'nama mahasiswa' || $h === 'nama') {
                $namaIndex = $idx;
                break;
            }
        }

        // 'status' — exact match (bukan 'status dtks', 'status wawancara', dst)
        $statusIndex = false;
        foreach ($headers as $idx => $h) {
            if ($h === 'status') {
                $statusIndex = $idx;
                break;
            }
        }

        /**
         * Normalisasi status Excel → nilai internal DB.
         * Export menggunakan label yang lebih ramah (Tidak Layak, Layak),
         * sementara DB menyimpan: 'layak', 'tidak diterima', dsb.
         */
        $normalizeStatus = fn (string $s): string => match ($s) {
            'tidak layak' => 'tidak diterima',
            'layak' => 'layak',
            'diterima' => 'diterima',
            'tidak diterima' => 'tidak diterima',
            default => $s,
        };

        if ($beasiswaIndex === false || $statusIndex === false) {
            return back()->with('error', 'Kolom wajib (Beasiswa, Status) tidak ditemukan di file Excel.');
        }

        if ($npmIndex === false && $namaIndex === false) {
            return back()->with('error', 'Kolom NPM atau Nama Mahasiswa harus ada di file Excel.');
        }

        $previewData = [];

        for ($i = $headerRowIndex + 1; $i < count($sheet); $i++) {
            $row = $sheet[$i];
            if (empty(trim((string) ($row[0] ?? '')))) {
                continue;
            }

            $npm = $npmIndex !== false ? trim((string) ($row[$npmIndex] ?? '')) : '';
            // Anggap NPM tidak valid jika kosong atau hanya berisi '-'
            $npmValid = $npm && $npm !== '-';
            $namaExcel = $namaIndex !== false ? trim((string) ($row[$namaIndex] ?? '')) : '';
            $beasiswa = trim((string) ($row[$beasiswaIndex] ?? ''));
            $rawStatus = strtolower(trim((string) ($row[$statusIndex] ?? '')));
            $statusExcel = $normalizeStatus($rawStatus);
            $tahapExcel = $tahapIndex !== false ? trim((string) ($row[$tahapIndex] ?? '')) : null;

            if (! $beasiswa || ! $statusExcel) {
                continue;
            }

            if (! $npmValid && ! $namaExcel) {
                continue;
            }

            // Cari selection: utamakan NPM, fallback ke nama mahasiswa
            $selectionQuery = Selection::with(['application.student', 'application.scholarship'])
                ->whereHas('application.scholarship', fn ($q) => $q->where('scholarship_name', $beasiswa));

            if ($npmValid) {
                $selectionQuery->whereHas('application.student', fn ($q) => $q->where('student_number', $npm));
            } elseif ($namaExcel) {
                $selectionQuery->whereHas('application.student', fn ($q) => $q->where('name', 'like', $namaExcel));
            }

            $selection = $selectionQuery->first();

            if ($selection) {
                // Jika status diterima, tahap otomatis menjadi "Divalidasi Universitas"
                if ($statusExcel === 'diterima') {
                    $tahapExcel = 'Divalidasi Universitas';
                }

                $statusChanged = strtolower($selection->status) !== $statusExcel;
                $tahapChanged = $tahapExcel && strtolower($selection->stage) !== strtolower($tahapExcel);
                $changed = $statusChanged || $tahapChanged;
                $previewData[] = [
                    'selection_id' => $selection->id,
                    'application_id' => $selection->application_id,
                    'npm' => $npm,
                    'student_name' => $selection->application->student->name,
                    'scholarship_name' => $beasiswa,
                    'old_status' => $selection->status,
                    'new_status' => $statusExcel,
                    'old_stage' => $selection->stage,
                    'new_stage' => $tahapExcel ?? $selection->stage,
                    'changed' => $changed,
                ];
            }
        }

        $cacheKey = 'import_selection_'.auth()->id();
        cache()->put($cacheKey, collect($previewData)->where('changed', true)->toArray(), now()->addHour());

        return view('selections.import-preview', [
            'previewData' => collect($previewData),
            'cacheKey' => $cacheKey,
        ]);
    }

    /**
     * Apply import updates
     */
    public function importApply(Request $request)
    {
        $cacheKey = $request->input('cache_key');
        $updates = cache()->get($cacheKey);

        if (! $updates) {
            return redirect()->route('selections.index')->with('error', 'Sesi import telah kedaluwarsa atau tidak valid. Silakan upload ulang file.');
        }

        $count = 0;
        foreach ($updates as $data) {
            $selection = Selection::find($data['selection_id']);
            if ($selection) {
                $updatePayload = ['status' => $data['new_status']];
                if (! empty($data['new_stage'])) {
                    $updatePayload['stage'] = $data['new_stage'];
                }
                $selection->update($updatePayload);

                $appStatus = $data['new_status'];
                if ($appStatus === 'tidak diterima') {
                    $appStatus = 'ditolak';
                }

                $selection->application()->update(['status' => $appStatus]);
                $count++;
            }
        }

        cache()->forget($cacheKey);

        return redirect()->route('selections.index')->with('success', "Berhasil menerapkan update status pada {$count} data kelayakan.");
    }

    /**
     * Preview fuzzy logic selection results (without applying).
     */
    public function previewFuzzy(FuzzySelectionService $service)
    {
        $results = $service->runBatch();

        $layakCount = collect($results)->where('recommended_status', 'layak')->count();
        $tidakLayakCount = collect($results)->where('recommended_status', 'tidak layak')->count();

        return view('selections.fuzzy-preview', compact(
            'results',
            'layakCount',
            'tidakLayakCount',
        ));
    }

    /**
     * Apply fuzzy logic results to create/update Selection records.
     */
    public function applyFuzzy(Request $request, FuzzySelectionService $service)
    {
        $results = $service->runBatch();
        $applied = $service->applyResults($results);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Seleksi AI selesai. {$applied} data kelayakan berhasil diperbarui.",
                'applied' => $applied,
                'redirect' => route('selections.index'),
            ]);
        }

        return redirect()->route('selections.index')
            ->with('success', "Seleksi AI (Fuzzy Logic) selesai! {$applied} data kelayakan telah diperbarui.");
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'application_id' => 'required|integer|exists:applications,id',
            'stage' => 'required|string|max:255',
            'status' => 'required|string|in:verifikasi,wawancara,siap di proses,diterima,tidak diterima',
            'notes' => 'nullable|string',
            'date' => 'required|date',
        ];
    }
}
