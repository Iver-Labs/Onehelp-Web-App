<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Participation Report</title>
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
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 10px; font-weight: bold; }
        .badge-approved { background: #4CAF50; color: white; }
        .badge-pending { background: #FFC107; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">OneHelp</div>
        <h1>Event Participation Report</h1>
        <p>{{ now()->format('F d, Y') }}</p>
    </div>

    <h2>{{ $event->event_name }}</h2>
    <p><strong>Organization:</strong> {{ $event->organization->organization_name ?? 'N/A' }}</p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
    <p><strong>Location:</strong> {{ $event->location }}</p>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-number">{{ $stats['totalApplicants'] }}</div>
            <div>Total Applicants</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['approved'] }}</div>
            <div>Approved</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['pending'] }}</div>
            <div>Pending</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['spotsRemaining'] }}</div>
            <div>Spots Remaining</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Volunteer Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Applied Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $reg)
                <tr>
                    <td>{{ $reg->volunteer->first_name }} {{ $reg->volunteer->last_name }}</td>
                    <td>{{ $reg->volunteer->user->email ?? 'N/A' }}</td>
                    <td><span class="badge badge-{{ $reg->status }}">{{ ucfirst($reg->status) }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($reg->created_at)->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
