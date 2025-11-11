@extends('layouts.admin-app')

@section('title', 'System Analytics')
@section('page-title', 'System Analytics')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>User Growth Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Event Status Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="eventStatsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Registration Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="registrationTrendsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Top Organizations by Events</h5>
            </div>
            <div class="card-body">
                <canvas id="topOrganizationsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-download me-2"></i>Export Analytics</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('reports.system.summary') }}" class="btn btn-primary w-100">
                    <i class="fas fa-file-pdf"></i> Download System Report (PDF)
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('reports.export.users') }}" class="btn btn-success w-100">
                    <i class="fas fa-file-excel"></i> Export Users (Excel)
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('reports.export.events') }}" class="btn btn-info w-100">
                    <i class="fas fa-file-excel"></i> Export Events (Excel)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['userGrowth']['labels']) !!},
            datasets: [
                {
                    label: 'Volunteers',
                    data: {!! json_encode($chartData['userGrowth']['volunteers']) !!},
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Organizations',
                    data: {!! json_encode($chartData['userGrowth']['organizations']) !!},
                    borderColor: '#2196F3',
                    backgroundColor: 'rgba(33, 150, 243, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Event Stats Chart
    const eventStatsCtx = document.getElementById('eventStatsChart').getContext('2d');
    new Chart(eventStatsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Open', 'Closed', 'Cancelled'],
            datasets: [{
                data: [
                    {{ $chartData['eventStats']['open'] }},
                    {{ $chartData['eventStats']['closed'] }},
                    {{ $chartData['eventStats']['cancelled'] }}
                ],
                backgroundColor: [
                    '#4CAF50',
                    '#9E9E9E',
                    '#F44336'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Registration Trends Chart
    const registrationTrendsCtx = document.getElementById('registrationTrendsChart').getContext('2d');
    new Chart(registrationTrendsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['registrationTrends']['labels']) !!},
            datasets: [{
                label: 'Registrations',
                data: {!! json_encode($chartData['registrationTrends']['data']) !!},
                backgroundColor: '#2196F3'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Top Organizations Chart
    const topOrgsCtx = document.getElementById('topOrganizationsChart').getContext('2d');
    new Chart(topOrgsCtx, {
        type: 'horizontalBar',
        data: {
            labels: {!! json_encode($chartData['topOrganizations']->pluck('name')) !!},
            datasets: [{
                label: 'Events Hosted',
                data: {!! json_encode($chartData['topOrganizations']->pluck('events')) !!},
                backgroundColor: '#FF9800'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
