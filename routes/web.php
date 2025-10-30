<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\EventPageController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\RegisterController;

// ===============================
// FRONTEND ROUTES
// ===============================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/login', 'pages.login')->name('login');

// ===============================
// EVENTS PAGES
// ===============================
Route::get('/events', [EventPageController::class, 'index'])->name('events');
Route::get('/events/{id}', [EventPageController::class, 'show'])->name('events.show');

// ===============================
// REGISTRATION
// ===============================
// Show registration form
Route::view('/register', 'pages.register')->name('register');

// Handle registration form submission
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
