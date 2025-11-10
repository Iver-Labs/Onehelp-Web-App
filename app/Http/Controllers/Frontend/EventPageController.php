<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventPageController extends Controller
{
    // Show all events (for /events)
    public function index()
    {
        $events = Event::with(['organization', 'images'])
            ->where('status', 'open')
            ->where('event_date', '>=', now())
            ->latest()
            ->paginate(12);
        return view('pages.events', compact('events'));
    }

    // Show one event (for /events/{id})
    public function show($id)
    {
        $event = Event::with(['organization', 'images', 'registrations'])->findOrFail($id);
        
        // Check if current user has already registered
        $isRegistered = false;
        $userRegistration = null;
        
        if (Auth::check() && Auth::user()->user_type === 'volunteer') {
            $volunteer = Volunteer::where('user_id', Auth::id())->first();
            if ($volunteer) {
                $userRegistration = EventRegistration::where('event_id', $event->event_id)
                    ->where('volunteer_id', $volunteer->volunteer_id)
                    ->first();
                $isRegistered = $userRegistration !== null;
            }
        }
        
        return view('pages.event-detail', compact('event', 'isRegistered', 'userRegistration'));
    }

    // Register for an event
    public function register(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to register for events.');
        }

        if (Auth::user()->user_type !== 'volunteer') {
            return redirect()->back()->with('error', 'Only volunteers can register for events.');
        }

        $event = Event::findOrFail($id);
        $volunteer = Volunteer::where('user_id', Auth::id())->first();

        if (!$volunteer) {
            return redirect()->back()->with('error', 'Volunteer profile not found.');
        }

        // Check if already registered
        $existingRegistration = EventRegistration::where('event_id', $event->event_id)
            ->where('volunteer_id', $volunteer->volunteer_id)
            ->first();

        if ($existingRegistration) {
            return redirect()->back()->with('error', 'You have already registered for this event.');
        }

        // Check if event is full
        if ($event->registered_count >= $event->max_volunteers) {
            return redirect()->back()->with('error', 'This event is full.');
        }

        // Check if event is still open
        if ($event->status !== 'open') {
            return redirect()->back()->with('error', 'This event is no longer accepting registrations.');
        }

        // Create registration
        EventRegistration::create([
            'event_id' => $event->event_id,
            'volunteer_id' => $volunteer->volunteer_id,
            'status' => 'pending',
            'registered_at' => now()
        ]);

        return redirect()->back()->with('success', 'Your application has been submitted successfully!');
    }
}

