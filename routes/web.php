<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\FuzzyCriteriaController;
use App\Http\Controllers\FuzzyMembershipController;
use App\Http\Controllers\InterviewAssessmentController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\ScholarshipTypeController;
use App\Http\Controllers\SelectionController;
use App\Http\Controllers\StudentController;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Scholarship;
use App\Models\Student;
use App\Models\News;
use Illuminate\Support\Facades\Route;

// --- Rute Halaman Utama ---
Route::get('/', function () {
    $latestNews = News::latest()->take(3)->get();
    return view('welcome', compact('latestNews'));
});

// --- Rute Dashboard & Statistik (Auth Only) ---
Route::get('/dashboard', function () {
    $stats = [
        'total_scholarships' => Scholarship::count(),
        'total_students' => Student::count(),
        'total_applications' => Application::count(),
        'total_interviews' => Interview::count(),
        'pending' => Application::where('status', 'menunggu')->count(),
        'processed' => Application::where('status', 'diproses')->count(),
        'accepted' => Application::where('status', 'diterima')->count(),
        'rejected' => Application::where('status', 'ditolak')->count(),
        'unassessed_interviews' => Interview::doesntHave('assessments')->count(),
    ];

    $recentApplications = Application::with(['student', 'scholarship'])
        ->latest()
        ->limit(5)
        ->get();

    $pendingInterviews = Interview::with(['application.student', 'application.scholarship'])
        ->doesntHave('assessments')
        ->latest()
        ->limit(5)
        ->get();

    return view('dashboard', compact('stats', 'recentApplications', 'pendingInterviews'));
})->middleware(['auth', 'verified'])->name('dashboard');

// --- Rute Manajemen Profil User ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 1. AKSES UMUM (Pengumuman & Berita)
Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');

Route::get('berita', [NewsController::class, 'publicIndex'])->name('news.public_index');
Route::get('berita/{news}', [NewsController::class, 'read'])->name('news.read');

