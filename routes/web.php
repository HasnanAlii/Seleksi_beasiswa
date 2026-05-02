<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationDocumentController;
use App\Http\Controllers\FuzzyCriteriaController;
use App\Http\Controllers\FuzzyMembershipController;
use App\Http\Controllers\InterviewAssessmentController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsMediaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\ScholarshipRequirementController;
use App\Http\Controllers\ScholarshipTypeController;
use App\Http\Controllers\SelectionController;
use App\Http\Controllers\StudentController;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Scholarship;
use App\Models\Student;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::view('/news', 'news.index')->name('news.index');
    Route::view('/scholarships', 'scholarships.index')->name('scholarships.index');
    Route::view('/students', 'students.index')->name('students.index');
    Route::view('/applications', 'applications.index')->name('applications.index');
    Route::view('/requirements', 'requirements.index')->name('requirements.index');
    Route::view('/scholarship-requirements', 'scholarship-requirements.index')->name('scholarship-requirements.index');
    Route::view('/selections', 'selections.index')->name('selections.index');
    Route::view('/announcements', 'announcements.index')->name('announcements.index');
    Route::view('/fuzzy-criteria', 'fuzzy-criteria.index')->name('fuzzy-criteria.index');
    Route::view('/fuzzy-memberships', 'fuzzy-memberships.index')->name('fuzzy-memberships.index');
    Route::view('/interviews', 'interviews.index')->name('interviews.index');
    Route::view('/interview-assessments', 'interview-assessments.index')->name('interview-assessments.index');
    Route::view('/news-media', 'news-media.index')->name('news-media.index');
    Route::view('/application-documents', 'application-documents.index')->name('application-documents.index');
});

