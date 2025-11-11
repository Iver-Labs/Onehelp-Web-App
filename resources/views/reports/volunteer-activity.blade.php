<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Volunteer Activity Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2C7A6E;
        }
        .logo {
            font-size: 24px;
            color: #2C7A6E;
            font-weight: bold;
        }
        h1 {
            color: #2C7A6E;
            font-size: 18px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            background: #f5f5f5;
            border: 1px solid #ddd;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2C7A6E;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #2C7A6E;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-approved { background: #4CAF50; color: white; }
        .badge-pending { background: #FFC107; color: black; }
        .badge-rejected { background: #F44336; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">OneHelp</div>
        <h1>Volunteer Activity Report</h1>
        <p>Generated on {{ now()->format('F d, Y') }}</p>
    </div>

    <div class="info-section">
        <div><span class="info-label">Volunteer Name:</span> {{ $volunteer->first_name }} {{ $volunteer->last_name }}</div>
        <div><span class="info-label">Email:</span> {{ $volunteer->user->email ?? 'N/A' }}</div>
        <div><span class="info-label">Phone:</span> {{ $volunteer->phone ?? 'N/A' }}</div>
        <div><span class="info-label">Location:</span> {{ $volunteer->location ?? 'N/A' }}</div>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-number">{{ $stats['totalEvents'] }}</div>
            <div class="stat-label">Total Events</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['completedEvents'] }}</div>
            <div class="stat-label">Completed Events</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['upcomingEvents'] }}</div>
            <div class="stat-label">Upcoming Events</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['totalHours'] }}</div>
            <div class="stat-label">Total Hours</div>
        </div>
    </div>

    <h2 style="color: #2C7A6E; font-size: 16px; margin-top: 30px;">Event Participation History</h2>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Organization</th>
                <th>Date</th>
                <th>Status</th>
                <th>Hours</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $registration)
                <tr>
                    <td>{{ $registration->event->event_name }}</td>
                    <td>{{ $registration->event->organization->organization_name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($registration->event->event_date)->format('M d, Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $registration->status }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </td>
                    <td>{{ $registration->hours_contributed ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #999;">No events found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>OneHelp - Connecting Hearts to Communities</p>
        <p>This is an auto-generated report. For more information, visit our website.</p>
    </div>
</body>
</html>
