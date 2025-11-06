
# Critical Files

## app/Http/Controllers/VolunteerController.php

```php
<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
    public function messages()
    {
        // TODO: Implement messages functionality
        return view('volunteer.messages');
    }
    
    // API Methods
    public function index()
    {
        return response()->json(Volunteer::with('skills')->get());
    }

    public function store(Request $request)
    {
        $volunteer = Volunteer::create($request->all());
        return response()->json($volunteer, 201);
    }

    public function show($id)
    {
        return response()->json(Volunteer::with('skills')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->update($request->all());
        return response()->json($volunteer);
    }

    public function destroy($id)
    {
        Volunteer::destroy($id);
        return response()->json(null, 204);
    }
}
```

## resources/views/volunteer/messages.blade.php

```html
@extends('layouts.volunteer-app')

@section('title', 'Messages - OneHelp')

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-header">
        <div class="welcome-avatar">
            @if(auth()->user()->volunteer && auth()->user()->volunteer->profile_image)
                <img src="{{ asset('storage/' . auth()->user()->volunteer->profile_image) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            @endif
        </div>
        <div class="welcome-text">
            <h1>Welcome back, {{ auth()->user()->volunteer->first_name ?? 'Volunteer' }}!</h1>
            <p>See the difference you're making.</p>
        </div>
    </div>
</div>

<!-- Messages Card -->
<div class="card" style="padding: 0; overflow: hidden; height: 600px;">
    <div style="display: flex; height: 100%;">
        <!-- Left Sidebar - Conversations List -->
        <div style="width: 35%; border-right: 2px solid #E5E7EB; overflow-y: auto;">
            <!-- Search Header -->
            <div style="padding: 20px; border-bottom: 2px solid #E5E7EB;">
                <h2 style="font-size: 18px; font-weight: 600; color: #2C3E50; margin-bottom: 12px;">Messages</h2>
                <input type="text" placeholder="Search conversations..." 
                       style="width: 100%; padding: 10px 14px; border: 2px solid #D1D5DB; border-radius: 12px; font-size: 13px;">
            </div>

            <!-- Conversations List -->
            <div>
                @if($conversations->isEmpty())
                    <div style="padding: 40px 20px; text-align: center;">
                        <svg style="width: 60px; height: 60px; color: #D1D5DB; margin: 0 auto 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <p style="font-size: 14px; color: #6B7280; font-weight: 500;">No messages yet</p>
                        <p style="font-size: 12px; color: #9CA3AF; margin-top: 5px;">Your conversations will appear here</p>
                    </div>
                @else
                    @foreach($conversations as $conversation)
                    <a href="{{ route('volunteer.messages', ['user_id' => $conversation['user']->user_id]) }}" 
                       style="display: block; padding: 15px 20px; border-bottom: 1px solid #F0F0F0; text-decoration: none; transition: background 0.2s; {{ request('user_id') == $conversation['user']->user_id ? 'background: #F9FAFB;' : '' }}"
                       onmouseover="this.style.background='#F9FAFB'"
                       onmouseout="this.style.background='{{ request('user_id') == $conversation['user']->user_id ? '#F9FAFB' : 'transparent' }}'">
                        <div style="display: flex; align-items: start; gap: 12px;">
                            <!-- Avatar -->
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: #E8D4A7; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #2C3E50; font-weight: 600; font-size: 14px;">
                                {{ strtoupper(substr($conversation['user']->organization->org_name ?? $conversation['user']->email, 0, 2)) }}
                            </div>
                            
                            <!-- Message Info -->
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
                                    <h3 style="font-size: 14px; font-weight: 600; color: #2C3E50; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $conversation['user']->organization->org_name ?? $conversation['user']->email }}
                                    </h3>
                                    <span style="font-size: 11px; color: #9CA3AF; white-space: nowrap; margin-left: 8px;">
                                        {{ $conversation['latest_message']->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p style="font-size: 13px; color: #6B7280; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ Str::limit($conversation['latest_message']->message, 50) }}
                                </p>
                                @if($conversation['unread_count'] > 0)
                                <span style="display: inline-block; margin-top: 6px; padding: 3px 8px; background: #5BA8C9; color: white; border-radius: 10px; font-size: 11px; font-weight: 600;">
                                    {{ $conversation['unread_count'] }} new
                                </span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Right Panel - Messages -->
        <div style="flex: 1; display: flex; flex-direction: column;">
            @if($selectedUser)
                <!-- Chat Header -->
                <div style="padding: 20px 25px; border-bottom: 2px solid #E5E7EB; background: #F9FAFB;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 45px; height: 45px; border-radius: 50%; background: #7CB5B3; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
                            {{ strtoupper(substr($selectedUser->organization->org_name ?? $selectedUser->email, 0, 2)) }}
                        </div>
                        <div>
                            <h3 style="font-size: 16px; font-weight: 600; color: #2C3E50; margin: 0;">
                                {{ $selectedUser->organization->org_name ?? $selectedUser->email }}
                            </h3>
                            <p style="font-size: 12px; color: #6B7280; margin: 0;">Organization</p>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div style="flex: 1; overflow-y: auto; padding: 25px; background: #F9FAFB;">
                    @if($messages->isEmpty())
                        <div style="text-align: center; padding: 60px 20px;">
                            <svg style="width: 70px; height: 70px; color: #D1D5DB; margin: 0 auto 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p style="font-size: 14px; color: #6B7280; font-weight: 500;">No messages yet</p>
                            <p style="font-size: 12px; color: #9CA3AF; margin-top: 5px;">Start the conversation!</p>
                        </div>
                    @else
                        @foreach($messages as $message)
                            @php
                                $isCurrentUser = $message->sender_id == auth()->id();
                            @endphp
                            <div style="margin-bottom: 20px; display: flex; {{ $isCurrentUser ? 'justify-content: flex-end;' : 'justify-content: flex-start;' }}">
                                <div style="max-width: 70%;">
                                    <div style="background: {{ $isCurrentUser ? '#7CB5B3' : 'white' }}; color: {{ $isCurrentUser ? 'white' : '#2C3E50' }}; padding: 12px 16px; border-radius: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                                        <p style="margin: 0; font-size: 14px; line-height: 1.5;">{{ $message->message }}</p>
                                    </div>
                                    <div style="margin-top: 5px; font-size: 11px; color: #9CA3AF; {{ $isCurrentUser ? 'text-align: right;' : 'text-align: left;' }}">
                                        {{ $message->created_at->format('M d, g:i A') }}
                                        @if($isCurrentUser && $message->is_read)
                                            <span style="color: #7CB5B3;">✓✓</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Message Input -->
                <div style="padding: 20px 25px; border-top: 2px solid #E5E7EB; background: white;">
                    <form method="POST" action="{{ route('volunteer.messages.send') }}" style="display: flex; gap: 12px; align-items: center;">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $selectedUser->user_id }}">
                        <input type="text" name="message" placeholder="Type your message..." required
                               style="flex: 1; padding: 12px 16px; border: 2px solid #D1D5DB; border-radius: 12px; font-size: 14px; outline: none;">
                        <button type="submit" 
                                style="padding: 12px 24px; background: #7CB5B3; color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                            Send
                        </button>
                    </form>
                </div>
            @else
                <!-- No Conversation Selected -->
                <div style="flex: 1; display: flex; align-items: center; justify-content: center; background: #F9FAFB;">
                    <div style="text-align: center;">
                        <svg style="width: 90px; height: 90px; color: #D1D5DB; margin: 0 auto 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <h3 style="font-size: 18px; font-weight: 600; color: #4A5568; margin-bottom: 8px;">Select a conversation</h3>
                        <p style="font-size: 14px; color: #9CA3AF;">Choose a conversation from the left to view messages</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
```

## database/migrations/2025_11_05_232911_create_messages_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('sender_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('user_id')->on('users')->onDelete('cascade');

            // Indexes for performance
            $table->index(['sender_id', 'receiver_id']);
            $table->index('is_read');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
```

## app/Models/Message.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'message_id';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the sender of the message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }

    /**
     * Get the receiver of the message
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'user_id');
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for messages between two users
     */
    public function scopeBetweenUsers($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId1)
              ->where('receiver_id', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId2)
              ->where('receiver_id', $userId1);
        });
    }
}
```
