@extends('layouts.app')
@section('title', $event->event_name)

@section('content')
<!-- Event Hero Section -->
<section class="hero-section" style="min-height: 300px;">
    <div class="hero-content">
        <h1>{{ $event->event_name }}</h1>
        <p>{{ $event->organization->org_name ?? 'Community Partner' }}</p>
    </div>
</section>

<!-- Event Details Section -->
<section style="padding: 4rem 0; background: #f8f9fa;">
    <div class="container">
        <div class="row g-4">
            <!-- Event Image -->
            <div class="col-lg-5">
                <div class="event-detail-image">
                    @if($event->images->isNotEmpty())
                        <img src="{{ asset($event->images->first()->image_url) }}" 
                             alt="{{ $event->event_name }}" 
                             class="img-fluid rounded">
                    @else
                        <img src="https://via.placeholder.com/600x400/7DD3C0/1A4D5E?text=Event" 
                             alt="{{ $event->event_name }}" 
                             class="img-fluid rounded">
                    @endif
                </div>

                <!-- Event Meta Card -->
                <div class="event-meta-card mt-4">
                    <h4>Event Information</h4>
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <div>
                            <strong>Date</strong>
                            <p>{{ \Carbon\Carbon::parse($event->event_date)->format('l, F d, Y') }}</p>
                        </div>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <div>
                            <strong>Time</strong>
                            <p>{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</p>
                        </div>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Location</strong>
                            <p>{{ $event->location }}</p>
                        </div>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <div>
                            <strong>Volunteers</strong>
                            <p>{{ $event->registered_count }}/{{ $event->max_volunteers }} registered</p>
                        </div>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <div>
                            <strong>Category</strong>
                            <p>{{ $event->category }}</p>
                        </div>
                    </div>
                    
                    <a href="#" class="btn btn-join-event">Register for Event</a>
                </div>
            </div>

            <!-- Event Description -->
            <div class="col-lg-7">
                <div class="event-detail-content">
                    <div class="description-section">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .event-detail-image {
        border: 3px solid var(--navy);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .event-detail-image img {
        width: 100%;
        height: auto;
        display: block;
    }

    .event-meta-card {
        background: white;
        border: 3px solid var(--navy);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .event-meta-card h4 {
        color: var(--navy);
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
    }

    .meta-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .meta-item:last-of-type {
        border-bottom: none;
        margin-bottom: 2rem;
    }

    .meta-item i {
        color: var(--primary-teal);
        font-size: 1.5rem;
        min-width: 30px;
    }

    .meta-item strong {
        color: var(--navy);
        font-size: 0.9rem;
        display: block;
        margin-bottom: 0.3rem;
    }

    .meta-item p {
        color: var(--text-dark);
        margin: 0;
        font-size: 0.95rem;
    }

    .btn-join-event {
        background: var(--accent-yellow);
        color: var(--navy);
        padding: 1rem 2rem;
        border-radius: 25px;
        border: 2px solid var(--navy);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-align: center;
        display: block;
        width: 100%;
        text-decoration: none;
    }

    .btn-join-event:hover {
        background: white;
        color: var(--navy);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .event-detail-content {
        background: white;
        border: 3px solid var(--navy);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .description-section {
        color: var(--text-dark);
        line-height: 1.8;
        font-size: 1rem;
    }

    .description-section strong {
        color: var(--navy);
        font-weight: 700;
        font-size: 1.1rem;
    }

    .description-section p {
        margin-bottom: 1rem;
    }

    @media (max-width: 991px) {
        .event-detail-content {
            margin-top: 2rem;
        }
    }
</style>
@endpush
