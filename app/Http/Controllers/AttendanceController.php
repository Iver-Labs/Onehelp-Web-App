<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EventRegistration;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{

    public function index() {
        // Only admins and organizations can list attendances
        if (!Auth::user()->isAdmin() && !Auth::user()->isOrganization()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $query = Attendance::with('registration.event', 'registration.volunteer');

        // Filter for organizations - only their events
        if (Auth::user()->isOrganization()) {
            $organization = Organization::where('user_id', Auth::id())->first();
            if ($organization) {
                $query->whereHas('registration.event', function($q) use ($organization) {
                    $q->where('organization_id', $organization->organization_id);
                });
            }
        }

        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }

    public function store(Request $request) {
        // Only organizations can mark attendance
        if (!Auth::user()->isOrganization() && !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Only organizations can record attendance'
            ], 403);
        }

        $validated = $request->validate([
            'registration_id' => 'required|exists:event_registrations,registration_id',
            'check_in_time' => 'required|date_format:Y-m-d H:i:s',
            'check_out_time' => 'nullable|date_format:Y-m-d H:i:s|after:check_in_time',
            'status' => 'required|in:present,absent,late',
            'hours_logged' => 'nullable|numeric|min:0|max:24',
        ]);

        // Verify registration belongs to organization's event
        $registration = EventRegistration::with('event')->findOrFail($validated['registration_id']);
        
        if (Auth::user()->isOrganization()) {
            $organization = Organization::where('user_id', Auth::id())->first();
            if (!$organization || $registration->event->organization_id !== $organization->organization_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only record attendance for your events'
                ], 403);
            }
        }

        $attendance = Attendance::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully',
            'data' => $attendance->load('registration')
        ], 201);
    }

    public function show($id) {
        $attendance = Attendance::with('registration.event', 'registration.volunteer')->findOrFail($id);

        // Verify access
        if (Auth::user()->isOrganization()) {
            $organization = Organization::where('user_id', Auth::id())->first();
            if (!$organization || $attendance->registration->event->organization_id !== $organization->organization_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        } elseif (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    public function update(Request $request, $id) {
        $attendance = Attendance::with('registration.event')->findOrFail($id);

        // Only organizations and admins can update
        if (Auth::user()->isOrganization()) {
            $organization = Organization::where('user_id', Auth::id())->first();
            if (!$organization || $attendance->registration->event->organization_id !== $organization->organization_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        } elseif (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'check_in_time' => 'sometimes|date_format:Y-m-d H:i:s',
            'check_out_time' => 'nullable|date_format:Y-m-d H:i:s',
            'status' => 'sometimes|in:present,absent,late',
            'hours_logged' => 'nullable|numeric|min:0|max:24',
        ]);

        $attendance->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully',
            'data' => $attendance
        ]);
    }

    public function destroy($id) {
        $attendance = Attendance::with('registration.event')->findOrFail($id);

        // Only organizations and admins can delete
        if (Auth::user()->isOrganization()) {
            $organization = Organization::where('user_id', Auth::id())->first();
            if (!$organization || $attendance->registration->event->organization_id !== $organization->organization_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        } elseif (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $attendance->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Attendance deleted successfully'
        ], 204);
    }
}
