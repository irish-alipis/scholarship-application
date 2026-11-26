<?php

use App\Enums\Role;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EvaluatorRequestController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ScholarshipController;
use App\Http\Controllers\Admin\ScholarshipDocumentController;
use App\Http\Controllers\Admin\ScholarshipRequirementController;
use App\Http\Controllers\Admin\UserController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin pages. The Admin owner adds user, scholarship, application, report and audit log routes here.
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

    Route::resource('/users', UserController::class)->except(['show', 'destroy']);
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');

    // evaluator requests
    Route::patch('/evaluator-requests/{user}/approve', [EvaluatorRequestController::class, 'approve'])->name('evaluator-requests.approve');
    Route::patch('/evaluator-requests/{user}/reject', [EvaluatorRequestController::class, 'reject'])->name('evaluator-requests.reject');

    // scholarship management
    Route::resource('scholarships', ScholarshipController::class)->except(['destroy']);
    Route::patch('/scholarships/{scholarship}/status', [ScholarshipController::class, 'updateStatus'])->name('scholarships.status');
    Route::post('/scholarships/{scholarship}/requirements', [ScholarshipRequirementController::class, 'store'])->name('scholarships.requirements.store');
    Route::delete('/scholarships/{scholarship}/requirements/{requirement}', [ScholarshipRequirementController::class, 'destroy'])->name('scholarships.requirements.destroy');
    Route::post('/scholarships/{scholarship}/documents', [ScholarshipDocumentController::class, 'store'])->name('scholarships.documents.store');
    Route::delete('/scholarships/{scholarship}/documents/{documents}', [ScholarshipDocumentController::class, 'destroy'])->name('scholarships.documents.destroy');

    // application management
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{application}/assign', [ApplicationController::class, 'assign'])->name('applications.assign');

    // reports management
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // audit logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});

// role dashboards 
// TODO remove this afterwards 
Route::middleware(['auth', 'role:applicant'])->prefix('applicant')->name('applicant.')->group(function () {
    Route::get('/dashboard', fn () => view('applicant.dashboard'))->name('dashboard');
});

Route::middleware(['auth', 'role:evaluator'])->prefix('evaluator')->name('evaluator.')->group(function () {
    Route::get('/dashboard', fn () => view('evaluator.dashboard'))->name('dashboard');
});

require __DIR__.'/auth.php';
