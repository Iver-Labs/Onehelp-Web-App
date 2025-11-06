<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        // Require authentication for all user operations
        $this->middleware('api.auth');
    }

    public function index() {
        // Only admins can list all users
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => User::all()
        ]);
    }

    public function store(Request $request) {
        // Only admins can create users via API
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|max:255',
            'user_type' => 'required|in:volunteer,organization,admin',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'user_type' => $validated['user_type'],
            'is_active' => $validated['is_active'] ?? true,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    public function show($id) {
        $user = User::findOrFail($id);

        // Users can only view their own profile unless they're admin
        if (Auth::id() !== (int)$id && !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only view your own profile.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        // Users can only update their own profile unless they're admin
        if (Auth::id() !== (int)$id && !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only update your own profile.'
            ], 403);
        }

        $validated = $request->validate([
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'password' => 'sometimes|string|min:8|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $updateData = [];
        if (isset($validated['email'])) {
            $updateData['email'] = $validated['email'];
        }
        if (isset($validated['password'])) {
            $updateData['password_hash'] = Hash::make($validated['password']);
        }
        if (isset($validated['is_active']) && Auth::user()->isAdmin()) {
            // Only admins can change active status
            $updateData['is_active'] = $validated['is_active'];
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    public function destroy($id) {
        // Only admins can delete users
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        User::destroy($id);
        
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ], 204);
    }
}
