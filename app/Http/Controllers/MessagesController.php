<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    /**
     * Display the messages inbox
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Get all conversations (grouped by the other user)
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($userId) {
                // Group by the other user (not current user)
                return $message->sender_id == $userId 
                    ? $message->receiver_id 
                    : $message->sender_id;
            })
            ->map(function ($messages) use ($userId) {
                $latestMessage = $messages->first();
                $otherUser = $latestMessage->sender_id == $userId 
                    ? $latestMessage->receiver 
                    : $latestMessage->sender;
                
                return [
                    'user' => $otherUser,
                    'latest_message' => $latestMessage,
                    'unread_count' => $messages->where('receiver_id', $userId)
                                               ->where('is_read', false)
                                               ->count()
                ];
            })
            ->values();

        // Get selected conversation if any
        $selectedUserId = request('user_id');
        $messages = collect();
        $selectedUser = null;

        if ($selectedUserId) {
            $selectedUser = User::find($selectedUserId);
            $messages = Message::betweenUsers($userId, $selectedUserId)
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();
            
            // Mark messages as read
            Message::where('receiver_id', $userId)
                ->where('sender_id', $selectedUserId)
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        return view('volunteer.messages', compact('conversations', 'messages', 'selectedUser'));
    }

    /**
     * Send a new message
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,user_id',
            'message' => 'required|string|max:2000'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        return redirect()->route('volunteer.messages', ['user_id' => $request->receiver_id])
            ->with('success', 'Message sent successfully!');
    }

    /**
     * Get unread message count
     */
    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark conversation as read
     */
    public function markAsRead($userId)
    {
        Message::where('receiver_id', Auth::id())
            ->where('sender_id', $userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }
}