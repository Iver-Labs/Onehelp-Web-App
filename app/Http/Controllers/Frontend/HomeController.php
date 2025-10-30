<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::latest()->take(4)->get(); // Fetch 4 latest events
        return view('pages.home', compact('events'));
    }
}
