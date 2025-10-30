<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index() {
        return response()->json(Organization::with('events')->get());
    }

    public function store(Request $request) {
        $organization = Organization::create($request->all());
        return response()->json($organization, 201);
    }

    public function show($id) {
        return response()->json(Organization::with('events')->findOrFail($id));
    }

    public function update(Request $request, $id) {
        $organization = Organization::findOrFail($id);
        $organization->update($request->all());
        return response()->json($organization);
    }

    public function destroy($id) {
        Organization::destroy($id);
        return response()->json(null, 204);
    }
}
