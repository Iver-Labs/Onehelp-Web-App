@extends('layouts.volunteer-app')

@section('title', 'Profile - OneHelp')

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-header">
        <div class="welcome-avatar">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div class="welcome-text">
            <h1>Welcome back, {{ auth()->user()->volunteer->first_name ?? 'Volunteer' }}!</h1>
            <p>See the difference you're making.</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['totalEvents'] ?? 4 }}</h3>
                <p>Total Events</p>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-icon beige">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['completedEvents'] ?? 2 }}</h3>
                <p>Completed Events</p>
            </div>
        </div>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
<div style="background: #D1FAE5; color: #065F46; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
    ✓ {{ session('success') }}
</div>
@endif

<!-- Error Messages -->
@if($errors->any())
<div style="background: #FEE2E2; color: #991B1B; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
    <ul style="margin: 0; padding-left: 20px;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Profile Forms Grid -->
<div class="content-grid">
    <!-- Personal Information Card -->
    <div class="card">
        <h2 class="card-title">Personal Information</h2>
        
        <form method="POST" action="{{ route('volunteer.profile.update') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; align-items: center;">
            @csrf
            @method('PUT')
            
            <!-- Profile Photo -->
            <div style="text-align: center; margin-bottom: 25px;">
                <div style="width: 120px; height: 120px; border-radius: 50%; background: #F4D58D; margin: 0 auto 15px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    @if($volunteer->profile_image)
                        <img src="{{ asset('storage/' . $volunteer->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <svg style="width: 60px; height: 60px; color: #B8956A;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </div>
                
                <label for="profile_image" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: white; border: 2px solid #4A5568; border-radius: 8px; cursor: pointer; font-size: 13px; color: #2C3E50; font-weight: 500;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Change Photo
                </label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display: none;">
            </div>

            <!-- Form Fields -->
            <div style="width: 100%;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $volunteer->first_name) }}" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 20px; font-size: 14px; background: #F5F5F5;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Age</label>
                        <input type="number" name="age" value="{{ old('age', $volunteer->age ?? '') }}"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 20px; font-size: 14px; background: #F5F5F5;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Birthday</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $volunteer->date_of_birth) }}"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 20px; font-size: 14px; background: #F5F5F5;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Contact No.</label>
                        <input type="text" name="phone" value="{{ old('phone', $volunteer->phone) }}"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 20px; font-size: 14px; background: #F5F5F5;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Email Address</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled
                               style="width: 100%; padding: 12px 16px; border: 2px solid #D1D5DB; border-radius: 20px; font-size: 14px; background: #E5E7EB; color: #6B7280;">
                    </div>
                </div>

                <button type="submit" 
                        style="width: 100%; padding: 12px; background: #E8D4A7; color: #2C3E50; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    UPDATE
                </button>
            </div>
        </form>
    </div>

    <!-- Volunteer Information Card -->
    <div class="card">
        <h2 class="card-title">Volunteer Information</h2>
        
        <form method="POST" action="{{ route('volunteer.info.update') }}">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Skills</label>
                <input type="text" name="skills" value="{{ old('skills', $volunteer->skills) }}" placeholder="e.g., Teaching, Cooking, First Aid"
                       style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 20px; font-size: 14px; background: #F5F5F5;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Interests</label>
                <input type="text" name="interests" value="{{ old('interests', $volunteer->interests) }}" placeholder="e.g., Environment, Education, Health"
                       style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 20px; font-size: 14px; background: #F5F5F5;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Location</label>
                <input type="text" name="location" value="{{ old('location', $volunteer->location) }}" placeholder="e.g., Quezon City, Metro Manila"
                       style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 20px; font-size: 14px; background: #F5F5F5;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Availability</label>
                <input type="text" name="availability" value="{{ old('availability', $volunteer->availability) }}" placeholder="e.g., Weekends, Weekdays after 5pm"
                       style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 20px; font-size: 14px; background: #F5F5F5;">
            </div>

            <button type="submit" 
                    style="width: 100%; padding: 12px; background: #E8D4A7; color: #2C3E50; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                UPDATE
            </button>
        </form>
    </div>
</div>
@endsection