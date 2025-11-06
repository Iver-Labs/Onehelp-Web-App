<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Message;
use App\Models\User;

class VolunteerController extends Controller
{
    /**
     * Display the volunteer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get volunteer profile - user_id in volunteers table references user_id in users table
        $volunteer = Volunteer::where('user_id', $user->user_id)->first();
        
        if (!$volunteer) {
            return redirect()->route('home')->with('error', 'Volunteer profile not found.');
        }
        
        // Get all approved registrations
        $approvedRegistrations = EventRegistration::where('volunteer_id', $volunteer->volunteer_id)
            ->where('status', 'approved')
            ->with('event')
            ->get();
        
        // Total approved events
        $totalEvents = $approvedRegistrations->count();
        
        // Completed events (approved AND event date has passed)
        $completedEvents = $approvedRegistrations->filter(function ($registration) {
            return Carbon::parse($registration->event->event_date)->isPast();
        })->count();
        
        // Get all applications (any status) for My History table
        $applications = EventRegistration::where('volunteer_id', $volunteer->volunteer_id)
            ->with('event')
            ->orderBy('registered_at', 'desc')
            ->get();
        
        // Calculate volunteers mobilized (mock data - replace with actual logic)
        $volunteersMobilized = 87; // TODO: Calculate based on events attended
        
        // Calculate community hours
        $communityHours = $volunteer->total_hours ?? 45;
        
        // Monthly Impact Stats
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $monthlyRegistrations = EventRegistration::where('volunteer_id', $volunteer->volunteer_id)
            ->whereYear('registered_at', $currentYear)
            ->whereMonth('registered_at', $currentMonth)
            ->with('event')
            ->get();
        
        $monthlyImpact = [
            'events' => $monthlyRegistrations->count(),
            'hours' => $monthlyRegistrations->sum('hours_contributed') ?? 23,
            'upcoming' => $monthlyRegistrations->filter(function($reg) {
                return Carbon::parse($reg->event->event_date)->isFuture();
            })->count(),
            'certificates' => $volunteer->events_completed ?? 4,
            'badges' => 4, // TODO: Implement badges system
            'mobilized' => $volunteersMobilized
        ];
        
        // Recent Activity - last 10 activities
        $recentActivities = $this->getRecentActivities($volunteer->volunteer_id);
        
        return view('volunteer.dashboard', compact(
            'volunteer',
            'totalEvents',
            'completedEvents',
            'applications',
            'recentActivities',
            'volunteersMobilized',
            'communityHours',
            'monthlyImpact'
        ));
    }
    
    /**
     * Get recent activities for the volunteer
     */
    private function getRecentActivities($volunteerId)
    {
        $activities = [];
        
        // Get recent registrations with their events
        $registrations = EventRegistration::where('volunteer_id', $volunteerId)
            ->with('event')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
        
        foreach ($registrations as $registration) {
            $eventName = $registration->event->event_name;
            $date = Carbon::parse($registration->updated_at)->format('m/d/Y');
            
            if ($registration->status === 'approved') {
                $activities[] = [
                    'message' => "Application Approved for {$eventName}",
                    'date' => $date,
                    'type' => 'approved'
                ];
            } elseif ($registration->status === 'pending') {
                $activities[] = [
                    'message' => "Application Pending: {$eventName}",
                    'date' => $date,
                    'type' => 'pending'
                ];
            } elseif ($registration->status === 'rejected') {
                $activities[] = [
                    'message' => "Application Rejected for {$eventName}",
                    'date' => $date,
                    'type' => 'rejected'
                ];
            }
        }
        
        // Sort by date (newest first)
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return collect($activities)->take(5);
    }
    
    /**
     * Display events page
     */
    public function events()
    {
        // TODO: Implement events listing page
        return view('volunteer.events');
    }
    public function profile()
    {
        $user = Auth::user();
        $volunteer = Volunteer::where('user_id', $user->user_id)->first();
        
        if (!$volunteer) {
            return redirect()->route('home')->with('error', 'Volunteer profile not found.');
        }
        
        // Get stats for the profile page
        $stats = $this->getVolunteerStats($volunteer->volunteer_id);
        
        return view('volunteer.profile', compact('volunteer', 'stats'));
    }
    
