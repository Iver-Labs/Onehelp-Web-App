@extends('layouts.organization-app')

@section('title', 'Create Event - OneHelp')

@section('content')
<div class="welcome-section">
    <h1 style="font-size: 28px; font-weight: 600; color: #2C3E50; margin-bottom: 10px;">Create New Event</h1>
    <p style="font-size: 14px; color: #5A6C7D;">Fill in the details below to create a volunteer opportunity.</p>
</div>

<div class="card" style="margin-top: 20px;">
    @if(session('success'))
    <div style="background: #D1FAE5; border-left: 4px solid #10B981; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <svg style="width: 18px; height: 18px; color: #10B981; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p style="font-size: 13px; color: #065F46; margin: 0;">{{ session('success') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div style="background: #FEE2E2; border-left: 4px solid #EF4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
        <p style="font-size: 13px; color: #991B1B; margin: 0 0 8px 0; font-weight: 600;">Please fix the following errors:</p>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
            <li style="font-size: 13px; color: #991B1B;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('organization.events.store') }}" style="padding: 20px;">
        @csrf
        
        <!-- Event Name -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #2C3E50; margin-bottom: 8px;">
                Event Name <span style="color: #EF4444;">*</span>
            </label>
            <input 
                type="text" 
                name="event_name" 
                value="{{ old('event_name') }}"
                required
                style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; color: #2C3E50; transition: all 0.2s;"
                placeholder="Enter event name"
                onfocus="this.style.borderColor='#7CB5B3'" 
                onblur="this.style.borderColor='#E5E7EB'"
            >
        </div>

        <!-- Description -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #2C3E50; margin-bottom: 8px;">
                Description
            </label>
            <textarea 
                name="description" 
                rows="4"
                style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; color: #2C3E50; transition: all 0.2s; resize: vertical;"
                placeholder="Describe the event, its purpose, and what volunteers will do"
                onfocus="this.style.borderColor='#7CB5B3'" 
                onblur="this.style.borderColor='#E5E7EB'"
            >{{ old('description') }}</textarea>
        </div>

        <!-- Category -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #2C3E50; margin-bottom: 8px;">
                Category
            </label>
            <select 
                name="category"
                style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; color: #2C3E50; transition: all 0.2s;"
                onfocus="this.style.borderColor='#7CB5B3'" 
                onblur="this.style.borderColor='#E5E7EB'"
            >
                <option value="">Select category...</option>
                <option value="Education" {{ old('category') == 'Education' ? 'selected' : '' }}>Education</option>
                <option value="Environment" {{ old('category') == 'Environment' ? 'selected' : '' }}>Environment</option>
                <option value="Health" {{ old('category') == 'Health' ? 'selected' : '' }}>Health</option>
                <option value="Community" {{ old('category') == 'Community' ? 'selected' : '' }}>Community</option>
                <option value="Animals" {{ old('category') == 'Animals' ? 'selected' : '' }}>Animals</option>
                <option value="Disaster Relief" {{ old('category') == 'Disaster Relief' ? 'selected' : '' }}>Disaster Relief</option>
                <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <!-- Event Date and Time Row -->
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 20px;">
            <!-- Event Date -->
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #2C3E50; margin-bottom: 8px;">
                    Event Date <span style="color: #EF4444;">*</span>
                </label>
                <input 
                    type="date" 
                    name="event_date" 
                    value="{{ old('event_date') }}"
                    required
                    min="{{ date('Y-m-d') }}"
                    style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; color: #2C3E50; transition: all 0.2s;"
                    onfocus="this.style.borderColor='#7CB5B3'" 
                    onblur="this.style.borderColor='#E5E7EB'"
                >
            </div>

            <!-- Start Time -->
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #2C3E50; margin-bottom: 8px;">
                    Start Time <span style="color: #EF4444;">*</span>
                </label>
                <input 
                    type="time" 
                    name="start_time" 
                    value="{{ old('start_time') }}"
                    required
                    style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; color: #2C3E50; transition: all 0.2s;"
                    onfocus="this.style.borderColor='#7CB5B3'" 
                    onblur="this.style.borderColor='#E5E7EB'"
                >
            </div>

            <!-- End Time -->
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #2C3E50; margin-bottom: 8px;">
                    End Time <span style="color: #EF4444;">*</span>
                </label>
                <input 
                    type="time" 
                    name="end_time" 
                    value="{{ old('end_time') }}"
                    required
                    style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; color: #2C3E50; transition: all 0.2s;"
                    onfocus="this.style.borderColor='#7CB5B3'" 
                    onblur="this.style.borderColor='#E5E7EB'"
                >
            </div>
        </div>

        <!-- Location -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #2C3E50; margin-bottom: 8px;">
                Location <span style="color: #EF4444;">*</span>
            </label>
            <input 
                type="text" 
                name="location" 
                value="{{ old('location') }}"
                required
                style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; color: #2C3E50; transition: all 0.2s;"
                placeholder="Enter event location or address"
                onfocus="this.style.borderColor='#7CB5B3'" 
                onblur="this.style.borderColor='#E5E7EB'"
            >
        </div>

        <!-- Max Volunteers and Status Row -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
            <!-- Max Volunteers -->
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #2C3E50; margin-bottom: 8px;">
                    Maximum Volunteers <span style="color: #EF4444;">*</span>
                </label>
                <input 
                    type="number" 
                    name="max_volunteers" 
                    value="{{ old('max_volunteers', 10) }}"
                    required
                    min="1"
                    style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; color: #2C3E50; transition: all 0.2s;"
                    onfocus="this.style.borderColor='#7CB5B3'" 
                    onblur="this.style.borderColor='#E5E7EB'"
                >
            </div>

            <!-- Status -->
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #2C3E50; margin-bottom: 8px;">
                    Status
                </label>
                <select 
                    name="status"
                    style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; color: #2C3E50; transition: all 0.2s;"
                    onfocus="this.style.borderColor='#7CB5B3'" 
                    onblur="this.style.borderColor='#E5E7EB'"
                >
                    <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Open for Registration</option>
                    <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
            <button 
                type="submit"
                style="flex: 1; padding: 14px; background: #2C3E50; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                onmouseover="this.style.background='#1A2532'"
                onmouseout="this.style.background='#2C3E50'"
            >
                Create Event
            </button>
            <a 
                href="{{ route('organization.dashboard') }}"
                style="flex: 0 0 auto; padding: 14px 24px; background: white; color: #2C3E50; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; text-align: center; transition: all 0.2s;"
                onmouseover="this.style.borderColor='#2C3E50'"
                onmouseout="this.style.borderColor='#E5E7EB'"
            >
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
