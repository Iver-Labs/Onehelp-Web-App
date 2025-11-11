@extends('layouts.volunteer-app')

@section('title', 'Browse Events - OneHelp')

@section('content')
<div class="welcome-section">
    <h1 style="font-size: 28px; font-weight: 600; color: #2C3E50; margin-bottom: 10px;">Browse Events</h1>
    <p style="font-size: 14px; color: #5A6C7D;">Find opportunities to make a difference in your community.</p>
</div>

<<<<<<< HEAD
<!-- Filter Bar -->
<div class="card" style="margin-bottom: 20px;">
    <form action="{{ route('volunteer.events') }}" method="GET">
        <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
            <div class="d-flex gap-2 flex-wrap">
                <select name="skill" class="form-select" style="width: auto;">
                    <option value="">All Categories</option>
                    @foreach($skills as $skill)
                        <option value="{{ $skill->skill_id }}" {{ request('skill') == $skill->skill_id ? 'selected' : '' }}>{{ $skill->skill_name }}</option>
                    @endforeach
                </select>
                <select name="location" class="form-select" style="width: auto;">
                    <option value="">All Locations</option>
                    @foreach($locations as $location)
                        <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                    @endforeach
                </select>
                <select name="sort" class="form-select" style="width: auto;">
                    <option value="">Sort By</option>
                    <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date (Oldest First)</option>
                    <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date (Newest First)</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <input type="search" name="search" class="form-control" placeholder="Search events..." value="{{ request('search') }}">
                <button type="submit" class="btn-primary">Filter</button>
=======
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
>>>>>>> bf643605715377de91547a4649aed863af0a426c
            </div>
        </div>
    </form>
</div>

<<<<<<< HEAD
<div class="content-grid event-grid">
    @if($events && $events->count() > 0)
        @foreach($events as $event)
            <div class="event-card">
                <div class="event-image">
                    @if($event->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $event->images->first()->image_path) }}" alt="Event Image">
                    @else
                        <img src="https://via.placeholder.com/300x200/{{ rand(0,1) == 0 ? '7DD3C0' : 'F4D58D' }}/1A4D5E?text=Event" alt="Event Image">
                    @endif
                </div>
                <div class="event-content">
                    <h3 class="event-title">{{ $event->event_name }}</h3>
                    <p class="event-org">Hosted by: {{ $event->organization->name }}</p>
                    <p class="event-description">{{ Str::limit($event->description, 100) }}</p>
                    <div class="event-meta">
                        <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                        <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</span>
                    </div>
                    <div class="event-tags">
                        @foreach($event->skills as $skill)
                            <span class="event-tag">{{ $skill->skill_name }}</span>
                        @endforeach
                    </div>
                    <a href="{{ route('events.show', $event->event_id) }}" class="btn-view-details">View Details</a>
                </div>
            </div>
        @endforeach
    @else
        <div class="card" style="grid-column: 1 / -1;">
            <div style="text-align: center; padding: 60px 20px;">
                <svg style="width: 80px; height: 80px; color: #D1D5DB; margin: 0 auto 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h2 style="font-size: 20px; font-weight: 600; color: #2C3E50; margin-bottom: 10px;">No Events Found</h2>
                <p style="font-size: 14px; color: #6B7280; margin-bottom: 20px;">There are currently no events matching your criteria.</p>
            </div>
        </div>
    @endif
</div>

<!-- Pagination -->
<div class="mt-5 d-flex justify-content-center">
    {{ $events->links() }}
</div>

@endsection

@push('styles')
<style>
    .d-flex {
        display: flex;
    }
    .flex-wrap {
        flex-wrap: wrap;
    }
    .gap-2 {
        gap: 0.5rem;
    }
    .gap-3 {
        gap: 1rem;
    }
    .justify-content-between {
        justify-content: space-between;
    }
    .align-items-center {
        align-items: center;
    }
    .mt-5 {
        margin-top: 3rem;
    }
    .justify-content-center {
        justify-content: center;
    }
    .form-select, .form-control {
        border: 2px solid #2C3E50;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-weight: 600;
    }

    .form-select:focus, .form-control:focus {
        border-color: #7CB5B3;
        box-shadow: 0 0 0 0.2rem rgba(125, 211, 192, 0.25);
    }
    .btn-primary {
        display: inline-block;
        padding: 10px 24px;
        background: #7CB5B3;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        text-align: center;
        border: none;
        cursor: pointer;
    }
    .event-grid {
        grid-template-columns: repeat(4, 1fr);
    }

    .event-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%; /* Ensure cards in a row have equal height */
        display: flex;
        flex-direction: column;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .event-image {
        width: 100%;
        height: 180px; /* Fixed height for images */
        overflow: hidden;
        background-color: #e0e0e0; /* Placeholder background */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .event-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .event-content {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .event-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2C3E50;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .event-org {
        font-size: 0.9rem;
        color: #7F8C8D;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .event-description {
        font-size: 0.95rem;
        color: #5A6C7D;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .event-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        font-size: 0.85rem;
        color: #7F8C8D;
    }

    .event-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .event-meta i {
        color: #7CB5B3; /* Primary teal color */
    }

    .event-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: auto; /* Pushes tags and button to the bottom */
        margin-bottom: 15px;
    }

    .event-tag {
        background-color: #e8f5e9; /* Light green */
        color: #28a745; /* Darker green */
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .btn-view-details {
        display: inline-block;
        width: 100%;
        padding: 10px 15px;
        background-color: #7CB5B3; /* Primary teal */
        color: #ffffff;
        text-align: center;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .btn-view-details:hover {
        background-color: #5a9a98; /* Darker teal */
    }

    /* Responsive adjustments for the grid */
    @media (max-width: 1200px) {
        .event-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .event-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Pagination Styles */
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination li a,
    .pagination li span {
        color: #2C3E50;
        border: 2px solid #2C3E50;
        margin: 0 0.25rem;
        border-radius: 10px;
        font-weight: 600;
        padding: 0.5rem 1rem;
        text-decoration: none;
    }

    .pagination li.active span {
        background-color: #7CB5B3;
        border-color: #2C3E50;
        color: #fff;
    }

    .pagination li a:hover {
        background-color: #EFD191;
        border-color: #2C3E50;
        color: #2C3E50;
    }

</style>
@endpush
=======
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
>>>>>>> bf643605715377de91547a4649aed863af0a426c
