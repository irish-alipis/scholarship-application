<?php

use App\Enums\Role;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Applicant\ApplicationController as ApplicantApplicationController;
use App\Http\Controllers\Applicant\DashboardController as ApplicantDashboardController;
use App\Http\Controllers\Applicant\NotificationController as ApplicantNotificationController;
use App\Http\Controllers\Applicant\ProfileController as ApplicantProfileController;
use App\Http\Controllers\Applicant\ScholarshipController as ApplicantScholarshipController;
use App\Http\Controllers\Applicant\SearchController as ApplicantSearchController;
use App\Http\Controllers\Evaluator\DashboardController as EvaluatorDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// After login, Breeze sends everyone here. We forward each role to its own dashboard.
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        Role::Admin => redirect()->route('admin.dashboard'),
        Role::Evaluator => redirect()->route('evaluator.dashboard'),
        Role::Applicant => redirect()->route('applicant.dashboard'),
    };
})->middleware(['auth', 'role:admin,evaluator,applicant'])->name('dashboard');

// Pages every signed-in role can open
Route::middleware(['auth', 'role:admin,evaluator,applicant'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin pages. The Admin owner adds user, scholarship, application, report and audit log routes here.
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
});

// Evaluator pages. The Evaluator owner adds review, evaluation and report routes here.
Route::middleware(['auth', 'role:evaluator'])->prefix('evaluator')->name('evaluator.')->group(function () {
    Route::get('/dashboard', EvaluatorDashboardController::class)->name('dashboard');
});

// Applicant pages. The Applicant owner adds scholarship, application and notification routes here.
Route::middleware(['auth', 'role:applicant'])->prefix('applicant')->name('applicant.')->group(function () {
    Route::get('/dashboard', ApplicantDashboardController::class)->name('dashboard');

    // Header search bar
    Route::get('/search', ApplicantSearchController::class)->name('search');

    // Account Management: student profile, account status, password
    Route::get('/profile', [ApplicantProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ApplicantProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ApplicantProfileController::class, 'updatePassword'])->name('profile.password');

    // Scholarship Browsing
    Route::get('/scholarships', [ApplicantScholarshipController::class, 'index'])->name('scholarships.index');
    Route::get('/scholarships/{scholarship}', [ApplicantScholarshipController::class, 'show'])->name('scholarships.show');

    // Scholarship Application and Application Monitoring
    Route::post('/scholarships/{scholarship}/apply', [ApplicantApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications', [ApplicantApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicantApplicationController::class, 'show'])->name('applications.show');
    Route::get('/applications/{application}/edit', [ApplicantApplicationController::class, 'edit'])->name('applications.edit');
    Route::put('/applications/{application}', [ApplicantApplicationController::class, 'update'])->name('applications.update');
    Route::post('/applications/{application}/submit', [ApplicantApplicationController::class, 'submit'])->name('applications.submit');
    Route::post('/applications/{application}/cancel', [ApplicantApplicationController::class, 'cancel'])->name('applications.cancel');
    Route::post('/applications/{application}/documents/{requiredDocument}', [ApplicantApplicationController::class, 'uploadDocument'])->name('applications.documents.store');
    Route::delete('/applications/{application}/documents/{document}', [ApplicantApplicationController::class, 'destroyDocument'])->name('applications.documents.destroy');
    Route::get('/documents/{document}', [ApplicantApplicationController::class, 'document'])->name('documents.show');

    // Notifications
    Route::get('/notifications', [ApplicantNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [ApplicantNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [ApplicantNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/{notification}/unread', [ApplicantNotificationController::class, 'markAsUnread'])->name('notifications.unread');
});

require __DIR__.'/auth.php';
