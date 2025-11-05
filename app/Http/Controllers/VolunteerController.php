<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    
    /**
     * Display the volunteer profile page
     */
    public function profile()
    {
        $user = Auth::user();
        $volunteer = Volunteer::where('user_id', $user->user_id)->first();
        
        if (!$volunteer) {
            return redirect()->route('home')->with('error', 'Volunteer profile not found.');
        }
        
        return view('volunteer.profile', compact('volunteer'));
    }
    
    /**
     * Display the account settings page
     */
    public function account()
    {
        $user = Auth::user();
        
        return view('volunteer.account', compact('user'));
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