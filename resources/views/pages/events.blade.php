@extends('layouts.app')

@section('content')
<style>
  .events-hero {
    background: url('{{ asset('images/page-8.png') }}') no-repeat center center/cover;
    min-height: 50vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    position: relative;
  }
  .events-hero::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(30, 46, 61, 0.7);
  }
  .events-hero .content {
    position: relative;
    z-index: 1;
  }
  .event-card {
    background-color: #EAF2F8;
    border: none;
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
  }
  .event-card img {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    height: 180px;
    object-fit: cover;
  }
  .btn-join {
    background-color: #F7D47E;
    color: #1E2E3D;
    font-weight: 600;
    border: none;
    transition: 0.2s;
  }
  .btn-join:hover {
    background-color: #EACB69;
  }
</style>

<!-- HERO SECTION -->
<section class="events-hero">
  <div class="content">
    <h1 class="fw-bold display-5 mb-3">Volunteer Events</h1>
    <p class="lead">Discover and join meaningful volunteer opportunities near you.</p>
  </div>
</section>

<!-- EVENTS GRID -->
<section class="py-5" style="background-color:#F7D47E;">
  <div class="container">
    <h2 class="fw-bold text-center mb-5">Upcoming Events</h2>

    @if ($events->count() > 0)
      <div class="row g-4">
        @foreach ($events as $event)
          <div class="col-md-3">
            <div class="card event-card h-100 shadow-sm">
              @if ($event->images && count($event->images))
                <img src="{{ asset('storage/' . $event->images[0]->image_url) }}" alt="{{ $event->event_name }}">
              @else
                <img src="{{ asset('images/event-placeholder.jpg') }}" alt="{{ $event->event_name }}">
              @endif
              <div class="card-body text-start">
                <h5 class="fw-bold">{{ $event->event_name }}</h5>
                <p class="text-muted small mb-1">📍 {{ $event->location }}</p>
                <p class="text-muted small mb-1">🗓 {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                <p class="text-muted small">🕗 {{ $event->start_time }} - {{ $event->end_time }}</p>
                <a href="/events/{{ $event->id }}" class="btn btn-join w-100 mt-2">Join This Event</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-center text-muted fs-5 mt-5">No events available at the moment. Please check back soon!</p>
    @endif
  </div>
</section>

<!-- COMMUNITY MESSAGE -->
<section class="py-5" style="background-color:#234C6A; color:white;">
  <div class="container text-center">
    <h2 class="fw-bold mb-3">Together, We Make a Difference</h2>
    <p class="mb-0">Every act of kindness counts — find your next opportunity to give back and be part of a growing movement for change.</p>
  </div>
</section>
@endsection
