<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index() {
        return response()->json(Event::with(['organization', 'skills', 'images'])->get());
    }

    public function store(Request $request) {
        $event = Event::create($request->all());
        return response()->json($event, 201);
    }

    public function show($id) {
        return response()->json(Event::with(['organization', 'skills', 'images'])->findOrFail($id));
    }

    public function update(Request $request, $id) {
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return response()->json($event);
    }

    public function destroy($id) {
        Event::destroy($id);
        return response()->json(null, 204);
    }
}