Route::middleware('auth')->group(function () {
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');

    // 2. AKSES STAF & ADMIN (Manajemen Konten & Data Master)
    Route::middleware('role:admin|staf')->group(function () {

        // --- Manajemen Pengumuman ---
        Route::get('announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // --- Manajemen Beasiswa ---
        Route::get('scholarships', [ScholarshipController::class, 'index'])->name('scholarships.index');
        Route::get('scholarships/create', [ScholarshipController::class, 'create'])->name('scholarships.create');
        Route::post('scholarships', [ScholarshipController::class, 'store'])->name('scholarships.store');
        Route::get('scholarships/{scholarship}/edit', [ScholarshipController::class, 'edit'])->name('scholarships.edit');
        Route::get('scholarships/{scholarship}', [ScholarshipController::class, 'show'])->name('scholarships.show');
        Route::put('scholarships/{scholarship}', [ScholarshipController::class, 'update'])->name('scholarships.update');
        Route::delete('scholarships/{scholarship}', [ScholarshipController::class, 'destroy'])->name('scholarships.destroy');

        // --- Manajemen Tipe Beasiswa ---
        Route::get('scholarship-types', [ScholarshipTypeController::class, 'index'])->name('scholarship-types.index');
        Route::get('scholarship-types/create', [ScholarshipTypeController::class, 'create'])->name('scholarship-types.create');
        Route::post('scholarship-types', [ScholarshipTypeController::class, 'store'])->name('scholarship-types.store');
        Route::get('scholarship-types/{scholarshipType}/edit', [ScholarshipTypeController::class, 'edit'])->name('scholarship-types.edit');
        Route::get('scholarship-types/{scholarshipType}', [ScholarshipTypeController::class, 'show'])->name('scholarship-types.show');
        Route::put('scholarship-types/{scholarshipType}', [ScholarshipTypeController::class, 'update'])->name('scholarship-types.update');
        Route::delete('scholarship-types/{scholarshipType}', [ScholarshipTypeController::class, 'destroy'])->name('scholarship-types.destroy');

        // --- Manajemen Persyaratan ---
        Route::get('requirements', [RequirementController::class, 'index'])->name('requirements.index');
        Route::get('requirements/create', [RequirementController::class, 'create'])->name('requirements.create');
        Route::post('requirements', [RequirementController::class, 'store'])->name('requirements.store');
        Route::get('requirements/{requirement}/edit', [RequirementController::class, 'edit'])->name('requirements.edit');
        Route::get('requirements/{requirement}', [RequirementController::class, 'show'])->name('requirements.show');
        Route::put('requirements/{requirement}', [RequirementController::class, 'update'])->name('requirements.update');
        Route::delete('requirements/{requirement}', [RequirementController::class, 'destroy'])->name('requirements.destroy');

        // --- Manajemen Data Mahasiswa ---
        Route::get('students', [StudentController::class, 'index'])->name('students.index');
        Route::post('students', [StudentController::class, 'store']);
        Route::get('students/{student}', [StudentController::class, 'show']);
        Route::put('students/{student}', [StudentController::class, 'update']);
        Route::delete('students/{student}', [StudentController::class, 'destroy']);

        // --- Manajemen Berita ---
        Route::get('news/create', [NewsController::class, 'create'])->name('news.create');
        Route::post('news', [NewsController::class, 'store'])->name('news.store');
        Route::get('news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::put('news/{news}', [NewsController::class, 'update'])->name('news.update');
        Route::delete('news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

        // --- Manajemen Wawancara (Aksi) ---
        Route::get('interviews/create', [InterviewController::class, 'create'])->name('interviews.create');
        Route::post('interviews', [InterviewController::class, 'store'])->name('interviews.store');
        Route::get('interviews/{interview}/edit', [InterviewController::class, 'edit'])->name('interviews.edit');
        Route::get('interviews/{interview}', [InterviewController::class, 'show'])->name('interviews.show');
        Route::put('interviews/{interview}', [InterviewController::class, 'update'])->name('interviews.update');
        Route::delete('interviews/{interview}', [InterviewController::class, 'destroy'])->name('interviews.destroy');

        // --- Manajemen Kriteria Fuzzy ---
        Route::get('fuzzy-criteria', [FuzzyCriteriaController::class, 'index'])->name('fuzzy-criteria.index');
        Route::get('fuzzy-criteria/create', [FuzzyCriteriaController::class, 'create'])->name('fuzzy-criteria.create');
        Route::post('fuzzy-criteria', [FuzzyCriteriaController::class, 'store'])->name('fuzzy-criteria.store');
        Route::get('fuzzy-criteria/{fuzzy_criterium}/edit', [FuzzyCriteriaController::class, 'edit'])->name('fuzzy-criteria.edit');
        Route::get('fuzzy-criteria/{fuzzy_criterium}', [FuzzyCriteriaController::class, 'show'])->name('fuzzy-criteria.show');
        Route::put('fuzzy-criteria/{fuzzy_criterium}', [FuzzyCriteriaController::class, 'update'])->name('fuzzy-criteria.update');
        Route::delete('fuzzy-criteria/{fuzzy_criterium}', [FuzzyCriteriaController::class, 'destroy'])->name('fuzzy-criteria.destroy');

        // --- Manajemen Keanggotaan Fuzzy ---
        Route::get('fuzzy-memberships', [FuzzyMembershipController::class, 'index'])->name('fuzzy-memberships.index');
        Route::get('fuzzy-memberships/create', [FuzzyMembershipController::class, 'create'])->name('fuzzy-memberships.create');
        Route::post('fuzzy-memberships', [FuzzyMembershipController::class, 'store'])->name('fuzzy-memberships.store');
        Route::get('fuzzy-memberships/{fuzzyMembership}/edit', [FuzzyMembershipController::class, 'edit'])->name('fuzzy-memberships.edit');
        Route::get('fuzzy-memberships/{fuzzyMembership}', [FuzzyMembershipController::class, 'show'])->name('fuzzy-memberships.show');
        Route::put('fuzzy-memberships/{fuzzyMembership}', [FuzzyMembershipController::class, 'update'])->name('fuzzy-memberships.update');
        Route::delete('fuzzy-memberships/{fuzzyMembership}', [FuzzyMembershipController::class, 'destroy'])->name('fuzzy-memberships.destroy');

        // --- Manajemen Seleksi (Aksi) ---
        Route::get('selections/create', [SelectionController::class, 'create'])->name('selections.create');
        Route::post('selections', [SelectionController::class, 'store'])->name('selections.store');
        Route::get('selections/{selection}/edit', [SelectionController::class, 'edit'])->name('selections.edit');
        Route::put('selections/{selection}', [SelectionController::class, 'update'])->name('selections.update');
        Route::delete('selections/{selection}', [SelectionController::class, 'destroy'])->name('selections.destroy');

        // --- Manajemen Pengajuan/Pendaftaran ---
        Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/create', [ApplicationController::class, 'create'])->name('applications.create');
        Route::post('applications', [ApplicationController::class, 'store'])->name('applications.store');
        Route::get('applications/{application}/edit', [ApplicationController::class, 'edit'])->name('applications.edit');
        Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::put('applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');
        Route::delete('applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    });

    // 3. AKSES KHUSUS (Wawancara & Penilaian - Kaprodi & Staf)
    Route::middleware('role:admin|kaprodi|staf')->group(function () {
        Route::get('scholarships', [ScholarshipController::class, 'index'])->name('scholarships.index');
        Route::get('scholarships/{scholarship}', [ScholarshipController::class, 'show'])->name('scholarships.show');

        Route::get('interviews', [InterviewController::class, 'index'])->name('interviews.index');

        Route::get('interview-assessments', [InterviewAssessmentController::class, 'index'])->name('interview-assessments.index');
        Route::get('interview-assessments/create', [InterviewAssessmentController::class, 'create'])->name('interview-assessments.create');
        Route::post('interview-assessments', [InterviewAssessmentController::class, 'store'])->name('interview-assessments.store');
        Route::get('interview-assessments/{interviewAssessment}/edit', [InterviewAssessmentController::class, 'edit'])->name('interview-assessments.edit');
        Route::get('interview-assessments/{interviewAssessment}', [InterviewAssessmentController::class, 'show'])->name('interview-assessments.show');
        Route::put('interview-assessments/{interviewAssessment}', [InterviewAssessmentController::class, 'update'])->name('interview-assessments.update');
        Route::delete('interview-assessments/{interviewAssessment}', [InterviewAssessmentController::class, 'destroy'])->name('interview-assessments.destroy');
    });

    // 4. AKSES KHUSUS (Hasil Seleksi - Kaprodi, WD3, & Staf)
    Route::middleware('role:admin|kaprodi|wakil dekan 3|staf|mahasiswa')->group(function () {
        // beasiswa
        Route::get('scholarships', [ScholarshipController::class, 'index'])->name('scholarships.index');
        Route::get('scholarships/{scholarship}', [ScholarshipController::class, 'show'])->name('scholarships.show');

        Route::get('selections', [SelectionController::class, 'index'])->name('selections.index');
        Route::get('selections/{selection}', [SelectionController::class, 'show'])->name('selections.show');
    });

    // 5. AKSES PENDAFTARAN (Mahasiswa)
    Route::middleware('role:admin|mahasiswa')->group(function () {
        // pendaftaran
        Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::get('applications/create', [ApplicationController::class, 'create'])->name('applications.create');
        Route::post('applications', [ApplicationController::class, 'store'])->name('applications.store');

        // wawancara
        Route::get('interviews/{interview}', [InterviewController::class, 'show'])->name('interviews.show');

        // status pendaftaran
        //     Route::get('application-statuses', [ApplicationStatusController::class, 'index'])->name('application-statuses.index');
        //     Route::get('application-statuses/{applicationStatus}', [ApplicationStatusController::class, 'show'])->name('application-statuses.show');
    });
});

require __DIR__.'/auth.php';
