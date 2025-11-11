<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Organization Summary Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2C7A6E; }
        .logo { font-size: 24px; color: #2C7A6E; font-weight: bold; }
        h1 { color: #2C7A6E; font-size: 18px; }
        .stats-grid { display: table; width: 100%; margin: 20px 0; }
        .stat-box { display: table-cell; padding: 15px; text-align: center; background: #f5f5f5; border: 1px solid #ddd; }
        .stat-number { font-size: 24px; font-weight: bold; color: #2C7A6E; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #2C7A6E; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">OneHelp</div>
        <h1>Organization Summary Report</h1>
        <p>{{ now()->format('F d, Y') }}</p>
    </div>

    <h2>{{ $organization->organization_name }}</h2>
    <p><strong>Email:</strong> {{ $organization->user->email ?? 'N/A' }}</p>
    <p><strong>Type:</strong> {{ $organization->organization_type ?? 'N/A' }}</p>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-number">{{ $stats['totalEvents'] }}</div>
            <div>Total Events</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['upcomingEvents'] }}</div>
            <div>Upcoming Events</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['totalVolunteers'] }}</div>
            <div>Total Volunteers</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['totalHours'] }}</div>
            <div>Total Hours</div>
        </div>
    </div>

    <h3>Recent Events</h3>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Volunteers</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events->take(10) as $event)
                <tr>
                    <td>{{ $event->event_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                    <td>{{ $event->location }}</td>
                    <td>{{ $event->registered_count }}/{{ $event->max_volunteers }}</td>
                    <td>{{ ucfirst($event->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
