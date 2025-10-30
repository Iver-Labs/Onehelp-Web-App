@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- HERO -->
<section class="text-center text-white" style="background: url('{{ asset('images/hero.jpg') }}') center/cover no-repeat; padding: 150px 0;">
  <div class="container">
    <h1 class="fw-bold display-5">“Connecting Hearts to Communities.”</h1>
    <p class="lead mt-3">Empowering volunteers and organizations to make a difference.</p>
    <a href="/events" class="btn btn-warning fw-semibold mt-4">Browse Events</a>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="py-5" style="background-color:#92C7CF;">
  <div class="container text-center">
    <h2 class="fw-bold mb-5">How it Works?</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="p-4 bg-white rounded shadow-sm">
          <h4 class="fw-bold">REGISTER</h4>
          <p>Sign up easily as a volunteer or organization to begin your journey of community impact.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 bg-white rounded shadow-sm">
          <h4 class="fw-bold">MATCH</h4>
          <p>Our AI-based system connects volunteers with opportunities that best fit their skills and interests.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 bg-white rounded shadow-sm">
          <h4 class="fw-bold">VOLUNTEER</h4>
          <p>Join events, contribute your time, and make a real difference in your community.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURED EVENTS -->
<section class="py-5" style="background-color:#F7D47E;">
  <div class="container text-center">
    <h2 class="fw-bold mb-5">Featured Volunteer Events</h2>
    <div class="row g-4">
      @foreach ($events as $event)
      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <img src="{{ asset('images/event-placeholder.jpg') }}" class="card-img-top" alt="{{ $event->event_name }}">
          <div class="card-body text-start">
            <h5 class="fw-bold">{{ $event->event_name }}</h5>
            <p class="text-muted small mb-1">📍 {{ $event->location }}</p>
            <p class="text-muted small mb-1">🗓 {{ $event->event_date }}</p>
            <p class="text-muted small">🕗 {{ $event->start_time }} - {{ $event->end_time }}</p>
            <a href="/events/{{ $event->id }}" class="btn btn-warning w-100 mt-2">Join This Event</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- SDG SECTION -->
<section class="py-5 text-center" style="background-color:#AAD7D9;">
  <div class="container">
    <h2 class="fw-bold mb-3">A Sustainable Future with You</h2>
    <p class="mb-4">Together, we empower communities through meaningful partnerships to build a stronger, more sustainable future.</p>
    <img src="{{ asset('images/sdg.png') }}" width="300" alt="Sustainable Development Goals">
  </div>
</section>
@endsection
