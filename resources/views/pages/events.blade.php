@extends('layouts.app')

@section('title', 'Browse Events - OneHelp')

@section('content')
<!-- Events Hero -->
<section class="hero-section" style="background: linear-gradient(rgba(28, 77, 94, 0.6), rgba(28, 77, 94, 0.6)), url('{{ asset('images/community_photo.jpg') }}') center/cover; min-height: 400px;">
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
        <div class="d-flex flex-wrap justify-content-center gap-4">
            @forelse ($events as $event)
                <div class="event-card shadow-sm"
                    style="flex: 1 1 calc(33.333% - 2rem); max-width: 360px; min-width: 280px;">
                <div class="event-image">
                    @if ($event->images && $event->images->isNotEmpty())
                    <img src="{{ asset($event->images->first()->image_url) }}" 
                        alt="{{ $event->event_name }}">
                    @else
                    <img src="{{ asset('images/community_photo.jpg') }}" 
                        alt="{{ $event->event_name }}">
                    @endif
                </div>

                <div class="event-content p-3 text-center">
                    <h5 class="fw-bold">{{ $event->event_name }}</h5>
                    <p class="text-muted small mb-1">{{ $event->organization->org_name ?? 'Community Partner' }}</p>
                    <p class="text-muted small mb-2">
                    <i class="far fa-calendar"></i> 
                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }} <br>
                    <i class="far fa-clock"></i> 
                    {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                    </p>
                    <p class="small">{{ Str::limit($event->description, 100) }}</p>
                    <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-warning w-100 fw-semibold mt-3">
                    View Details
                    </a>
                </div>
                </div>
            @empty
                <p class="text-center text-muted fs-5">No events available at the moment.</p>
            @endforelse
            </div>

        <!-- Pagination -->
        <div class="mt-5 d-flex justify-content-center">
            {{ $events->links() }}
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

    .event-card {
    background: #EAF2F8;
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .event-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    }
</style>
@endpush