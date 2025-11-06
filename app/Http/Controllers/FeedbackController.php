<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\EventRegistration;
use App\Models\Volunteer;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index() {
        $user = Auth::user();

        // Filter based on user type
        if ($user->isVolunteer()) {
            $volunteer = Volunteer::where('user_id', $user->user_id)->first();
            if (!$volunteer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Volunteer profile not found'
                ], 404);
            }
            $feedbacks = Feedback::with('registration.event')
                ->whereHas('registration', function($query) use ($volunteer) {
                    $query->where('volunteer_id', $volunteer->volunteer_id);
                })
                ->get();
        } elseif ($user->isOrganization()) {
            $organization = Organization::where('user_id', $user->user_id)->first();
            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Organization profile not found'
                ], 404);
            }
            $feedbacks = Feedback::with('registration.event', 'registration.volunteer')
                ->whereHas('registration.event', function($query) use ($organization) {
                    $query->where('organization_id', $organization->organization_id);
                })
                ->get();
        } elseif ($user->isAdmin()) {
            $feedbacks = Feedback::with('registration')->get();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user type'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $feedbacks
        ]);
    }

    public function store(Request $request) {
        // Only volunteers can leave feedback
        if (!Auth::user()->isVolunteer()) {
            return response()->json([
                'success' => false,
                'message' => 'Only volunteers can submit feedback'
            ], 403);
        }

        $validated = $request->validate([
            'registration_id' => 'required|exists:event_registrations,registration_id',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
        ]);

        $volunteer = Volunteer::where('user_id', Auth::id())->first();
        if (!$volunteer) {
            return response()->json([
                'success' => false,
                'message' => 'Volunteer profile not found'
            ], 404);
        }

        // Verify the registration belongs to the volunteer
        $registration = EventRegistration::findOrFail($validated['registration_id']);
        if ($registration->volunteer_id !== $volunteer->volunteer_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only provide feedback for your own registrations'
            ], 403);
        }

        // Check if feedback already exists
        $existing = Feedback::where('registration_id', $validated['registration_id'])->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback already submitted for this event'
            ], 400);
        }

        $feedback = Feedback::create(array_merge($validated, [
            'submitted_at' => now()
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted successfully',
            'data' => $feedback->load('registration')
        ], 201);
    }

    public function show($id) {
        $feedback = Feedback::with('registration.event', 'registration.volunteer')->findOrFail($id);
        $user = Auth::user();

        // Verify access
        $volunteer = $user->isVolunteer() ? Volunteer::where('user_id', $user->user_id)->first() : null;
        $organization = $user->isOrganization() ? Organization::where('user_id', $user->user_id)->first() : null;

        $canView = $user->isAdmin() ||
                   ($volunteer && $feedback->registration->volunteer_id === $volunteer->volunteer_id) ||
                   ($organization && $feedback->registration->event->organization_id === $organization->organization_id);

        if (!$canView) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $feedback
        ]);
    }

    public function update(Request $request, $id) {
        $feedback = Feedback::with('registration')->findOrFail($id);

        // Only the volunteer who created it can update
        $volunteer = Volunteer::where('user_id', Auth::id())->first();
        if (!$volunteer || $feedback->registration->volunteer_id !== $volunteer->volunteer_id) {
            if (!Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        }

        $validated = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
        ]);

        $feedback->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Feedback updated successfully',
            'data' => $feedback
        ]);
    }

    public function destroy($id) {
        $feedback = Feedback::with('registration')->findOrFail($id);

        // Only the volunteer who created it or admin can delete
        $volunteer = Volunteer::where('user_id', Auth::id())->first();
        $canDelete = Auth::user()->isAdmin() ||
                     ($volunteer && $feedback->registration->volunteer_id === $volunteer->volunteer_id);

        if (!$canDelete) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $feedback->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Feedback deleted successfully'
        ], 204);
    }
}
