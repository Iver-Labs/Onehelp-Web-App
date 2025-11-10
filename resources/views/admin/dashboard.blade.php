@extends('layouts.admin-app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
            <h3>{{ $stats['totalUsers'] }}</h3>
            <p class="text-muted mb-0">Total Users</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-building"></i>
            </div>
            <h3>{{ $stats['totalOrganizations'] }}</h3>
            <p class="text-muted mb-0">Organizations</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-hands-helping"></i>
            </div>
            <h3>{{ $stats['totalVolunteers'] }}</h3>
            <p class="text-muted mb-0">Volunteers</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3>{{ $stats['totalEvents'] }}</h3>
            <p class="text-muted mb-0">Total Events</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon teal">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3>{{ $stats['activeEvents'] }}</h3>
            <p class="text-muted mb-0">Active Events</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-double"></i>
            </div>
            <h3>{{ $stats['completedEvents'] }}</h3>
            <p class="text-muted mb-0">Completed Events</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-clock"></i>
            </div>
            <h3>{{ $stats['pendingVerifications'] }}</h3>
            <p class="text-muted mb-0">Pending Verifications</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-user-check"></i>
            </div>
            <h3>{{ $stats['approvedRegistrations'] }}</h3>
            <p class="text-muted mb-0">Approved Registrations</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>This Month</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span><i class="fas fa-user-plus text-primary"></i> New Users</span>
                    <strong>{{ $monthlyStats['newUsers'] }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span><i class="fas fa-calendar-plus text-success"></i> New Events</span>
                    <strong>{{ $monthlyStats['newEvents'] }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-clipboard-check text-info"></i> Registrations</span>
                    <strong>{{ $monthlyStats['newRegistrations'] }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Recent Activity</h5>
                <a href="{{ route('reports.system.summary') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-file-pdf"></i> Generate Report
                </a>
            </div>
            <div class="card-body">
                @if($recentActivities->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentActivities as $activity)
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <i class="fas fa-circle fa-xs me-2" style="color: {{ $activity['type'] === 'user' ? '#1976D2' : '#388E3C' }}"></i>
                                        {{ $activity['message'] }}
                                    </div>
                                    <small class="text-muted">{{ $activity['date'] }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>No recent activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-download me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('reports.export.users') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-file-excel"></i> Export Users
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('reports.export.events') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-file-excel"></i> Export Events
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.verifications') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-check-circle"></i> Review Verifications
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.analytics') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-chart-bar"></i> View Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
