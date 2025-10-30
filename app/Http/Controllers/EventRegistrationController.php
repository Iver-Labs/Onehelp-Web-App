<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function index() {
        return response()->json(EventRegistration::with(['event', 'volunteer'])->get());
    }

    public function store(Request $request) {
        $registration = EventRegistration::create($request->all());
        return response()->json($registration, 201);
    }

    public function show($id) {
        return response()->json(EventRegistration::with(['event', 'volunteer'])->findOrFail($id));
    }

    public function update(Request $request, $id) {
        $registration = EventRegistration::findOrFail($id);
        $registration->update($request->all());
        return response()->json($registration);
    }

    public function destroy($id) {
        EventRegistration::destroy($id);
        return response()->json(null, 204);
    }
}
