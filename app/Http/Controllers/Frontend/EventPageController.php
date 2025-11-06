<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventPageController extends Controller
{
    // Show all events (for /events)
    public function index()
    {
        $events = Event::with(['organization', 'images'])->latest()->get();
        return view('pages.events', compact('events'));
    }

    // Show one event (for /events/{id})
    public function show($id)
    {
        $event = Event::with(['organization', 'images'])->findOrFail($id);
        return view('pages.event-details', compact('event'));
    }
}
