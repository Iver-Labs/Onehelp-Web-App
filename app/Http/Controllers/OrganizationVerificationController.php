<?php

namespace App\Http\Controllers;

use App\Models\OrganizationVerification;
use Illuminate\Http\Request;

class OrganizationVerificationController extends Controller
{
    public function index() {
        return response()->json(OrganizationVerification::with('organization')->get());
    }

    public function store(Request $request) {
        $verification = OrganizationVerification::create($request->all());
        return response()->json($verification, 201);
    }

    public function show($id) {
        return response()->json(OrganizationVerification::with('organization')->findOrFail($id));
    }

    public function update(Request $request, $id) {
        $verification = OrganizationVerification::findOrFail($id);
        $verification->update($request->all());
        return response()->json($verification);
    }

    public function destroy($id) {
        OrganizationVerification::destroy($id);
        return response()->json(null, 204);
    }
}
