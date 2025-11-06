@extends('layouts.app')
@section('title', $event->event_name)

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-md-6">
      <img src="{{ $event->images[0]->url ?? asset('images/event-placeholder.jpg') }}"
           class="img-fluid rounded shadow-sm" alt="{{ $event->event_name }}">
    </div>
    <div class="col-md-6">
      <h2 class="fw-bold">{{ $event->event_name }}</h2>
      <p class="text-muted">{{ $event->organization->org_name ?? 'Community Partner' }}</p>
      <p><strong>Date:</strong> {{ $event->event_date }}</p>
      <p><strong>Location:</strong> {{ $event->location }}</p>
      <p class="mt-3">{{ $event->description }}</p>
      <a href="#" class="btn text-white" style="background-color:#1B3C53;">Join Event</a>
    </div>
  </div>
</div>
@endsection
