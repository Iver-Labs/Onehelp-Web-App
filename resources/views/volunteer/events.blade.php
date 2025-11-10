@extends('layouts.volunteer-app')

@section('title', 'Browse Events - OneHelp')

@section('content')
<div class="welcome-section">
    <h1 style="font-size: 28px; font-weight: 600; color: #2C3E50; margin-bottom: 10px;">Browse Events</h1>
    <p style="font-size: 14px; color: #5A6C7D;">Find opportunities to make a difference in your community.</p>
</div>

<!-- Filter and Search -->
<div class="card" style="margin-top: 20px; padding: 20px;">
    <form method="GET" action="{{ route('volunteer.events') }}">
        <div class="row g-3">
            <div class="col-md-4">
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <option value="Environment" {{ request('category') == 'Environment' ? 'selected' : '' }}>Environment</option>
                    <option value="Education" {{ request('category') == 'Education' ? 'selected' : '' }}>Education</option>
                    <option value="Healthcare" {{ request('category') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                    <option value="Community" {{ request('category') == 'Community' ? 'selected' : '' }}>Community</option>
                    <option value="Animals" {{ request('category') == 'Animals' ? 'selected' : '' }}>Animals</option>
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
</div>

<!-- Events Grid -->
<div class="row" style="margin-top: 30px;">
    @forelse($events as $event)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($event->primaryImage)
                    <img src="{{ asset($event->primaryImage->image_url) }}" class="card-img-top" alt="{{ $event->event_name }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-calendar-alt fa-4x text-white"></i>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        @if($event->category)
                            <span class="badge bg-info">{{ $event->category }}</span>
                        @endif
                        <span class="badge bg-{{ $event->status === 'open' ? 'success' : 'secondary' }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                    
                    <h5 class="card-title">{{ $event->event_name }}</h5>
                    
                    <p class="card-text text-muted small">
                        <i class="fas fa-building me-1"></i> {{ $event->organization->organization_name ?? 'N/A' }}
                    </p>
                    
                    <p class="card-text small">
                        <i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}<br>
                        <i class="fas fa-clock me-1"></i> {{ $event->start_time }} - {{ $event->end_time }}<br>
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $event->location }}
                    </p>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Volunteers</small>
                            <small><strong>{{ $event->registered_count }}/{{ $event->max_volunteers }}</strong></small>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $event->max_volunteers > 0 ? ($event->registered_count / $event->max_volunteers * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-primary w-100">
                            View Details & Apply
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h2 style="font-size: 20px; font-weight: 600; color: #2C3E50; margin-bottom: 10px;">No Events Found</h2>
                    <p style="font-size: 14px; color: #6B7280;">Try adjusting your search or filters.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($events->hasPages())
    <div class="mt-4">
        {{ $events->links() }}
    </div>
@endif
@endsection