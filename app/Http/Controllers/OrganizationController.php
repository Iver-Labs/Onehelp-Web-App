<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\User;

class OrganizationController extends Controller
{
    /**
     * Get pending applications count for the organization and share with views
     */
    private function sharePendingCount($organizationId)
    {
        $pendingCount = EventRegistration::whereHas('event', function($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->where('status', 'pending')->count();
        
        view()->share('pendingCount', $pendingCount);
        
        return $pendingCount;
    }

    /**
     * Display the organization dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Debug: Check if user exists
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
        // Get organization using user_id (which is the primary key in users table)
        $organization = Organization::where('user_id', $user->user_id)->first();
        
        // Debug: If organization not found
        if (!$organization) {
            return redirect()->route('home')->with('error', 'Organization profile not found. User ID: ' . $user->user_id);
        }
        
        // Calculate statistics
        $stats = $this->getOrganizationStats($organization->organization_id);
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities($organization->organization_id);
        
        // Get monthly impact
        $monthlyImpact = $this->getMonthlyImpact($organization->organization_id);
        
        // Get pending applications count
        $pendingCount = $this->sharePendingCount($organization->organization_id);
        
        return view('organization.dashboard', compact(
            'organization',
            'stats',
            'recentActivities',
            'monthlyImpact',
            'pendingCount'
        ));
    }
    
    /**
     * Calculate organization statistics
     */
    private function getOrganizationStats($organizationId)
    {
        // Total events created by this organization
        $totalEvents = Event::where('organization_id', $organizationId)->count();
        
        // Completed events (past date)
        $completedEvents = Event::where('organization_id', $organizationId)
            ->whereDate('event_date', '<', now())
            ->count();
        
        // Volunteers mobilized (unique volunteers across all events)
        $volunteersMobilized = EventRegistration::whereHas('event', function($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })
        ->where('status', 'approved')
        ->distinct('volunteer_id')
        ->count('volunteer_id');
        
        // Community hours (sum of hours contributed across all events)
        $communityHours = EventRegistration::whereHas('event', function($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })
        ->where('status', 'approved')
        ->sum('hours_contributed') ?? 0;
        
        return [
            'totalEvents' => $totalEvents,
            'completedEvents' => $completedEvents,
            'volunteersMobilized' => $volunteersMobilized,
            'communityHours' => $communityHours
        ];
    }
    
    /**
     * Get recent activities
     */
    private function getRecentActivities($organizationId)
    {
        $activities = [];
        
        // Get recent event registrations (applications)
        $recentRegistrations = EventRegistration::whereHas('event', function($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })
        ->with(['volunteer', 'event'])
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
        
        foreach ($recentRegistrations as $reg) {
            $volunteerName = $reg->volunteer->first_name . ' ' . $reg->volunteer->last_name;
            $eventName = $reg->event->event_name;
            $date = Carbon::parse($reg->created_at)->format('m/d/Y');
            
            if ($reg->status === 'pending') {
                $activities[] = [
                    'message' => "New application from {$volunteerName} for {$eventName}",
                    'date' => $date,
                    'color' => '#ECC94B', // Yellow
                    'timestamp' => $reg->created_at
                ];
            } elseif ($reg->status === 'approved') {
                $activities[] = [
                    'message' => "Approved {$volunteerName} for {$eventName}",
                    'date' => $date,
                    'color' => '#48BB78', // Green
                    'timestamp' => $reg->updated_at
                ];
            }
        }
        
        // Get recent events created
        $recentEvents = Event::where('organization_id', $organizationId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        foreach ($recentEvents as $event) {
            $activities[] = [
                'message' => "{$event->event_name} created",
                'date' => Carbon::parse($event->created_at)->format('m/d/Y'),
                'color' => '#4299E1', // Blue
                'timestamp' => $event->created_at
            ];
        }
        
        // Get completed events
        $completedEvents = Event::where('organization_id', $organizationId)
            ->whereDate('event_date', '<', now())
            ->whereDate('event_date', '>', now()->subDays(30))
            ->orderBy('event_date', 'desc')
            ->take(5)
            ->get();
        
        foreach ($completedEvents as $event) {
            $activities[] = [
                'message' => "{$event->event_name} completed successfully",
                'date' => Carbon::parse($event->event_date)->format('m/d/Y'),
                'color' => '#48BB78', // Green
                'timestamp' => $event->event_date
            ];
        }
        
        // Sort by timestamp (newest first)
        usort($activities, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });
        
        return collect($activities)->take(10);
    }
    
    /**
     * Get monthly impact statistics
     */
    private function getMonthlyImpact($organizationId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Events created this month
        $eventsCreated = Event::where('organization_id', $organizationId)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        
        // New volunteers this month (unique volunteers who registered this month)
        $newVolunteers = EventRegistration::whereHas('event', function($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->where('status', 'approved')
        ->distinct('volunteer_id')
        ->count('volunteer_id');
        
        // Hours organized this month
        $hoursOrganized = EventRegistration::whereHas('event', function($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->where('status', 'approved')
        ->sum('hours_contributed') ?? 0;
        
        return [
            'eventsCreated' => $eventsCreated,
            'newVolunteers' => $newVolunteers,
            'hoursOrganized' => $hoursOrganized
        ];
    }
    
    /**
     * Display manage applications page
     */
    public function applications()
    {
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->user_id)->first();
        
        if (!$organization) {
            return redirect()->route('home')->with('error', 'Organization profile not found.');
        }
        
        // Get all applications for this organization's events
        $applications = EventRegistration::whereHas('event', function($query) use ($organization) {
            $query->where('organization_id', $organization->organization_id);
        })
        ->with(['volunteer', 'event'])
        ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
        ->orderBy('created_at', 'desc')
        ->get();
        
        // Get pending count
        $this->sharePendingCount($organization->organization_id);
        
        return view('organization.applications', compact('applications', 'organization'));
    }
    
    /**
     * Update application status
     */
    public function updateApplicationStatus(Request $request, $registrationId)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);
        
        $registration = EventRegistration::findOrFail($registrationId);
        $registration->update([
            'status' => $request->status
        ]);
        
        return redirect()->back()->with('success', 'Application status updated successfully!');
    }
    
    /**
     * Display analytics page
     */
    public function analytics()
    {
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->user_id)->first();
        
        if (!$organization) {
            return redirect()->route('home')->with('error', 'Organization profile not found.');
        }
        
        $this->sharePendingCount($organization->organization_id);
        
        return view('organization.analytics', compact('organization'));
    }

    /**
     * Send a message from the organization
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

        return redirect()->route('organization.messages', ['user_id' => $request->receiver_id])
            ->with('success', 'Message sent successfully!');
    }
    
    /**
     * Display messages page
     */
    public function messages(Request $request)
    {
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->user_id)->first();

        if (!$organization) {
            return redirect()->route('home')->with('error', 'Organization profile not found.');
        }

        $currentUserId = $user->user_id;
        $selectedUserId = $request->get('user_id');

        // Get all unique conversations
        $conversationUsers = Message::where(function($query) use ($currentUserId) {
                $query->where('sender_id', $currentUserId)
                      ->orWhere('receiver_id', $currentUserId);
            })
            ->with(['sender.organization', 'sender.volunteer', 'receiver.organization', 'receiver.volunteer'])
            ->get()
            ->flatMap(function($message) use ($currentUserId) {
                return $message->sender_id == $currentUserId 
                    ? [$message->receiver] 
                    : [$message->sender];
            })
            ->unique('user_id')
            ->filter();

        // Build conversations array
        $conversations = $conversationUsers->map(function($user) use ($currentUserId) {
            $latestMessage = Message::betweenUsers($currentUserId, $user->user_id)
                ->orderBy('created_at', 'desc')
                ->first();
            
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

        // Get selected user and messages
        $selectedUser = null;
        $messages = collect();

        if ($selectedUserId) {
            $selectedUser = User::with(['organization', 'volunteer'])->find($selectedUserId);
            
            if ($selectedUser) {
                $messages = Message::betweenUsers($currentUserId, $selectedUserId)
                    ->orderBy('created_at', 'asc')
                    ->get();
                
                // Mark messages as read
                Message::where('sender_id', $selectedUserId)
                    ->where('receiver_id', $currentUserId)
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now()
                    ]);
            }
        }

        $pendingCount = EventRegistration::whereHas('event', function($query) use ($organization) {
            $query->where('organization_id', $organization->organization_id);
        })->where('status', 'pending')->count();

        view()->share('pendingCount', $pendingCount);

        return view('organization.messages', compact('organization', 'conversations', 'selectedUser', 'messages'));
    }

    /**
     * Show the form for creating a new event
     */
    public function createEvent()
    {
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->user_id)->first();

        if (!$organization) {
            return redirect()->route('home')->with('error', 'Organization profile not found.');
        }

        $this->sharePendingCount($organization->organization_id);

        return view('organization.create-event', compact('organization'));
    }

