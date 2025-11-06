<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Models\Event;
use App\Models\Volunteer;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventRegistrationController extends Controller
{

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
            $registrations = EventRegistration::with(['event', 'volunteer'])
                ->where('volunteer_id', $volunteer->volunteer_id)
                ->get();
        } elseif ($user->isOrganization()) {
            $organization = Organization::where('user_id', $user->user_id)->first();
            if (!$organization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Organization profile not found'
                ], 404);
            }
            $registrations = EventRegistration::with(['event', 'volunteer'])
                ->whereHas('event', function($query) use ($organization) {
                    $query->where('organization_id', $organization->organization_id);
                })
                ->get();
        } elseif ($user->isAdmin()) {
            $registrations = EventRegistration::with(['event', 'volunteer'])->get();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user type'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $registrations
        ]);
    }

    public function store(Request $request) {
        $user = Auth::user();

        // Only volunteers can register for events
        if (!$user->isVolunteer()) {
            return response()->json([
                'success' => false,
                'message' => 'Only volunteers can register for events'
            ], 403);
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:events,event_id',
            'motivation' => 'nullable|string|max:1000',
        ]);

        $volunteer = Volunteer::where('user_id', $user->user_id)->first();
        if (!$volunteer) {
            return response()->json([
                'success' => false,
                'message' => 'Volunteer profile not found'
            ], 404);
        }

        // Check if event exists and is open
        $event = Event::findOrFail($validated['event_id']);
        if ($event->status !== 'open') {
            return response()->json([
                'success' => false,
                'message' => 'This event is not accepting registrations'
            ], 400);
        }

        // Check if already registered
        $existing = EventRegistration::where('volunteer_id', $volunteer->volunteer_id)
            ->where('event_id', $validated['event_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You are already registered for this event'
            ], 400);
        }

        // Check if event is full
        $currentRegistrations = EventRegistration::where('event_id', $validated['event_id'])
            ->where('status', 'approved')
            ->count();

        if ($currentRegistrations >= $event->max_volunteers) {
            return response()->json([
                'success' => false,
                'message' => 'This event is full'
            ], 400);
        }

        $registration = EventRegistration::create([
            'event_id' => $validated['event_id'],
            'volunteer_id' => $volunteer->volunteer_id,
            'motivation' => $validated['motivation'] ?? null,
            'status' => 'pending',
            'registered_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration submitted successfully',
            'data' => $registration->load(['event', 'volunteer'])
        ], 201);
    }

    public function show($id) {
        $registration = EventRegistration::with(['event', 'volunteer'])->findOrFail($id);
        $user = Auth::user();

        // Verify access
        $volunteer = $user->isVolunteer() ? Volunteer::where('user_id', $user->user_id)->first() : null;
        $organization = $user->isOrganization() ? Organization::where('user_id', $user->user_id)->first() : null;

        $canView = $user->isAdmin() ||
                   ($volunteer && $registration->volunteer_id === $volunteer->volunteer_id) ||
                   ($organization && $registration->event->organization_id === $organization->organization_id);

        if (!$canView) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $registration
        ]);
    }

    public function update(Request $request, $id) {
        $registration = EventRegistration::findOrFail($id);
        $user = Auth::user();

        $validated = $request->validate([
            'status' => 'sometimes|in:pending,approved,rejected,cancelled',
            'hours_contributed' => 'sometimes|integer|min:0|max:24',
            'feedback' => 'sometimes|string|max:1000',
        ]);

        // Organizations can approve/reject registrations
        if ($user->isOrganization()) {
            $organization = Organization::where('user_id', $user->user_id)->first();
            if (!$organization || $registration->event->organization_id !== $organization->organization_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only update registrations for your events'
                ], 403);
            }

            // Organizations can only update status and hours
            $allowedFields = ['status', 'hours_contributed'];
            $updateData = array_intersect_key($validated, array_flip($allowedFields));
            
        } elseif ($user->isVolunteer()) {
            // Volunteers can cancel their registrations
            $volunteer = Volunteer::where('user_id', $user->user_id)->first();
            if (!$volunteer || $registration->volunteer_id !== $volunteer->volunteer_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Volunteers can only cancel
            if (isset($validated['status']) && $validated['status'] !== 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Volunteers can only cancel registrations'
                ], 403);
            }

            $updateData = ['status' => 'cancelled'];
            
        } elseif ($user->isAdmin()) {
            // Admins can update everything
            $updateData = $validated;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $registration->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Registration updated successfully',
            'data' => $registration->load(['event', 'volunteer'])
        ]);
    }

    public function destroy($id) {
        $registration = EventRegistration::findOrFail($id);
        $user = Auth::user();

        // Only admins or the volunteer who registered can delete
        $volunteer = $user->isVolunteer() ? Volunteer::where('user_id', $user->user_id)->first() : null;
        
        $canDelete = $user->isAdmin() || 
                     ($volunteer && $registration->volunteer_id === $volunteer->volunteer_id);

        if (!$canDelete) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $registration->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Registration deleted successfully'
        ], 204);
    }
}
