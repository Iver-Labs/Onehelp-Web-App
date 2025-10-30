<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\EventPageController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

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

// ===============================
// EVENTS PAGES
// ===============================
Route::get('/events', [EventPageController::class, 'index'])->name('events');
Route::get('/events/{id}', [EventPageController::class, 'show'])->name('events.show');

// ===============================
// PROTECTED ROUTES (Require Authentication)
// ===============================
Route::middleware(['auth'])->group(function () {
    // Volunteer Dashboard (placeholder for now)
    Route::get('/volunteer/dashboard', function () {
        return view('volunteer.dashboard');
    })->name('volunteer.dashboard');

    // Organization Dashboard (placeholder for now)
    Route::get('/organization/dashboard', function () {
        return view('organization.dashboard');
    })->name('organization.dashboard');

    // Admin Dashboard (placeholder for now)
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});