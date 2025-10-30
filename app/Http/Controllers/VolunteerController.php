<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index() {
        return response()->json(Volunteer::with('skills')->get());
    }

    public function store(Request $request) {
        $volunteer = Volunteer::create($request->all());
        return response()->json($volunteer, 201);
    }

    public function show($id) {
        return response()->json(Volunteer::with('skills')->findOrFail($id));
    }

    public function update(Request $request, $id) {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->update($request->all());
        return response()->json($volunteer);
    }

    public function destroy($id) {
        Volunteer::destroy($id);
        return response()->json(null, 204);
    }
}