Route::middleware('auth')->group(function () {
    // CRUD route manual (tanpa resource)
    // Announcements
    Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::get('announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::put('announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Scholarships
    Route::get('scholarships', [ScholarshipController::class, 'index'])->name('scholarships.index');
    Route::get('scholarships/create', [ScholarshipController::class, 'create'])->name('scholarships.create');
    Route::post('scholarships', [ScholarshipController::class, 'store'])->name('scholarships.store');
    Route::get('scholarships/{scholarship}/edit', [ScholarshipController::class, 'edit'])->name('scholarships.edit');
    Route::get('scholarships/{scholarship}', [ScholarshipController::class, 'show'])->name('scholarships.show');
    Route::put('scholarships/{scholarship}', [ScholarshipController::class, 'update'])->name('scholarships.update');
    Route::delete('scholarships/{scholarship}', [ScholarshipController::class, 'destroy'])->name('scholarships.destroy');

    // Scholarship Types
    Route::get('scholarship-types', [ScholarshipTypeController::class, 'index'])->name('scholarship-types.index');
    Route::get('scholarship-types/create', [ScholarshipTypeController::class, 'create'])->name('scholarship-types.create');
    Route::post('scholarship-types', [ScholarshipTypeController::class, 'store'])->name('scholarship-types.store');
    Route::get('scholarship-types/{scholarshipType}/edit', [ScholarshipTypeController::class, 'edit'])->name('scholarship-types.edit');
    Route::get('scholarship-types/{scholarshipType}', [ScholarshipTypeController::class, 'show'])->name('scholarship-types.show');
    Route::put('scholarship-types/{scholarshipType}', [ScholarshipTypeController::class, 'update'])->name('scholarship-types.update');
    Route::delete('scholarship-types/{scholarshipType}', [ScholarshipTypeController::class, 'destroy'])->name('scholarship-types.destroy');

    // Applications
    Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('applications', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('applications/{application}/edit', [ApplicationController::class, 'edit'])->name('applications.edit');
    Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::put('applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::delete('applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

    // Students
    Route::get('students', [StudentController::class, 'index'])->name('students.index');
    Route::post('students', [StudentController::class, 'store']);
    Route::get('students/{student}', [StudentController::class, 'show']);
    Route::put('students/{student}', [StudentController::class, 'update']);
    Route::delete('students/{student}', [StudentController::class, 'destroy']);

    // Requirements
    Route::get('requirements', [RequirementController::class, 'index'])->name('requirements.index');
    Route::get('requirements/create', [RequirementController::class, 'create'])->name('requirements.create');
    Route::post('requirements', [RequirementController::class, 'store'])->name('requirements.store');
    Route::get('requirements/{requirement}/edit', [RequirementController::class, 'edit'])->name('requirements.edit');
    Route::get('requirements/{requirement}', [RequirementController::class, 'show'])->name('requirements.show');
    Route::put('requirements/{requirement}', [RequirementController::class, 'update'])->name('requirements.update');
    Route::delete('requirements/{requirement}', [RequirementController::class, 'destroy'])->name('requirements.destroy');

    // Selections
    Route::get('selections', [SelectionController::class, 'index'])->name('selections.index');
    Route::get('selections/create', [SelectionController::class, 'create'])->name('selections.create');
    Route::post('selections', [SelectionController::class, 'store'])->name('selections.store');
    Route::get('selections/{selection}/edit', [SelectionController::class, 'edit'])->name('selections.edit');
    Route::get('selections/{selection}', [SelectionController::class, 'show'])->name('selections.show');
    Route::put('selections/{selection}', [SelectionController::class, 'update'])->name('selections.update');
    Route::delete('selections/{selection}', [SelectionController::class, 'destroy'])->name('selections.destroy');

    // Fuzzy Criteria
    Route::get('fuzzy-criteria', [FuzzyCriteriaController::class, 'index'])->name('fuzzy-criteria.index');
    Route::get('fuzzy-criteria/create', [FuzzyCriteriaController::class, 'create'])->name('fuzzy-criteria.create');
    Route::post('fuzzy-criteria', [FuzzyCriteriaController::class, 'store'])->name('fuzzy-criteria.store');
    Route::get('fuzzy-criteria/{fuzzy_criterium}/edit', [FuzzyCriteriaController::class, 'edit'])->name('fuzzy-criteria.edit');
    Route::get('fuzzy-criteria/{fuzzy_criterium}', [FuzzyCriteriaController::class, 'show'])->name('fuzzy-criteria.show');
    Route::put('fuzzy-criteria/{fuzzy_criterium}', [FuzzyCriteriaController::class, 'update'])->name('fuzzy-criteria.update');
    Route::delete('fuzzy-criteria/{fuzzy_criterium}', [FuzzyCriteriaController::class, 'destroy'])->name('fuzzy-criteria.destroy');

    // Fuzzy Memberships
    Route::get('fuzzy-memberships', [FuzzyMembershipController::class, 'index'])->name('fuzzy-memberships.index');
    Route::get('fuzzy-memberships/create', [FuzzyMembershipController::class, 'create'])->name('fuzzy-memberships.create');
    Route::post('fuzzy-memberships', [FuzzyMembershipController::class, 'store'])->name('fuzzy-memberships.store');
    Route::get('fuzzy-memberships/{fuzzyMembership}/edit', [FuzzyMembershipController::class, 'edit'])->name('fuzzy-memberships.edit');
    Route::get('fuzzy-memberships/{fuzzyMembership}', [FuzzyMembershipController::class, 'show'])->name('fuzzy-memberships.show');
    Route::put('fuzzy-memberships/{fuzzyMembership}', [FuzzyMembershipController::class, 'update'])->name('fuzzy-memberships.update');
    Route::delete('fuzzy-memberships/{fuzzyMembership}', [FuzzyMembershipController::class, 'destroy'])->name('fuzzy-memberships.destroy');

    // Interviews
    Route::get('interviews', [InterviewController::class, 'index'])->name('interviews.index');
    Route::get('interviews/create', [InterviewController::class, 'create'])->name('interviews.create');
    Route::post('interviews', [InterviewController::class, 'store'])->name('interviews.store');
    Route::get('interviews/{interview}/edit', [InterviewController::class, 'edit'])->name('interviews.edit');
    Route::get('interviews/{interview}', [InterviewController::class, 'show'])->name('interviews.show');
    Route::put('interviews/{interview}', [InterviewController::class, 'update'])->name('interviews.update');
    Route::delete('interviews/{interview}', [InterviewController::class, 'destroy'])->name('interviews.destroy');

    // Interview Assessments
    Route::get('interview-assessments', [InterviewAssessmentController::class, 'index'])->name('interview-assessments.index');
    Route::get('interview-assessments/create', [InterviewAssessmentController::class, 'create'])->name('interview-assessments.create');
    Route::post('interview-assessments', [InterviewAssessmentController::class, 'store'])->name('interview-assessments.store');
    Route::get('interview-assessments/{interviewAssessment}/edit', [InterviewAssessmentController::class, 'edit'])->name('interview-assessments.edit');
    Route::get('interview-assessments/{interviewAssessment}', [InterviewAssessmentController::class, 'show'])->name('interview-assessments.show');
    Route::put('interview-assessments/{interviewAssessment}', [InterviewAssessmentController::class, 'update'])->name('interview-assessments.update');
    Route::delete('interview-assessments/{interviewAssessment}', [InterviewAssessmentController::class, 'destroy'])->name('interview-assessments.destroy');

    // News
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('news', [NewsController::class, 'store'])->name('news.store');
    Route::get('news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');
    Route::put('news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

    // News Media
    Route::get('news-media', [NewsMediaController::class, 'index'])->name('news-media.index');
    Route::post('news-media', [NewsMediaController::class, 'store']);
    Route::get('news-media/{news_media}', [NewsMediaController::class, 'show']);
    Route::put('news-media/{news_media}', [NewsMediaController::class, 'update']);
    Route::delete('news-media/{news_media}', [NewsMediaController::class, 'destroy']);

    // Application Documents
    Route::get('application-documents', [ApplicationDocumentController::class, 'index'])->name('application-documents.index');
    Route::post('application-documents', [ApplicationDocumentController::class, 'store']);
    Route::get('application-documents/{application_document}', [ApplicationDocumentController::class, 'show']);
    Route::put('application-documents/{application_document}', [ApplicationDocumentController::class, 'update']);
    Route::delete('application-documents/{application_document}', [ApplicationDocumentController::class, 'destroy']);

    // Scholarship Requirements
    Route::get('scholarship-requirements', [ScholarshipRequirementController::class, 'index'])->name('scholarship-requirements.index');
    Route::post('scholarship-requirements', [ScholarshipRequirementController::class, 'store']);
    Route::get('scholarship-requirements/{scholarship_requirement}', [ScholarshipRequirementController::class, 'show']);
    Route::put('scholarship-requirements/{scholarship_requirement}', [ScholarshipRequirementController::class, 'update']);
    Route::delete('scholarship-requirements/{scholarship_requirement}', [ScholarshipRequirementController::class, 'destroy']);

});

require __DIR__.'/auth.php';
