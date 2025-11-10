<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Volunteer;
use App\Models\Organization;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\OrganizationVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function dashboard()
    {
        // Overall statistics
        $stats = [
            'totalUsers' => User::count(),
            'totalVolunteers' => Volunteer::count(),
            'totalOrganizations' => Organization::count(),
            'totalEvents' => Event::count(),
            'activeEvents' => Event::where('status', 'open')
                ->where('event_date', '>=', now())
                ->count(),
            'completedEvents' => Event::whereDate('event_date', '<', now())->count(),
            'pendingVerifications' => OrganizationVerification::where('status', 'pending')->count(),
            'totalRegistrations' => EventRegistration::count(),
            'approvedRegistrations' => EventRegistration::where('status', 'approved')->count(),
        ];

        // Recent activities
        $recentActivities = $this->getRecentActivities();

        // Monthly statistics
        $monthlyStats = $this->getMonthlyStats();

        return view('admin.dashboard', compact('stats', 'recentActivities', 'monthlyStats'));
    }

    /**
     * Get recent activities across the platform
     */
    private function getRecentActivities()
    {
        $activities = [];

        // Recent user registrations
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        foreach ($recentUsers as $user) {
            $activities[] = [
                'message' => "New {$user->user_type} registered: {$user->email}",
                'date' => Carbon::parse($user->created_at)->format('m/d/Y H:i'),
                'type' => 'user',
                'timestamp' => $user->created_at
            ];
        }

        // Recent events
        $recentEvents = Event::orderBy('created_at', 'desc')->take(5)->get();
        foreach ($recentEvents as $event) {
            $activities[] = [
                'message' => "New event created: {$event->event_name}",
                'date' => Carbon::parse($event->created_at)->format('m/d/Y H:i'),
                'type' => 'event',
                'timestamp' => $event->created_at
            ];
        }

        // Sort by timestamp
        usort($activities, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });

        return collect($activities)->take(10);
    }

    /**
     * Get monthly statistics
     */
    private function getMonthlyStats()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return [
            'newUsers' => User::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
            'newEvents' => Event::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
            'newRegistrations' => EventRegistration::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
        ];
    }

    /**
     * Display all users
     */
    public function users(Request $request)
    {
        $query = User::with(['volunteer', 'organization']);

        // Filter by user type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('user_type', $request->type);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhereHas('volunteer', function($q2) use ($request) {
                      $q2->where('first_name', 'like', '%' . $request->search . '%')
                         ->orWhere('last_name', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('organization', function($q2) use ($request) {
                      $q2->where('organization_name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Display all organizations
     */
    public function organizations()
    {
        $organizations = Organization::with(['user', 'events'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.organizations', compact('organizations'));
    }

    /**
     * Display all events
     */
    public function events(Request $request)
    {
        $query = Event::with(['organization']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('event_name', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(15);

        return view('admin.events', compact('events'));
    }

    /**
     * Display organization verifications
     */
    public function verifications()
    {
        $verifications = OrganizationVerification::with(['organization.user'])
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.verifications', compact('verifications'));
    }

    /**
     * Update organization verification status
     */
    public function updateVerification(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $verification = OrganizationVerification::findOrFail($id);
        $verification->update([
            'status' => $request->status,
            'verified_by' => auth()->id(),
            'admin_notes' => $request->admin_notes,
            'verified_at' => now()
        ]);

        // Update organization verification status
        $organization = $verification->organization;
        if ($organization) {
            $organization->is_verified = ($request->status === 'approved');
            $organization->save();
        }

        return redirect()->back()->with('success', 'Verification status updated successfully!');
    }

    /**
     * Deactivate/activate user
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$status} successfully!");
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from deleting themselves
        if ($user->user_id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    /**
     * Display analytics page
     */
    public function analytics()
    {
        // Get data for charts
        $chartData = [
            'userGrowth' => $this->getUserGrowthData(),
            'eventStats' => $this->getEventStatsData(),
            'registrationTrends' => $this->getRegistrationTrendsData(),
            'topOrganizations' => $this->getTopOrganizationsData(),
        ];

        return view('admin.analytics', compact('chartData'));
    }

    private function getUserGrowthData()
    {
        $months = [];
        $volunteers = [];
        $organizations = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $volunteers[] = Volunteer::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $organizations[] = Organization::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return [
            'labels' => $months,
            'volunteers' => $volunteers,
            'organizations' => $organizations
        ];
    }

    private function getEventStatsData()
    {
        return [
            'open' => Event::where('status', 'open')->count(),
            'closed' => Event::where('status', 'closed')->count(),
            'cancelled' => Event::where('status', 'cancelled')->count(),
        ];
    }

    private function getRegistrationTrendsData()
    {
        $months = [];
        $registrations = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $registrations[] = EventRegistration::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return [
            'labels' => $months,
            'data' => $registrations
        ];
    }

    private function getTopOrganizationsData()
    {
        return Organization::withCount('events')
            ->orderBy('events_count', 'desc')
            ->take(5)
            ->get()
            ->map(function($org) {
                return [
                    'name' => $org->organization_name,
                    'events' => $org->events_count
                ];
            });
    }
}
