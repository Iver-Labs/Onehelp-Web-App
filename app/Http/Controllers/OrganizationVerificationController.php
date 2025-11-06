<?php

namespace App\Http\Controllers;

use App\Models\OrganizationVerification;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationVerificationController extends Controller
{

    public function index() {
        // Only admins can list all verifications
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => OrganizationVerification::with('organization')->get()
        ]);
    }

    public function store(Request $request) {
        // Only organizations can submit verification requests
        if (!Auth::user()->isOrganization()) {
            return response()->json([
                'success' => false,
                'message' => 'Only organizations can submit verification requests'
            ], 403);
        }

        $validated = $request->validate([
            'organization_id' => 'required|exists:organizations,organization_id',
            'document_type' => 'required|string|in:registration_certificate,tax_certificate,other',
            'document_url' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Verify the organization belongs to the authenticated user
        $organization = Organization::where('organization_id', $validated['organization_id'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$organization) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only submit verification for your own organization.'
            ], 403);
        }

        // Check if already has a pending verification
        $existing = OrganizationVerification::where('organization_id', $validated['organization_id'])
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'A verification request is already pending'
            ], 400);
        }

        $verification = OrganizationVerification::create(array_merge($validated, [
            'status' => 'pending',
            'submitted_at' => now(),
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Verification request submitted successfully',
            'data' => $verification->load('organization')
        ], 201);
    }

    public function show($id) {
        $verification = OrganizationVerification::with('organization')->findOrFail($id);

        // Organizations can only see their own, admins can see all
        $organization = Auth::user()->isOrganization() ? Organization::where('user_id', Auth::id())->first() : null;
        
        $canView = Auth::user()->isAdmin() ||
                   ($organization && $verification->organization_id === $organization->organization_id);

        if (!$canView) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $verification
        ]);
    }

    public function update(Request $request, $id) {
        // Only admins can update verification status
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $verification = OrganizationVerification::findOrFail($id);

        $validated = $request->validate([
            'status' => 'sometimes|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if (isset($validated['status'])) {
            $validated['verified_at'] = now();
        }

        $verification->update($validated);

        // Update organization verification status
        if (isset($validated['status']) && $validated['status'] === 'approved') {
            $verification->organization->update(['is_verified' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification updated successfully',
            'data' => $verification->load('organization')
        ]);
    }

    public function destroy($id) {
        $verification = OrganizationVerification::findOrFail($id);

        // Organizations can delete their own pending requests, admins can delete any
        $organization = Auth::user()->isOrganization() ? Organization::where('user_id', Auth::id())->first() : null;
        
        $canDelete = Auth::user()->isAdmin() ||
                     ($organization && 
                      $verification->organization_id === $organization->organization_id &&
                      $verification->status === 'pending');

        if (!$canDelete) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $verification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Verification deleted successfully'
        ], 204);
    }
}
