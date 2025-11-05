@extends('layouts.volunteer-app')

@section('title', 'Volunteer Dashboard - OneHelp')

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-header">
        <div class="welcome-avatar">
            <!-- PLACEHOLDER: User avatar image -->
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div class="welcome-text">
            <h1>Welcome back, [username]!</h1>
            <p>See the difference you're making.</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <!-- Total Events -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-icon blue">
                <!-- PLACEHOLDER: Calendar/Document icon -->
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $totalEvents ?? 4 }}</h3>
                <p>Total Events</p>
            </div>
        </div>
    </div>

    <!-- Volunteers Mobilized -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-icon teal">
                <!-- PLACEHOLDER: Users icon -->
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $volunteersMobilized ?? 87 }}</h3>
                <p>Volunteers Mobilized</p>
            </div>
        </div>
    </div>

    <!-- Community Hours -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-icon gray">
                <!-- PLACEHOLDER: Clock icon -->
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $communityHours ?? 45 }}</h3>
                <p>Community Hours</p>
            </div>
        </div>
    </div>

    <!-- Completed Events -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-icon beige">
                <!-- PLACEHOLDER: Check circle icon -->
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $completedEvents ?? 2 }}</h3>
                <p>Completed Events</p>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid (2 Columns) -->
<div class="content-grid">
    <!-- This Month's Impact -->
    <div class="card">
        <h2 class="card-title">This Month's Impact</h2>
        <ul class="impact-list">
            <li class="impact-item">
                <span class="impact-label">Total Events Joined</span>
                <span class="impact-value">{{ $monthlyImpact['events'] ?? 4 }}</span>
            </li>
            <li class="impact-item">
                <span class="impact-label">Hours Volunteered</span>
                <span class="impact-value">{{ $monthlyImpact['hours'] ?? 23 }}</span>
            </li>
            <li class="impact-item">
                <span class="impact-label">Upcoming Events</span>
                <span class="impact-value">{{ $monthlyImpact['upcoming'] ?? 2 }}</span>
            </li>
            <li class="impact-item">
                <span class="impact-label">Certificate Earned</span>
                <span class="impact-value">{{ $monthlyImpact['certificates'] ?? 4 }}</span>
            </li>
            <li class="impact-item">
                <span class="impact-label">Badges Earned</span>
                <span class="impact-value">{{ $monthlyImpact['badges'] ?? 4 }}</span>
            </li>
        </ul>
        <div class="impact-banner">
            <strong>Growing Impact!</strong>
            <p>Your events have created lasting change in {{ $monthlyImpact['mobilized'] ?? 87 }} lives this year.</p>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <h2 class="card-title">Recent Activity</h2>
        <ul class="activity-list">
            @if($recentActivities && $recentActivities->count() > 0)
                @foreach($recentActivities as $activity)
                <li class="activity-item">
                    <span class="activity-dot {{ $activity['type'] === 'approved' ? 'green' : ($activity['type'] === 'pending' ? 'yellow' : 'blue') }}"></span>
                    <div class="activity-content">
                        <p>{{ $activity['message'] }}</p>
                        <span class="activity-date">{{ $activity['date'] }}</span>
                    </div>
                </li>
                @endforeach
            @else
            <li class="activity-item">
                <span class="activity-dot green"></span>
                <div class="activity-content">
                    <p>Application Approved for Urban Restoration and Eco-Awareness Drive</p>
                    <span class="activity-date">10/24/2025</span>
                </div>
            </li>
            <li class="activity-item">
                <span class="activity-dot green"></span>
                <div class="activity-content">
                    <p>Application Approved for Weekend Literacy Program</p>
                    <span class="activity-date">10/23/2025</span>
                </div>
            </li>
            <li class="activity-item">
                <span class="activity-dot yellow"></span>
                <div class="activity-content">
                    <p>Application Pending: Organic Farming Workshop</p>
                    <span class="activity-date">10/20/2024</span>
                </div>
            </li>
            <li class="activity-item">
                <span class="activity-dot blue"></span>
                <div class="activity-content">
                    <p>New message from Lea Santos about photography exhibition</p>
                    <span class="activity-date">10/16/2024</span>
                </div>
            </li>
            <li class="activity-item">
                <span class="activity-dot green"></span>
                <div class="activity-content">
                    <p>Tree Planting at La Mesa completed successfully</p>
                    <span class="activity-date">10/15/2024</span>
                </div>
            </li>
            @endif
        </ul>
    </div>
</div>

<!-- My History (Full Width) -->
<div class="card history-card">
    <h2 class="card-title">My History</h2>
    @if($applications && $applications->count() > 0)
    <table class="history-table">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $application)
            <tr>
                <td>{{ $application->event->event_name }}</td>
                <td>{{ \Carbon\Carbon::parse($application->event->event_date)->format('m/d/Y') }}</td>
                <td>{{ $application->hours_contributed ?? '2' }} hrs</td>
                <td>
                    @if($application->status === 'approved')
                    <span class="status-badge completed">Completed</span>
                    @elseif($application->status === 'pending')
                    <span class="status-badge pending">Pending</span>
                    @else
                    <span class="status-badge approved">{{ ucfirst($application->status) }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <table class="history-table">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tree Planting</td>
                <td>10/15/2024</td>
                <td>2 hrs</td>
                <td><span class="status-badge completed">Completed</span></td>
            </tr>
        </tbody>
    </table>
    @endif
</div>
@endsection