@extends('layouts.organization-app')

@section('title', 'Manage Applications - OneHelp')

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <h1 style="font-size: 28px; font-weight: 600; color: #2C3E50; margin-bottom: 10px;">Manage Applications</h1>
    <p style="font-size: 14px; color: #5A6C7D;">Review and manage volunteer applications for your events.</p>
</div>

<!-- Success Message -->
@if(session('success'))
<div style="background: #D1FAE5; color: #065F46; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
    ✓ {{ session('success') }}
</div>
@endif

<!-- Applications Card -->
<div class="card" style="padding: 0;">
    @if($applications->isEmpty())
    <div style="padding: 60px 20px; text-align: center;">
        <svg style="width: 80px; height: 80px; color: #D1D5DB; margin: 0 auto 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <h2 style="font-size: 20px; font-weight: 600; color: #2C3E50; margin-bottom: 10px;">No Applications Yet</h2>
        <p style="font-size: 14px; color: #6B7280;">Applications from volunteers will appear here.</p>
    </div>
    @else
    <!-- Filters -->
    <div style="padding: 20px 25px; border-bottom: 2px solid #E5E7EB;">
        <div style="display: flex; gap: 10px;">
            <button onclick="filterApplications('all')" class="filter-btn active" data-filter="all" style="padding: 8px 16px; background: #2C3E50; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                All ({{ $applications->count() }})
            </button>
            <button onclick="filterApplications('pending')" class="filter-btn" data-filter="pending" style="padding: 8px 16px; background: white; color: #2C3E50; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Pending ({{ $applications->where('status', 'pending')->count() }})
            </button>
            <button onclick="filterApplications('approved')" class="filter-btn" data-filter="approved" style="padding: 8px 16px; background: white; color: #2C3E50; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Approved ({{ $applications->where('status', 'approved')->count() }})
            </button>
            <button onclick="filterApplications('rejected')" class="filter-btn" data-filter="rejected" style="padding: 8px 16px; background: white; color: #2C3E50; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Rejected ({{ $applications->where('status', 'rejected')->count() }})
            </button>
        </div>
    </div>

    <!-- Applications Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #F9FAFB; border-bottom: 2px solid #E5E7EB;">
                <tr>
                    <th style="text-align: left; padding: 15px 25px; font-size: 13px; font-weight: 600; color: #4A5568;">Volunteer</th>
                    <th style="text-align: left; padding: 15px 25px; font-size: 13px; font-weight: 600; color: #4A5568;">Event</th>
                    <th style="text-align: left; padding: 15px 25px; font-size: 13px; font-weight: 600; color: #4A5568;">Applied Date</th>
                    <th style="text-align: left; padding: 15px 25px; font-size: 13px; font-weight: 600; color: #4A5568;">Status</th>
                    <th style="text-align: left; padding: 15px 25px; font-size: 13px; font-weight: 600; color: #4A5568;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr class="application-row" data-status="{{ $application->status }}" style="border-bottom: 1px solid #F0F0F0; transition: background 0.2s;">
                    <td style="padding: 15px 25px;">
                        <div>
                            <p style="font-size: 14px; font-weight: 600; color: #2C3E50; margin-bottom: 2px;">
                                {{ $application->volunteer->first_name }} {{ $application->volunteer->last_name }}
                            </p>
                            <p style="font-size: 12px; color: #6B7280;">{{ $application->volunteer->user->email }}</p>
                        </div>
                    </td>
                    <td style="padding: 15px 25px;">
                        <p style="font-size: 14px; color: #2C3E50;">{{ $application->event->event_name }}</p>
                    </td>
                    <td style="padding: 15px 25px;">
                        <p style="font-size: 14px; color: #6B7280;">{{ \Carbon\Carbon::parse($application->created_at)->format('M d, Y') }}</p>
                    </td>
                    <td style="padding: 15px 25px;">
                        @if($application->status === 'approved')
                        <span style="display: inline-block; padding: 6px 12px; background: #D1FAE5; color: #065F46; border-radius: 12px; font-size: 12px; font-weight: 600;">Approved</span>
                        @elseif($application->status === 'pending')
                        <span style="display: inline-block; padding: 6px 12px; background: #FEF3C7; color: #92400E; border-radius: 12px; font-size: 12px; font-weight: 600;">Pending</span>
                        @else
                        <span style="display: inline-block; padding: 6px 12px; background: #FEE2E2; color: #991B1B; border-radius: 12px; font-size: 12px; font-weight: 600;">Rejected</span>
                        @endif
                    </td>
                    <td style="padding: 15px 25px;">
                        <div style="display: flex; gap: 8px;">
                            @if($application->status === 'pending')
                            <form method="POST" action="{{ route('organization.applications.update', $application->registration_id) }}" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" style="padding: 6px 12px; background: #10B981; color: white; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('organization.applications.update', $application->registration_id) }}" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" style="padding: 6px 12px; background: #EF4444; color: white; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    Reject
                                </button>
                            </form>
                            @else
                            <span style="font-size: 12px; color: #9CA3AF;">No actions</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script nonce="{{ $cspNonce ?? '' }}">
function filterApplications(status) {
    const rows = document.querySelectorAll('.application-row');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button styles
    buttons.forEach(btn => {
        if (btn.dataset.filter === status) {
            btn.style.background = '#2C3E50';
            btn.style.color = 'white';
            btn.classList.add('active');
        } else {
            btn.style.background = 'white';
            btn.style.color = '#2C3E50';
            btn.classList.remove('active');
        }
    });
    
    // Filter rows
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection