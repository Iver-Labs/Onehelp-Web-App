@extends('layouts.app')

@section('title', $event->event_name)

@section('content')
<section class="py-5" style="background-color: #f8f9fa;">
  <div class="container">
    <div class="event-detail-card p-4 p-md-5 shadow-sm mx-auto">

      <div class="row align-items-center g-4">

        <!-- EVENT IMAGE -->
        <div class="col-md-5">
          <div class="event-image-frame">
            @if ($event->images && count($event->images))
              <img src="{{ asset('storage/' . $event->images[0]->image_url) }}" 
                   alt="{{ $event->event_name }}" 
                   class="event-image">
            @else
              <img src="{{ asset('images/event-placeholder.jpg') }}" 
                   alt="{{ $event->event_name }}" 
                   class="event-image">
            @endif
          </div>
        </div>

        <!-- EVENT DETAILS -->
        <div class="col-md-7">
          <h2 class="fw-bold text-navy mb-3">{{ $event->event_name }}</h2>
          <p class="text-muted mb-2">{{ $event->organization->org_name ?? 'Community Partner' }}</p>
          <p class="mb-2"><i class="far fa-calendar text-primary-teal"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
          <p class="mb-2"><i class="far fa-clock text-primary-teal"></i> {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</p>
          <p class="mb-3"><i class="fas fa-map-marker-alt text-primary-teal"></i> {{ $event->location }}</p>

          @if (!empty($event->long_description))
          <div class="event-long-description mt-3">
            {!! nl2br(e($event->long_description)) !!}
          </div>
          @else
          <p class="lead text-secondary">{{ $event->description }}</p>
          @endif

          <a href="#" class="btn-view-details mt-4">Join Event</a>
        </div>
      </div>

      <!-- ADDITIONAL IMAGES -->
      @if ($event->images && count($event->images) > 1)
      <div class="row mt-5 g-3">
        <h4 class="fw-bold text-navy mb-3">More Photos</h4>
        @foreach ($event->images as $image)
        <div class="col-6 col-md-3">
          <div class="event-image-frame small">
            <img src="{{ asset('storage/' . $image->image_url) }}" 
                 alt="Event photo"
                 class="event-image">
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  :root {
    --navy: #0c3045;
    --primary-teal: #7dd3c0;
    --accent-yellow: #f4d58d;
  }

  /* Card container */
  .event-detail-card {
    background-color: #eef7f8;
    border: 2px solid var(--navy);
    border-radius: 16px;
    max-width: 1100px;
  }

  /* Image frame */
  .event-image-frame {
    width: 100%;
    aspect-ratio: 3 / 4;
    background-color: #f8fafb;
    border: 2px solid var(--navy);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  .event-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
  }

  .event-image:hover {
    transform: scale(1.05);
  }

  .event-image-frame.small {
    aspect-ratio: 3 / 4;
  }

  /* Text Colors */
  .text-navy {
    color: var(--navy);
  }

  .text-primary-teal {
    color: var(--primary-teal);
  }

  /* Buttons */
  .btn-view-details {
    background-color: var(--accent-yellow);
    color: var(--navy);
    font-weight: 700;
    border: 2px solid var(--navy);
    border-radius: 12px;
    padding: 0.6rem 1.2rem;
    transition: all 0.25s ease;
    text-decoration: none;
    display: inline-block;
  }

  .btn-view-details:hover {
    background-color: #ffcc00;
    color: var(--navy);
    text-decoration: none;
  }

  /* Description text */
  .event-long-description {
    background: #ffffff;
    border: 1px solid #cde3e7;
    border-radius: 12px;
    padding: 1rem;
    color: #333;
    font-size: 0.95rem;
    line-height: 1.6;
    max-height: 400px;
    overflow-y: auto;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .event-detail-card {
      padding: 2rem 1rem;
    }
    .event-image-frame {
      aspect-ratio: 1 / 1;
    }
  }
</style>
@endpush
