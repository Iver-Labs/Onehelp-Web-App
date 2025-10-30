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

Route::apiResource('users', UserController::class);
Route::apiResource('volunteers', VolunteerController::class);
Route::apiResource('organizations', OrganizationController::class);
Route::apiResource('events', EventController::class);
Route::apiResource('registrations', EventRegistrationController::class);
Route::apiResource('skills', SkillController::class);
Route::apiResource('attendances', AttendanceController::class);
Route::apiResource('notifications', NotificationController::class);
Route::apiResource('feedbacks', FeedbackController::class);
Route::apiResource('verifications', OrganizationVerificationController::class);

// Optional custom routes
Route::post('/events/{event}/register', [EventRegistrationController::class, 'store']);
Route::get('/organizations/{organization}/events', [OrganizationController::class, 'show']);
Route::get('/volunteers/{volunteer}/registrations', [VolunteerController::class, 'show']);
