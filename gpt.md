
# Code Compilation

This file contains a compilation of the code snippets you requested.

## `app/Http/Controllers/VolunteerController.php`

```php
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
        
        // Get all applications (any status) for the applications table
        $applications = EventRegistration::where('volunteer_id', $volunteer->volunteer_id)
            ->with('event')
            ->orderBy('registered_at', 'desc')
            ->get();
        
        // Recent Activity - last 10 status changes and registrations
        $recentActivities = $this->getRecentActivities($volunteer->volunteer_id);
        
        return view('volunteer.dashboard', compact(
            'volunteer',
            'totalEvents',
            'completedEvents',
            'applications',
            'recentActivities'
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
        
        // Sort by date (newest first) - assuming dates are in the same format
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return collect($activities);
    }
    
    /**
     * Display the volunteer profile page
     */
    public function profile()
    {
        $user = Auth::user();
        $volunteer = $user->volunteer;

        if (!$volunteer) {
            return redirect()->route('home')->with('error', 'Volunteer profile not found.');
        }

        // Get all approved registrations to calculate stats
        $approvedRegistrations = EventRegistration::where('volunteer_id', $volunteer->volunteer_id)
            ->where('status', 'approved')
            ->with('event')
            ->get();

        // Calculate total hours from completed events
        $totalHours = $approvedRegistrations->filter(function ($registration) {
            return Carbon::parse($registration->event->event_date)->isPast();
        })->sum('hours_contributed');

        // Calculate completed events
        $completedEvents = $approvedRegistrations->filter(function ($registration) {
            return Carbon::parse($registration->event->event_date)->isPast();
        })->count();

        return view('volunteer.profile', compact('volunteer', 'totalHours', 'completedEvents'));
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
```

## `resources/views/layouts/volunteer-app.blade.php`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OneHelp - Volunteer Dashboard')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased" style="background-color: #B8D8D8;">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('components.volunteer-sidebar')
        
        <!-- Main Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
```

## `resources/views/volunteer/profile.blade.php`

```html
@extends('layouts.volunteer-app')

@section('title', 'My Profile - OneHelp')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="flex items-center gap-4">
            <img class="w-24 h-24 rounded-full border-4 border-white shadow-md" src="{{ $volunteer->profile_photo_path ?? 'https://via.placeholder.com/150' }}" alt="Profile Photo">
            <div>
                <h1 class="text-3xl font-bold text-text-dark">{{ $volunteer->first_name }} {{ $volunteer->last_name }}</h1>
                <p class="text-gray-600">Volunteer</p>
            </div>
        </div>
        <a href="#" class="mt-4 sm:mt-0 inline-flex items-center justify-center px-6 py-3 bg-accent-teal text-white font-semibold rounded-xl shadow-md hover:bg-accent-dark-teal transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L14.732 5.232z"></path>
            </svg>
            Edit Profile
        </a>
    </div>

    <!-- Profile Details Card -->
    <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-200">
        <h2 class="text-xl font-bold text-text-dark mb-6">Personal Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <!-- First Name -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">First Name</label>
                <p class="text-text-dark font-semibold">{{ $volunteer->first_name }}</p>
            </div>
            
            <!-- Last Name -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Last Name</label>
                <p class="text-text-dark font-semibold">{{ $volunteer->last_name }}</p>
            </div>
            
            <!-- Date of Birth -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Date of Birth</label>
                <p class="text-text-dark font-semibold">{{ \Carbon\Carbon::parse($volunteer->date_of_birth)->format('F j, Y') }}</p>
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                <p class="text-text-dark font-semibold">{{ $volunteer->phone_number ?? 'Not provided' }}</p>
            </div>

            <!-- Address -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                <p class="text-text-dark font-semibold">{{ $volunteer->address ?? 'Not provided' }}</p>
            </div>
            
            <!-- Bio -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500 mb-1">Bio</label>
                <p class="text-gray-600 italic">{{ $volunteer->bio ?? 'No bio yet.' }}</p>
            </div>
        </div>

        <!-- Divider -->
        <hr class="my-8 border-gray-200">

        <!-- Stats Section -->
        <div class="flex justify-around text-center">
            <div>
                <p class="text-2xl font-bold text-accent-teal">{{ $totalHours }}</p>
                <p class="text-sm text-gray-600 font-medium">Hours Contributed</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-accent-teal">{{ $completedEvents }}</p>
                <p class="text-sm text-gray-600 font-medium">Events Completed</p>
            </div>
        </div>
    </div>
