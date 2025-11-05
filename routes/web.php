<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\EventPageController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VolunteerController;

// ===============================
// FRONTEND ROUTES
// ===============================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages
Route::view('/about', 'pages.about')->name('about');

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

    Route::middleware(['auth'])->group(function () {
    
    // Volunteer Routes
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
        
        Route::get('/messages', [VolunteerController::class, 'messages'])->name('messages');
    });

    // Organization Dashboard (placeholder for now)
    Route::get('/organization/dashboard', function () {
        return view('organization.dashboard');
    })->name('organization.dashboard');

    // Admin Dashboard (placeholder for now)
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

    

// ===============================
// EVENTS PAGES
// ===============================
Route::get('/events', [EventPageController::class, 'index'])->name('events');
Route::get('/events/{id}', [EventPageController::class, 'show'])->name('events.show');

// ===============================
// PROTECTED ROUTES (Require Authentication)
// ===============================
Route::middleware(['auth'])->group(function () {

    // Organization Dashboard (placeholder for now)
    Route::get('/organization/dashboard', function () {
        return view('organization.dashboard');
    })->name('organization.dashboard');

    // Admin Dashboard (placeholder for now)
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});