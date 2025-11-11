@extends('layouts.app')

@section('title', 'About Us - OneHelp')

@section('content')
<!-- About Hero -->
<section class="hero-section" style="background: linear-gradient(rgba(28, 77, 94, 0.6), rgba(28, 77, 94, 0.6)), url('{{ asset('images/community_photo.jpg') }}') center/cover; min-height: 400px;">
    <div class="hero-content">
        <h1>About OneHelp</h1>
        <p>Building Bridges Between Hearts and Communities</p>
    </div>
</section>

<!-- Mission & Vision -->
<section style="padding: 5rem 0; background: white;">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-6">
                <div class="p-4" style="background: var(--primary-teal); border-radius: 20px; border: 3px solid var(--navy); height: 100%;">
                    <h2 style="color: var(--navy); font-size: 2.5rem; font-weight: 700; margin-bottom: 1.5rem;">Our Mission</h2>
                    <p style="color: var(--navy); font-size: 1.1rem; line-height: 1.8;">
                        To empower communities by connecting passionate volunteers with meaningful opportunities, 
                        fostering a culture of service, compassion, and sustainable development across the Philippines.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4" style="background: var(--accent-yellow); border-radius: 20px; border: 3px solid var(--navy); height: 100%;">
                    <h2 style="color: var(--navy); font-size: 2.5rem; font-weight: 700; margin-bottom: 1.5rem;">Our Vision</h2>
                    <p style="color: var(--navy); font-size: 1.1rem; line-height: 1.8;">
                        A Philippines where every individual actively contributes to building stronger, more resilient 
                        communities through volunteerism, creating lasting positive change for generations to come.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section style="padding: 5rem 0; background: var(--primary-teal);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="{{ asset('images/community_photo.jpg') }}" alt="Our Story" style="width: 100%; border-radius: 20px; border: 3px solid var(--navy);">
            </div>
            <div class="col-md-6">
                <h2 style="color: var(--navy); font-size: 2.5rem; font-weight: 700; margin-bottom: 2rem;">Our Story</h2>
                <p style="color: var(--navy); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.5rem;">
                    OneHelp was born from a simple idea: that every person has something valuable to contribute to their community. 
                    Founded in 2024, we recognized the gap between passionate volunteers and organizations in need of support.
                </p>
                <p style="color: var(--navy); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.5rem;">
                    Our platform leverages technology to make volunteering accessible, meaningful, and impactful. We've connected 
                    thousands of volunteers with hundreds of organizations, creating a ripple effect of positive change across the Philippines.
                </p>
                <p style="color: var(--navy); font-size: 1.1rem; line-height: 1.8;">
                    Today, we're proud to be the leading volunteer management platform in the country, but our journey is just beginning.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section style="padding: 5rem 0; background: white;">
    <div class="container">
        <h2 class="text-center mb-5" style="color: var(--navy); font-size: 2.5rem; font-weight: 700;">Our Core Values</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4" style="background: var(--primary-teal); border-radius: 20px; border: 3px solid var(--navy); height: 100%;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🤝</div>
                    <h3 style="color: var(--navy); font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Community</h3>
                    <p style="color: var(--navy);">Building strong connections and fostering a sense of belonging among volunteers and organizations.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4" style="background: var(--accent-yellow); border-radius: 20px; border: 3px solid var(--navy); height: 100%;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">💡</div>
                    <h3 style="color: var(--navy); font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Innovation</h3>
                    <p style="color: var(--navy);">Leveraging technology to create efficient, user-friendly solutions for modern volunteerism.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4" style="background: var(--primary-teal); border-radius: 20px; border: 3px solid var(--navy); height: 100%;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🌱</div>
                    <h3 style="color: var(--navy); font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Impact</h3>
                    <p style="color: var(--navy);">Measuring and maximizing the positive change we create in communities across the nation.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4" style="background: var(--accent-yellow); border-radius: 20px; border: 3px solid var(--navy); height: 100%;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🎯</div>
                    <h3 style="color: var(--navy); font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Integrity</h3>
                    <p style="color: var(--navy);">Operating with transparency, honesty, and accountability in everything we do.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4" style="background: var(--primary-teal); border-radius: 20px; border: 3px solid var(--navy); height: 100%;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🌍</div>
                    <h3 style="color: var(--navy); font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Sustainability</h3>
                    <p style="color: var(--navy);">Aligning our efforts with the UN SDGs to create long-term, sustainable solutions.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4" style="background: var(--accent-yellow); border-radius: 20px; border: 3px solid var(--navy); height: 100%;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">❤️</div>
                    <h3 style="color: var(--navy); font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Compassion</h3>
                    <p style="color: var(--navy);">Leading with empathy and understanding in every interaction and initiative.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section (Optional) -->
<section style="padding: 5rem 0; background: var(--accent-yellow);">
    <div class="container">
        <h2 class="text-center mb-5" style="color: var(--navy); font-size: 2.5rem; font-weight: 700;">Meet Our Team</h2>
        <div class="row g-4">
            @for ($i = 1; $i <= 4; $i++)
            <div class="col-md-3">
                <div class="text-center">
                    <div style="width: 150px; height: 150px; margin: 0 auto 1rem; border-radius: 50%; border: 3px solid var(--navy); overflow: hidden;">
                        <img src="https://via.placeholder.com/150/7DD3C0/1A4D5E?text=Team+{{ $i }}" alt="Team Member {{ $i }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h4 style="color: var(--navy); font-weight: 700; margin-bottom: 0.5rem;">Team Member {{ $i }}</h4>
                    <p style="color: var(--navy); font-weight: 600;">Position Title</p>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- Call to Action -->
<section style="padding: 5rem 0; background: var(--primary-teal);">
    <div class="container text-center">
        <h2 style="color: var(--navy); font-size: 2.5rem; font-weight: 700; margin-bottom: 2rem;">Join Our Community</h2>
        <p style="color: var(--navy); font-size: 1.2rem; margin-bottom: 2.5rem; max-width: 700px; margin-left: auto; margin-right: auto;">
            Whether you're a volunteer looking to make a difference or an organization seeking support, 
            we're here to help you create positive change.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ url('/register') }}" class="btn btn-browse-events" style="display: inline-block;">Sign Up Now</a>
            <a href="{{ url('/events') }}" class="btn" style="background: white; color: var(--navy); padding: 1rem 3rem; border-radius: 50px; border: 3px solid var(--navy); font-size: 1.1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; display: inline-block;">Browse Events</a>
        </div>
    </div>
</section>
@endsection