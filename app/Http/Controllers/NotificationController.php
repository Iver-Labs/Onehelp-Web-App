<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function index() {
        // Users can only see their own notifications
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    public function store(Request $request) {
        // Only admins can create notifications
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'notification_type' => 'required|string|max:50',
            'message' => 'required|string|max:500',
            'is_read' => 'boolean',
        ]);

        $notification = Notification::create(array_merge($validated, [
            'is_read' => $validated['is_read'] ?? false,
            'created_at' => now(),
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Notification created successfully',
            'data' => $notification
        ], 201);
    }

    public function show($id) {
        $notification = Notification::findOrFail($id);

        // Users can only view their own notifications
        if ($notification->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $notification
        ]);
    }

    public function update(Request $request, $id) {
        $notification = Notification::findOrFail($id);

        // Users can only update their own notifications
        if ($notification->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'is_read' => 'sometimes|boolean',
        ]);

        $notification->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notification updated successfully',
            'data' => $notification
        ]);
    }

    public function destroy($id) {
        $notification = Notification::findOrFail($id);

        // Users can only delete their own notifications
        if ($notification->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ], 204);
    }
}
