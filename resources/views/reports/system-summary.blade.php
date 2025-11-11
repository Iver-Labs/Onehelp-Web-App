<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Summary Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2C7A6E; }
        .logo { font-size: 24px; color: #2C7A6E; font-weight: bold; }
        h1 { color: #2C7A6E; font-size: 18px; }
        .stats-grid { display: table; width: 100%; margin: 20px 0; }
        .stat-box { display: table-cell; padding: 10px; text-align: center; background: #f5f5f5; border: 1px solid #ddd; }
        .stat-number { font-size: 20px; font-weight: bold; color: #2C7A6E; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #2C7A6E; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">OneHelp</div>
        <h1>System Summary Report</h1>
        <p>{{ now()->format('F d, Y') }}</p>
    </div>

    <h2>System Overview</h2>
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-number">{{ $stats['totalUsers'] }}</div>
            <div>Total Users</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['totalVolunteers'] }}</div>
            <div>Volunteers</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['totalOrganizations'] }}</div>
            <div>Organizations</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['totalEvents'] }}</div>
            <div>Total Events</div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-number">{{ $stats['activeEvents'] }}</div>
            <div>Active Events</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['completedEvents'] }}</div>
            <div>Completed</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['approvedRegistrations'] }}</div>
            <div>Approved Reg.</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['totalHours'] }}</div>
            <div>Total Hours</div>
        </div>
    </div>

    <h3>Top Organizations</h3>
    <table>
        <thead>
            <tr>
                <th>Organization Name</th>
                <th>Events Hosted</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topOrganizations as $org)
                <tr>
                    <td>{{ $org->organization_name }}</td>
                    <td>{{ $org->events_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Recent Events</h3>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Organization</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentEvents as $event)
                <tr>
                    <td>{{ $event->event_name }}</td>
                    <td>{{ $event->organization->organization_name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                    <td>{{ ucfirst($event->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
