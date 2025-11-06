@extends('layouts.organization-app')

@section('title', 'Organization Dashboard - OneHelp')

@section('content')
<!-- Success Message -->
@if(session('success'))
<div style="background: #D1FAE5; border-left: 4px solid #10B981; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
    <svg style="width: 18px; height: 18px; color: #10B981; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p style="font-size: 13px; color: #065F46; margin: 0;">{{ session('success') }}</p>
</div>
@endif

<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-header">
        <div class="welcome-avatar">
            @if($organization->logo_image)
                <img src="{{ asset('storage/' . $organization->logo_image) }}" alt="Logo">
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            @endif
        </div>
        <div class="welcome-text">
            <h1>Welcome back, {{ $organization->org_name }}!</h1>
            <p>Here's your organization's impact at a glance.</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <!-- Total Events -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['totalEvents'] }}</h3>
                <p>Total Events</p>
            </div>
        </div>
    </div>

    <!-- Volunteers Mobilized -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-icon teal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['volunteersMobilized'] }}</h3>
                <p>Volunteers Mobilized</p>
            </div>
        </div>
    </div>

    <!-- Community Hours -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-icon gray">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['communityHours'] }}</h3>
                <p>Community Hours</p>
            </div>
        </div>
    </div>

    <!-- Completed Events -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-icon beige">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['completedEvents'] }}</h3>
                <p>Completed Events</p>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid (2 Columns) -->
<div class="content-grid">
    <!-- Organization Profile -->
    <div class="card">
        <h2 class="card-title">Organization Profile</h2>
        
        <h3 style="font-size: 16px; font-weight: 600; color: #2C3E50; margin-bottom: 12px;">About Us</h3>
        <p style="font-size: 14px; color: #4A5568; line-height: 1.6; margin-bottom: 20px;">
            {{ $organization->description ?? 'No description available.' }}
        </p>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <p style="font-size: 13px; font-weight: 600; color: #6B7280; margin-bottom: 4px;">Founded:</p>
                <p style="font-size: 14px; color: #2C3E50; font-weight: 500;">{{ $organization->founded_year ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="font-size: 13px; font-weight: 600; color: #6B7280; margin-bottom: 4px;">Location:</p>
                <p style="font-size: 14px; color: #2C3E50; font-weight: 500;">{{ $organization->address ?? 'N/A' }}</p>
            </div>
        </div>
        
        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #E5E7EB;">
            <p style="font-size: 13px; font-weight: 600; color: #6B7280; margin-bottom: 6px;">Rating:</p>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 20px; font-weight: 700; color: #2C3E50;">{{ number_format($organization->rating, 1) }}/5.0</span>
                <span style="color: #F59E0B; font-size: 16px;">⭐</span>
                <span style="font-size: 13px; color: #6B7280;">from volunteers</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <h2 class="card-title">Recent Activity</h2>
        
        @if($recentActivities->isEmpty())
        <div style="text-align: center; padding: 40px 20px;">
            <svg style="width: 60px; height: 60px; color: #D1D5DB; margin: 0 auto 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="font-size: 14px; color: #6B7280; font-weight: 500;">No recent activity</p>
        </div>
        @else
        <ul style="list-style: none; padding: 0; margin: 0;">
            @foreach($recentActivities as $activity)
            <li style="padding: 12px 0; border-bottom: 1px solid #F0F0F0; display: flex; gap: 10px;">
                <span style="color: {{ $activity['color'] ?? '#48BB78' }}; margin-top: 4px;">●</span>
                <div style="flex: 1;">
                    <p style="font-size: 13px; color: #2C3E50; margin-bottom: 3px; line-height: 1.4;">
                        {{ $activity['message'] }}
                    </p>
                    <span style="font-size: 11px; color: #9CA3AF;">{{ $activity['date'] }}</span>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>

<!-- Quick Actions & This Month's Impact Row -->
<div class="content-grid">
    <!-- Quick Actions -->
    <div class="card">
        <h2 class="card-title">Quick Actions</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
            <a href="{{ route('organization.events.create') }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; padding: 15px; background: #2C3E50; color: white; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Event
            </a>
            
            <a href="{{ route('organization.applications') }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; padding: 15px; background: white; color: #2C3E50; border: 2px solid #E5E7EB; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Review Applications
            </a>
        </div>
        
        @if($pendingCount > 0)
        <div style="background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 12px 16px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
            <svg style="width: 18px; height: 18px; color: #F59E0B; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="font-size: 13px; color: #92400E; margin: 0;">
                You have <strong>{{ $pendingCount }}</strong> pending {{ Str::plural('application', $pendingCount) }} to review.
            </p>
        </div>
        @endif
    </div>

    <!-- This Month's Impact -->
    <div class="card">
        <h2 class="card-title">This Month's Impact</h2>
        
        <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 14px; color: #4A5568;">Events Created</span>
                <span style="font-size: 18px; font-weight: 700; color: #2C3E50;">{{ $monthlyImpact['eventsCreated'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 14px; color: #4A5568;">New Volunteers</span>
                <span style="font-size: 18px; font-weight: 700; color: #2C3E50;">{{ $monthlyImpact['newVolunteers'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 14px; color: #4A5568;">Hours Organized</span>
                <span style="font-size: 18px; font-weight: 700; color: #2C3E50;">{{ $monthlyImpact['hoursOrganized'] }}</span>
            </div>
        </div>
        
        <div style="background: #7CB5B3; color: white; padding: 15px; border-radius: 10px; text-align: center;">
            <strong style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 3px;">Growing Impact!</strong>
            <p style="font-size: 12px; opacity: 0.95; margin: 0;">
                Your events have created lasting change in <strong>{{ $stats['volunteersMobilized'] }}</strong> lives this year.
            </p>
        </div>
    </div>
</div>
@endsection