</div>
@endsection
```

## `resources/views/components/volunteer-sidebar.blade.php`

```html
<aside class="w-64 min-h-screen p-6 flex flex-col" style="background-color: #F5E6D3;">
    <!-- Logo -->
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-6">
            <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: #7CC5C5;">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                </svg>
            </div>
            <span class="text-xl font-bold" style="color: #2C3E50;">OneHelp</span>
        </div>
        
        <!-- Volunteer Dashboard Button -->
        <a href="{{ route('volunteer.dashboard') }}" class="block w-full bg-white border-2 border-gray-300 rounded-lg px-4 py-2 text-sm font-medium hover:bg-gray-50 transition" style="color: #2C3E50;">
            <div class="flex items-center justify-between">
                <span>Volunteer Dashboard</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </a>
    </div>
    
    <!-- Logout Button -->
    <form method="POST" action="{{ route('logout') }}" class="mb-8">
        @csrf
        <button type="submit" class="w-full bg-white border-2 border-gray-300 rounded-lg px-4 py-2 text-sm font-medium hover:bg-red-50 hover:border-red-300 hover:text-red-600 transition flex items-center gap-2" style="color: #2C3E50;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
        </button>
    </form>
    
    <!-- Navigation Links -->
    <nav class="flex-1 space-y-2">
        <!-- View Events -->
        <a href="{{ route('volunteer.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('volunteer.dashboard') ? 'text-white' : 'hover:bg-white' }}"
           style="{{ request()->routeIs('volunteer.dashboard') ? 'background-color: #E8967C;' : 'color: #2C3E50;' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="font-medium">View Events</span>
        </a>
        
        <!-- Profile -->
        <a href="{{ route('volunteer.profile') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition relative {{ request()->routeIs('volunteer.profile') ? 'text-white' : 'hover:bg-white' }}"
           style="{{ request()->routeIs('volunteer.profile') ? 'background-color: #E8967C;' : 'color: #2C3E50;' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="font-medium">Profile</span>
            @if(auth()->user()->volunteer && !auth()->user()->volunteer->profile_image)
            <span class="absolute right-3 top-3 w-5 h-5 text-white text-xs rounded-full flex items-center justify-center font-bold" style="background-color: #7CC5C5;">1</span>
            @endif
        </a>
        
        <!-- Account -->
        <a href="{{ route('volunteer.account') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition relative {{ request()->routeIs('volunteer.account') ? 'text-white' : 'hover:bg-white' }}"
           style="{{ request()->routeIs('volunteer.account') ? 'background-color: #E8967C;' : 'color: #2C3E50;' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-medium">Account</span>
            @if(!auth()->user()->email_verified_at)
            <span class="absolute right-3 top-3 w-5 h-5 text-white text-xs rounded-full flex items-center justify-center font-bold" style="background-color: #7CC5C5;">1</span>
            @endif
        </a>
        
        <!-- Messages -->
        <a href="{{ route('volunteer.messages') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition relative {{ request()->routeIs('volunteer.messages') ? 'text-white' : 'hover:bg-white' }}"
           style="{{ request()->routeIs('volunteer.messages') ? 'background-color: #E8967C;' : 'color: #2C3E50;' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="font-medium">Messages</span>
            <span class="absolute right-3 top-3 w-5 h-5 text-white text-xs rounded-full flex items-center justify-center font-bold" style="background-color: #7CC5C5;">1</span>
        </a>
    </nav>
</aside>
```

**Note:** I was unable to find the `tailwind.config.js` file in your project. The code snippets above are from the files that were successfully read.
