@extends('layouts.app')

@section('title', 'OneHelp - Connecting Hearts to Communities')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(rgba(28, 77, 94, 0.6), rgba(28, 77, 94, 0.6)), url('{{ asset('images/community_photo.jpg') }}') center/cover;">
    <div class="hero-content">
        <h1>"Connecting Hearts to<br>Communities"</h1>
        <p>Empowering volunteers and organizations to<br>make a difference.</p>
        <button class="btn-browse-events" onclick="window.location.href='{{ url('/events') }}'">Browse Events</button>
    </div>
    
    <!-- SDG Logo at bottom right -->
    <div style="position: absolute; bottom: 2rem; right: 2rem; z-index: 10;">
        <img src="{{ asset('images/sdg_logo.svg') }}" alt="UN Sustainable Development Goals" style="height: 60px; width: auto;">
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works">
    <div class="container">
        <h2>How It Works?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="how-card">
                    <div class="how-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3>Register</h3>
                    <p>Sign up easily as a volunteer or organization to get started. Create your profile and tell us about your interests, skills, and availability. It only takes a few minutes to join our community.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="how-card">
                    <div class="how-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Match</h3>
                    <p>Our AI-based system connects volunteers with organizations that align with their passions and availability. Browse through various opportunities and find the perfect match for your skills and interests.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="how-card">
                    <div class="how-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Volunteer</h3>
                    <p>Join events, contribute your time, and make a real difference in your community. Track your impact, earn recognition, and build meaningful connections with like-minded individuals along the way.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Events Section -->
<section class="featured-events">
    <div class="container">
        <h2>FEATURED VOLUNTEER EVENTS</h2>
        <div class="row g-4">
            <!-- Event Card 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="event-card">
                    <div class="event-image">
                        <img src="{{ asset('images/events/coastal_cleanup.jpg') }}" alt="Coastal & Riverbank Cleanup Drive">
                    </div>
                    <div class="event-content">
                        <h3 class="event-title">Coastal & Riverbank Cleanup Drive</h3>
                        <p class="event-org">Green Earth Alliance</p>
                        <p class="event-description">Join us for a meaningful day of environmental action as we clean up our coastal areas and riverbanks. Help protect marine life and keep our waters clean.</p>
                        <div class="event-meta">
                            <span><i class="far fa-calendar"></i> Dec 15, 2024</span>
                            <span><i class="far fa-clock"></i> 8:00 AM</span>
                        </div>
                        <div class="event-tags">
                            <span class="event-tag">Environment</span>
                            <span class="event-tag">Outdoor</span>
                        </div>
                        <a href="{{ url('/events/1') }}" class="btn-view-details">View Details</a>
                    </div>
                </div>
            </div>

            <!-- Event Card 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="event-card">
                    <div class="event-image">
                        <img src="{{ asset('images/events/feeding-program.png') }}" alt="Feed the City: Community Food Drive">
                    </div>
                    <div class="event-content">
                        <h3 class="event-title">Feed the City: Community Food Drive</h3>
                        <p class="event-org">Hope Foundation</p>
                        <p class="event-description">Help us distribute food packages to families in need. Your time and effort can make a significant difference in fighting hunger in our community.</p>
                        <div class="event-meta">
                            <span><i class="far fa-calendar"></i> Dec 20, 2024</span>
                            <span><i class="far fa-clock"></i> 2:00 PM</span>
                        </div>
                        <div class="event-tags">
                            <span class="event-tag">Community</span>
                            <span class="event-tag">Food</span>
                        </div>
                        <a href="{{ url('/events/2') }}" class="btn-view-details">View Details</a>
                    </div>
                </div>
            </div>

            <!-- Event Card 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="event-card">
                    <div class="event-image">
                        <img src="{{ asset('images/events/reforestation.jpg') }}" alt="Reforestation Project: Plant a Tree, Grow Hope">
                    </div>
                    <div class="event-content">
                        <h3 class="event-title">Reforestation Project: Plant a Tree, Grow Hope</h3>
                        <p class="event-org">Nature Warriors PH</p>
                        <p class="event-description">Be part of our mission to restore our forests. Join us in planting native tree species and contribute to a greener, more sustainable future for the next generation.</p>
                        <div class="event-meta">
                            <span><i class="far fa-calendar"></i> Dec 22, 2024</span>
                            <span><i class="far fa-clock"></i> 6:00 AM</span>
                        </div>
                        <div class="event-tags">
                            <span class="event-tag">Environment</span>
                            <span class="event-tag">Outdoor</span>
                        </div>
                        <a href="{{ url('/events/3') }}" class="btn-view-details">View Details</a>
                    </div>
                </div>
            </div>

            <!-- Event Card 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="event-card">
                    <div class="event-image">
                        <img src="{{ asset('images/events/computer.png') }}" alt="Read to Lead: Children's Literacy Program">
                    </div>
                    <div class="event-content">
                        <h3 class="event-title">Read to Lead: Children's Literacy Program</h3>
                        <p class="event-org">Bright Futures Education</p>
                        <p class="event-description">Share your love for reading with children! Help improve literacy rates by reading stories, teaching basic reading skills, and inspiring young minds to discover the joy of books.</p>
                        <div class="event-meta">
                            <span><i class="far fa-calendar"></i> Dec 28, 2024</span>
                            <span><i class="far fa-clock"></i> 3:00 PM</span>
                        </div>
                        <div class="event-tags">
                            <span class="event-tag">Education</span>
                            <span class="event-tag">Children</span>
                        </div>
                        <a href="{{ url('/events/4') }}" class="btn-view-details">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sustainable Future Section -->
<section class="sustainable-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>A Sustainable Future with you</h2>
                <p>Together, we empower communities to create a brighter, more sustainable future. Our platform connects volunteers and organizations to build a stronger, more resilient society. By aligning with the United Nations Sustainable Development Goals, we're making a real difference in people's lives.</p>
            </div>
            <div class="col-md-6">
                <div class="sdg-grid">
                    <!-- SDG Placeholders -->
                    <div class="sdg-item">
                        <img src="{{ asset('images/sdg-2-logo.svg') }}" alt="SDG 1" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="sdg-item">
                        <img src="{{ asset('images/sdg-4-logo.svg') }}" alt="SDG 2" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="sdg-item">
                        <img src="{{ asset('images/sdg-11-logo.svg') }}" alt="SDG 3" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="sdg-item">
                        <img src="{{ asset('images/sdg-16-logo.svg') }}" alt="SDG 4" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="sdg-item">
                        <img src="{{ asset('images/sdg-3-logo.svg') }}" alt="SDG 5" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="sdg-item">
                        <img src="{{ asset('images/sdg-17-logo.svg') }}" alt="SDG 6" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
                <div class="sdg-logo">
                    <img src="{{ asset('images/sdg_logo.svg') }}" alt="UN SDG Logo" style="width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection