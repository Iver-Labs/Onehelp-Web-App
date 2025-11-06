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
        
        // Event routes (placeholder for now)
        Route::get('/events/create', function() {
            return view('organization.create-event');
        })->name('events.create');
    });

    // ===============================
    // ADMIN ROUTES (Placeholder)
    // ===============================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });
});