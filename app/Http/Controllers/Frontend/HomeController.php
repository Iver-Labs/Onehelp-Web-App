<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch 4 latest events with organization and images relationships
        $events = Event::with(['organization', 'images'])
            ->where('status', 'open')
            ->where('event_date', '>=', now())
            ->latest()
            ->take(4)
            ->get();
            
        return view('pages.home', compact('events'));
    }
}
