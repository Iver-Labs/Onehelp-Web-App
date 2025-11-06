<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct()
    {
        // Public routes don't need auth
        $this->middleware('api.auth')->except(['index', 'show']);
    }

    public function index() {
        // Public endpoint - anyone can view events list
        $events = Event::with(['organization', 'skills', 'images'])
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    public function store(Request $request) {
        // Only organizations can create events
        if (!Auth::user()->isOrganization()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only organizations can create events.'
            ], 403);
        }

        $validated = $request->validate([
            'organization_id' => 'required|exists:organizations,organization_id',
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'long_description' => 'nullable|string',
            'event_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'max_volunteers' => 'required|integer|min:1|max:10000',
            'status' => 'sometimes|in:open,closed,cancelled',
        ]);

        // Verify the organization belongs to the authenticated user
        $organization = Organization::where('organization_id', $validated['organization_id'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$organization) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only create events for your own organization.'
            ], 403);
        }

        // Verify organization is verified
        if (!$organization->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Your organization must be verified before creating events.'
            ], 403);
        }

        $event = Event::create(array_merge($validated, [
            'created_at' => now()
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $event->load(['organization', 'skills', 'images'])
        ], 201);
    }

    public function show($id) {
        // Public endpoint - anyone can view event details
        $event = Event::with(['organization', 'skills', 'images'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    public function update(Request $request, $id) {
        $event = Event::findOrFail($id);

        // Only the organization that created the event can update it
        $organization = Organization::where('user_id', Auth::id())->first();
        
        if (!$organization || $event->organization_id !== $organization->organization_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only update your own events.'
            ], 403);
        }

        $validated = $request->validate([
            'event_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'long_description' => 'nullable|string',
            'event_date' => 'sometimes|date',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i',
            'location' => 'sometimes|string|max:255',
            'max_volunteers' => 'sometimes|integer|min:1|max:10000',
            'status' => 'sometimes|in:open,closed,cancelled',
        ]);

        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'data' => $event->load(['organization', 'skills', 'images'])
        ]);
    }

    public function destroy($id) {
        $event = Event::findOrFail($id);

        // Only the organization that created the event can delete it
        $organization = Organization::where('user_id', Auth::id())->first();
        
        if (!$organization || $event->organization_id !== $organization->organization_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only delete your own events.'
            ], 403);
        }

        $event->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ], 204);
    }
}
