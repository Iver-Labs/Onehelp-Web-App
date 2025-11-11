@extends('layouts.organization-app')

@section('title', 'Analytics & Impact - OneHelp')

@section('content')
<div class="welcome-section">
    <h1 style="font-size: 28px; font-weight: 600; color: #2C3E50; margin-bottom: 10px;">Analytics & Impact</h1>
    <p style="font-size: 14px; color: #5A6C7D;">Track your organization's performance and impact metrics.</p>
</div>

<!-- Quick Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                <h3 class="mb-0">{{ $stats['totalEvents'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Events</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x text-success mb-2"></i>
                <h3 class="mb-0">{{ $stats['totalVolunteers'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Volunteers</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                <h3 class="mb-0">{{ $stats['totalHours'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Hours</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-star fa-2x text-info mb-2"></i>
                <h3 class="mb-0">{{ number_format($stats['avgRating'] ?? 0, 1) }}</h3>
                <p class="text-muted mb-0">Avg Rating</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Event Trend (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <canvas id="eventTrendChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Event Status Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="eventStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Volunteer Registrations</h5>
            </div>
            <div class="card-body">
                <canvas id="registrationChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-download me-2"></i>Export Reports</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('reports.organization.summary', $organization->organization_id) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-file-pdf me-2"></i>Download Organization Summary (PDF)
                    </a>
                    <a href="{{ route('reports.export.events') }}" 
                       class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Export Events List (Excel)
                    </a>
                    <a href="{{ route('reports.export.registrations') }}" 
                       class="btn btn-info">
                        <i class="fas fa-file-excel me-2"></i>Export All Registrations (Excel)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Events Performance -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Recent Events Performance</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Volunteers</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEvents ?? [] as $event)
                        <tr>
                            <td>{{ $event->event_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                            <td>{{ $event->registered_count }}/{{ $event->max_volunteers }}</td>
                            <td>
                                <span class="badge bg-{{ $event->status === 'open' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('reports.event.participation', $event->event_id) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-file-pdf"></i> Report
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No events found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script nonce="{{ $cspNonce ?? '' }}">
    // Event Trend Chart
    const eventTrendCtx = document.getElementById('eventTrendChart').getContext('2d');
    new Chart(eventTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['months'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
            datasets: [{
                label: 'Events Created',
                data: {!! json_encode($chartData['eventCounts'] ?? [2, 5, 3, 7, 4, 6]) !!},
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Event Status Chart
    const eventStatusCtx = document.getElementById('eventStatusChart').getContext('2d');
    new Chart(eventStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Open', 'Closed', 'Cancelled'],
            datasets: [{
                data: [
                    {{ $chartData['statusCounts']['open'] ?? 0 }},
                    {{ $chartData['statusCounts']['closed'] ?? 0 }},
                    {{ $chartData['statusCounts']['cancelled'] ?? 0 }}
                ],
                backgroundColor: ['#4CAF50', '#9E9E9E', '#F44336']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Registration Chart
    const registrationCtx = document.getElementById('registrationChart').getContext('2d');
    new Chart(registrationCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['months'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
            datasets: [{
                label: 'Registrations',
                data: {!! json_encode($chartData['registrationCounts'] ?? [15, 25, 18, 32, 22, 28]) !!},
                backgroundColor: '#2196F3'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
