<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,
    VolunteerController,
    OrganizationController,
    EventController,
    EventRegistrationController,
    SkillController,
    AttendanceController,
    NotificationController,
    FeedbackController,
    OrganizationVerificationController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes here are automatically prefixed with "/api"
| Example: http://127.0.0.1:8000/api/users
|--------------------------------------------------------------------------
*/

// Public API routes (no auth required)
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::get('/skills', [SkillController::class, 'index']);
Route::get('/skills/{id}', [SkillController::class, 'show']);

// Protected API routes (require authentication)
Route::middleware(['api.auth'])->group(function () {
    // User routes
    Route::apiResource('users', UserController::class);
    Route::apiResource('volunteers', VolunteerController::class);
    Route::apiResource('organizations', OrganizationController::class);
    
    // Event routes (except public ones already defined above)
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::patch('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    
    // Event registration routes
    Route::apiResource('registrations', EventRegistrationController::class);
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'store']);
    
    // Skill management routes (modify/create)
    Route::post('/skills', [SkillController::class, 'store']);
    Route::put('/skills/{id}', [SkillController::class, 'update']);
    Route::patch('/skills/{id}', [SkillController::class, 'update']);
    Route::delete('/skills/{id}', [SkillController::class, 'destroy']);
    
    // Other protected resources
    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('notifications', NotificationController::class);
    Route::apiResource('feedbacks', FeedbackController::class);
    Route::apiResource('verifications', OrganizationVerificationController::class);
    
    // Optional custom routes
    Route::get('/organizations/{organization}/events', [OrganizationController::class, 'show']);
    Route::get('/volunteers/{volunteer}/registrations', [VolunteerController::class, 'show']);
});