    /**
     * Update volunteer personal information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $volunteer = Volunteer::where('user_id', $user->user_id)->first();
        
        if (!$volunteer) {
            return redirect()->back()->with('error', 'Volunteer profile not found.');
        }
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'age' => 'nullable|integer|min:1|max:120',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($volunteer->profile_image && \Storage::disk('public')->exists($volunteer->profile_image)) {
                \Storage::disk('public')->delete($volunteer->profile_image);
            }
            
            // Store new image
            $path = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = $path;
        }
        
        $volunteer->update($validated);
        
        return redirect()->route('volunteer.profile')->with('success', 'Personal information updated successfully!');
    }
    
    /**
     * Update volunteer information (skills, interests, etc.)
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();
        $volunteer = Volunteer::where('user_id', $user->user_id)->first();
        
        if (!$volunteer) {
            return redirect()->back()->with('error', 'Volunteer profile not found.');
        }
        
        $validated = $request->validate([
            'skills' => 'nullable|string|max:500',
            'interests' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'availability' => 'nullable|string|max:255'
        ]);
        
        $volunteer->update($validated);
        
        return redirect()->route('volunteer.profile')->with('success', 'Volunteer information updated successfully!');
    }
    
    /**
     * Display the account settings page
     */
    public function account()
    {
        $user = Auth::user();
        $volunteer = Volunteer::where('user_id', $user->user_id)->first();
        
        // Get stats for the account page
        $stats = $this->getVolunteerStats($volunteer->volunteer_id ?? 0);
        
        return view('volunteer.account', compact('user', 'stats'));
    }
    
    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Check if current password is correct
        if (!\Hash::check($request->current_password, $user->password_hash)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }
        
        // Update password
        $user->password_hash = \Hash::make($request->new_password);
        $user->save();
        
        return redirect()->route('volunteer.account')->with('success', 'Password updated successfully!');
    }
    
    /**
     * Deactivate user account
     */
    public function deactivateAccount(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);
        
        $user = Auth::user();
        
        // Verify password
        if (!\Hash::check($request->password, $user->password_hash)) {
            return redirect()->back()->with('error', 'Password is incorrect.');
        }
        
        // Soft delete and deactivate
        $user->is_active = false;
        $user->save();
        $user->delete(); // Soft delete if using SoftDeletes trait
        
        // Logout the user
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Your account has been deactivated.');
    }
    
    /**
     * Get volunteer stats
     */
    private function getVolunteerStats($volunteerId)
    {
        $approvedRegistrations = EventRegistration::where('volunteer_id', $volunteerId)
            ->where('status', 'approved')
            ->with('event')
            ->get();
        
        return [
            'totalEvents' => $approvedRegistrations->count(),
            'completedEvents' => $approvedRegistrations->filter(function ($reg) {
                return Carbon::parse($reg->event->event_date)->isPast();
            })->count()
        ];
    }
    
    /**
 * Display the messages page
 */
public function messages(Request $request)
{
    $currentUserId = auth()->id();
    $selectedUserId = $request->get('user_id');
    
    // Get all unique conversations for this volunteer
    $conversationUsers = Message::where(function($query) use ($currentUserId) {
            $query->where('sender_id', $currentUserId)
                  ->orWhere('receiver_id', $currentUserId);
        })
        ->with(['sender.organization', 'sender.volunteer', 'receiver.organization', 'receiver.volunteer'])
        ->get()
        ->flatMap(function($message) use ($currentUserId) {
            // Get the other user (not current user)
            return $message->sender_id == $currentUserId 
                ? [$message->receiver] 
                : [$message->sender];
        })
        ->unique('user_id')
        ->filter(); // Remove any null values
    
    // Build conversations array with latest message and unread count
    $conversations = $conversationUsers->map(function($user) use ($currentUserId) {
        // Get latest message between current user and this user
        $latestMessage = Message::betweenUsers($currentUserId, $user->user_id)
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Count unread messages from this user
        $unreadCount = Message::where('sender_id', $user->user_id)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->count();
        
        return [
            'user' => $user,
            'latest_message' => $latestMessage,
            'unread_count' => $unreadCount
        ];
    })
    ->sortByDesc(function($conversation) {
        return $conversation['latest_message']->created_at ?? now();
    })
    ->values();
    
    // Get selected user and messages if user_id is provided
    $selectedUser = null;
    $messages = collect();
    
    if ($selectedUserId) {
        $selectedUser = User::with(['organization', 'volunteer'])->find($selectedUserId);
        
        if ($selectedUser) {
            // Get all messages between current user and selected user
            $messages = Message::betweenUsers($currentUserId, $selectedUserId)
                ->orderBy('created_at', 'asc')
                ->get();
            
            // Mark messages from selected user as read
            Message::where('sender_id', $selectedUserId)
                ->where('receiver_id', $currentUserId)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);
        }
    }
    
    return view('volunteer.messages', compact('conversations', 'selectedUser', 'messages'));
}

/**
 * Send a message
 */
public function sendMessage(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|exists:users,user_id',
        'message' => 'required|string|max:1000'
    ]);
    
    Message::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $request->receiver_id,
        'message' => $request->message,
        'is_read' => false
    ]);
    
    return redirect()->route('volunteer.messages', ['user_id' => $request->receiver_id])
        ->with('success', 'Message sent successfully!');
}
}