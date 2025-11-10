<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\EventPageController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\MessagesController;

// ===============================
// FRONTEND ROUTES
// ===============================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages
Route::view('/about', 'pages.about')->name('about');

// Events Pages
Route::get('/events', [EventPageController::class, 'index'])->name('events');
Route::get('/events/{id}', [EventPageController::class, 'show'])->name('events.show');

// ===============================
// AUTHENTICATION ROUTES
// ===============================

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit')
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
    ->name('register')
    ->middleware('guest');

Route::post('/register/volunteer', [RegisterController::class, 'registerVolunteer'])
    ->name('register.volunteer')
    ->middleware('guest');

Route::post('/register/organization', [RegisterController::class, 'registerOrganization'])
    ->name('register.organization')
    ->middleware('guest');

// ===============================
// PROTECTED ROUTES (Require Authentication)
// ===============================
Route::middleware(['auth'])->group(function () {
    
    // ===============================
    // VOLUNTEER ROUTES
    // ===============================
    Route::prefix('volunteer')->name('volunteer.')->group(function () {
        Route::get('/dashboard', [VolunteerController::class, 'dashboard'])->name('dashboard');
        Route::get('/events', [VolunteerController::class, 'events'])->name('events');
        
        // Profile routes
        Route::get('/profile', [VolunteerController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [VolunteerController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/info', [VolunteerController::class, 'updateInfo'])->name('info.update');
        
        // Account routes
        Route::get('/account', [VolunteerController::class, 'account'])->name('account');
        Route::put('/account/password', [VolunteerController::class, 'updatePassword'])->name('password.update');
        Route::delete('/account/deactivate', [VolunteerController::class, 'deactivateAccount'])->name('account.deactivate');
        
        // Messages
        Route::get('/messages', [VolunteerController::class, 'messages'])->name('messages');
        Route::post('/messages/send', [VolunteerController::class, 'sendMessage'])->name('messages.send');
    });

    // ===============================
    // ORGANIZATION ROUTES
    // ===============================
    Route::prefix('organization')->name('organization.')->group(function () {
        Route::get('/dashboard', [OrganizationController::class, 'dashboard'])->name('dashboard');
        Route::get('/applications', [OrganizationController::class, 'applications'])->name('applications');
        Route::put('/applications/{id}', [OrganizationController::class, 'updateApplicationStatus'])->name('applications.update');
        Route::get('/analytics', [OrganizationController::class, 'analytics'])->name('analytics');
        Route::get('/messages', [OrganizationController::class, 'messages'])->name('messages');
        Route::post('/messages/send', [OrganizationController::class, 'sendMessage'])->name('messages.send');
        
        // Event routes
        Route::get('/events/create', [OrganizationController::class, 'createEvent'])->name('events.create');
        Route::post('/events/store', [OrganizationController::class, 'storeEvent'])->name('events.store');
    });

    // ===============================
    // ADMIN ROUTES
    // ===============================
    Route::prefix('admin')->name('admin.')->middleware('can:admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
        Route::get('/organizations', [\App\Http\Controllers\AdminController::class, 'organizations'])->name('organizations');
        Route::get('/events', [\App\Http\Controllers\AdminController::class, 'events'])->name('events');
        Route::get('/verifications', [\App\Http\Controllers\AdminController::class, 'verifications'])->name('verifications');
        Route::put('/verifications/{id}', [\App\Http\Controllers\AdminController::class, 'updateVerification'])->name('verifications.update');
        Route::post('/users/{id}/toggle-status', [\App\Http\Controllers\AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
        Route::delete('/users/{id}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('/analytics', [\App\Http\Controllers\AdminController::class, 'analytics'])->name('analytics');
    });

    // ===============================
    // REPORT ROUTES
    // ===============================
    Route::prefix('reports')->name('reports.')->middleware('auth')->group(function () {
        // PDF Reports
        Route::get('/volunteer/{volunteerId}/activity', [\App\Http\Controllers\ReportController::class, 'volunteerActivityReport'])->name('volunteer.activity');
        Route::get('/event/{eventId}/participation', [\App\Http\Controllers\ReportController::class, 'eventParticipationReport'])->name('event.participation');
        Route::get('/organization/{organizationId}/summary', [\App\Http\Controllers\ReportController::class, 'organizationSummaryReport'])->name('organization.summary');
        Route::get('/system/summary', [\App\Http\Controllers\ReportController::class, 'systemSummaryReport'])->name('system.summary')->middleware('can:admin');
        
        // Excel Exports
        Route::get('/export/users', [\App\Http\Controllers\ReportController::class, 'exportUsers'])->name('export.users')->middleware('can:admin');
        Route::get('/export/events', [\App\Http\Controllers\ReportController::class, 'exportEvents'])->name('export.events');
        Route::get('/export/registrations', [\App\Http\Controllers\ReportController::class, 'exportRegistrations'])->name('export.registrations');
        
        // Certificate
        Route::get('/certificate/{registrationId}', [\App\Http\Controllers\ReportController::class, 'volunteerCertificate'])->name('certificate');
    });
});