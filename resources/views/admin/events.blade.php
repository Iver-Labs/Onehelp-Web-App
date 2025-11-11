@extends('layouts.admin-app')

@section('title', 'Manage Events')
@section('page-title', 'Event Management')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>All Events</h5>
        <a href="{{ route('reports.export.events') }}" class="btn btn-sm btn-success">
            <i class="fas fa-file-excel"></i> Export to Excel
        </a>
    </div>
    <div class="card-body">
        <!-- Filter and Search -->
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search events..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>

        <!-- Events Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Event Name</th>
                        <th>Organization</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Volunteers</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $event->event_id }}</td>
                            <td>{{ $event->event_name }}</td>
                            <td>{{ $event->organization->organization_name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                            <td>{{ $event->location }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $event->registered_count }}/{{ $event->max_volunteers }}
                                </span>
                            </td>
                            <td>
                                @if($event->status === 'open')
                                    <span class="badge bg-success">Open</span>
                                @elseif($event->status === 'closed')
                                    <span class="badge bg-secondary">Closed</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('reports.event.participation', $event->event_id) }}" 
                                       class="btn btn-info" 
                                       title="Generate Report">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <button type="button" class="btn btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#eventModal{{ $event->event_id }}"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal for event details -->
                        <div class="modal fade" id="eventModal{{ $event->event_id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ $event->event_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Organization:</strong> {{ $event->organization->organization_name ?? 'N/A' }}</p>
                                                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                                                <p><strong>Time:</strong> {{ $event->start_time }} - {{ $event->end_time }}</p>
                                                <p><strong>Location:</strong> {{ $event->location }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Category:</strong> {{ $event->category ?? 'N/A' }}</p>
                                                <p><strong>Max Volunteers:</strong> {{ $event->max_volunteers }}</p>
                                                <p><strong>Registered:</strong> {{ $event->registered_count }}</p>
                                                <p><strong>Status:</strong> {{ ucfirst($event->status) }}</p>
                                            </div>
                                        </div>
                                        @if($event->description)
                                            <hr>
                                            <p><strong>Description:</strong></p>
                                            <p>{{ $event->description }}</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p>No events found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection
