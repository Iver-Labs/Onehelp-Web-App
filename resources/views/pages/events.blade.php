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
            @for ($i = 1; $i <= 8; $i++)
            <div class="col-md-6 col-lg-3">
                <div class="event-card">
                    <div class="event-image">
                        <img src="https://via.placeholder.com/300x200/{{ $i % 2 == 0 ? '7DD3C0' : 'F4D58D' }}/1A4D5E?text=Event+{{ $i }}" alt="Event {{ $i }}">
                    </div>
                    <div class="event-content">
                        <h3 class="event-title">Sample Event {{ $i }}</h3>
                        <p class="event-org">Organization Name {{ $i }}</p>
                        <p class="event-description">Join us for this amazing volunteer opportunity that will make a real difference in our community.</p>
                        <div class="event-meta">
                            <span><i class="far fa-calendar"></i> Dec {{ 10 + $i }}, 2024</span>
                            <span><i class="far fa-clock"></i> {{ 8 + $i }}:00 AM</span>
                        </div>
                        <div class="event-tags">
                            <span class="event-tag">{{ $i % 2 == 0 ? 'Environment' : 'Community' }}</span>
                            <span class="event-tag">{{ $i % 3 == 0 ? 'Indoor' : 'Outdoor' }}</span>
                        </div>
                        <a href="{{ url('/events/' . $i) }}" class="btn-view-details">View Details</a>
                    </div>
                </div>
            </div>
            @endfor
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