    /**
     * Store a newly created event
     */
    public function storeEvent(Request $request)
    {
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->user_id)->first();

        if (!$organization) {
            return redirect()->route('home')->with('error', 'Organization profile not found.');
        }

        // Validate the request
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'event_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'max_volunteers' => 'required|integer|min:1',
            'status' => 'nullable|string|in:open,closed,cancelled',
            'event_image' => 'required|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        // Additional validation: end_time must be after start_time
        if (strtotime($validated['end_time']) <= strtotime($validated['start_time'])) {
            return back()->withErrors(['end_time' => 'The end time must be after the start time.'])->withInput();
        }

        // Create the event
        $event = Event::create([
            'organization_id' => $organization->organization_id,
            'event_name' => $validated['event_name'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'event_date' => $validated['event_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'],
            'max_volunteers' => $validated['max_volunteers'],
            'status' => $validated['status'] ?? 'open',
            'registered_count' => 0
        ]);

        // Handle image upload
        if ($request->hasFile('event_image')) {
            $image = $request->file('event_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('events', $imageName, 'public');
            
            // Create event image record
            EventImage::create([
                'event_id' => $event->event_id,
                'image_url' => 'storage/' . $imagePath,
                'is_primary' => true,
                'uploaded_at' => now()
            ]);
        }

        return redirect()->route('organization.dashboard')
            ->with('success', 'Event "' . $event->event_name . '" created successfully!');
    }
}