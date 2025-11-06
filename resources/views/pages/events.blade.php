@extends('layouts.app')

@section('title', 'Browse Events - OneHelp')

@section('content')
<!-- Events Hero -->
<section class="hero-section" style="min-height: 400px;">
    <div class="hero-content">
        <h1>Volunteer Events</h1>
        <p>Find opportunities to make a difference in your community</p>
    </div>
</section>

<!-- Events Filters & Grid -->
<section style="padding: 4rem 0; background: #f8f9fa;">
    <div class="container">
        <!-- Filter Bar -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
                    <div class="d-flex gap-2 flex-wrap">
                        <select class="form-select" style="width: auto;">
                            <option>All Categories</option>
                            <option>Environment</option>
                            <option>Education</option>
                            <option>Community</option>
                            <option>Health</option>
                        </select>
                        <select class="form-select" style="width: auto;">
                            <option>All Locations</option>
                            <option>Manila</option>
                            <option>Quezon City</option>
                            <option>Makati</option>
                            <option>Pasig</option>
                        </select>
                        <input type="date" class="form-control" style="width: auto;">
                    </div>
                    <div>
                        <input type="search" class="form-control" placeholder="Search events...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="row g-4">
            @forelse ($events as $event)
            <div class="col-md-6 col-lg-3">
                <div class="event-card">
                    <div class="event-image">
                        @if($event->images->isNotEmpty())
                            <img src="{{ asset($event->images->first()->image_url) }}" alt="{{ $event->event_name }}">
                        @else
                            <img src="https://via.placeholder.com/300x200/7DD3C0/1A4D5E?text=Event" alt="{{ $event->event_name }}">
                        @endif
                    </div>
                    <div class="event-content">
                        <h3 class="event-title">{{ Str::limit($event->event_name, 50) }}</h3>
                        <p class="event-org">{{ $event->organization->org_name ?? 'Community Partner' }}</p>
                        <p class="event-description">{{ Str::limit(explode("\n", $event->description)[0], 100) }}</p>
                        <div class="event-meta">
                            <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                            <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}</span>
                        </div>
                        <div class="event-tags">
                            <span class="event-tag">{{ $event->category }}</span>
                            <span class="event-tag">{{ $event->registered_count }}/{{ $event->max_volunteers }} slots</span>
                        </div>
                        <a href="{{ route('events.show', $event->event_id) }}" class="btn-view-details">View Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>No events available at the moment.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-5 d-flex justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .form-select, .form-control {
        border: 2px solid var(--navy);
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-weight: 600;
    }

    .form-select:focus, .form-control:focus {
        border-color: var(--primary-teal);
        box-shadow: 0 0 0 0.2rem rgba(125, 211, 192, 0.25);
    }

    .pagination .page-link {
        color: var(--navy);
        border: 2px solid var(--navy);
        margin: 0 0.25rem;
        border-radius: 10px;
        font-weight: 600;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary-teal);
        border-color: var(--navy);
        color: var(--navy);
    }

    .pagination .page-link:hover {
        background-color: var(--accent-yellow);
        border-color: var(--navy);
        color: var(--navy);
    }
</style>
@endpush