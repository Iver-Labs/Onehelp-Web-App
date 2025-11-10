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

    <form method="POST" action="{{ route('organization.events.store') }}" enctype="multipart/form-data" style="padding: 20px;">
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: #2C3E50;">
                    Description
                </label>
                <button 
                    type="button"
                    id="generateDescriptionBtn"
                    onclick="generateDescription()"
                    style="padding: 8px 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2); display: flex; align-items: center; gap: 6px;"
                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(102, 126, 234, 0.3)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(102, 126, 234, 0.2)'"
                >
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span id="generateBtnText">Generate with AI</span>
                </button>
            </div>
            <textarea 
                id="descriptionField"
                name="description" 
                rows="6"
                style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; color: #2C3E50; transition: all 0.2s; resize: vertical;"
                placeholder="Describe the event, its purpose, and what volunteers will do. Or use AI to generate a compelling description!"
                onfocus="this.style.borderColor='#7CB5B3'" 
                onblur="this.style.borderColor='#E5E7EB'"
            >{{ old('description') }}</textarea>
            <p style="font-size: 12px; color: #6B7280; margin-top: 6px;">
                💡 <strong>Tip:</strong> Fill in the event name, category, and location first, then click "Generate with AI" to create a compelling description automatically!
            </p>
        </div>

        <!-- Event Image -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #2C3E50; margin-bottom: 8px;">
                Event Image <span style="color: #EF4444;">*</span>
            </label>
            <input 
                type="file" 
                name="event_image" 
                accept="image/jpeg,image/jpg,image/png"
                required
                style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; color: #2C3E50; transition: all 0.2s;"
                onfocus="this.style.borderColor='#7CB5B3'" 
                onblur="this.style.borderColor='#E5E7EB'"
            >
            <p style="font-size: 12px; color: #6B7280; margin-top: 6px;">
                Accepted formats: JPG, JPEG, PNG. Maximum size: 2MB
            </p>
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

<script>
async function generateDescription() {
    const eventName = document.querySelector('input[name="event_name"]').value;
    const category = document.querySelector('select[name="category"]').value;
    const location = document.querySelector('input[name="location"]').value;
    
    // Validate required fields
    if (!eventName.trim()) {
        alert('Please enter an event name first!');
        document.querySelector('input[name="event_name"]').focus();
        return;
    }
    
    const btn = document.getElementById('generateDescriptionBtn');
    const btnText = document.getElementById('generateBtnText');
    const descriptionField = document.getElementById('descriptionField');
    
    // Update button state
    btn.disabled = true;
    btn.style.opacity = '0.7';
    btn.style.cursor = 'not-allowed';
    btnText.innerHTML = '<svg style="width: 16px; height: 16px; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"/><path fill="currentColor" opacity="0.75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg> Generating...';
    
    // Add CSS for spin animation if not already present
    if (!document.getElementById('spin-style')) {
        const style = document.createElement('style');
        style.id = 'spin-style';
        style.textContent = '@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    }
    
    try {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]') || 
                         document.querySelector('input[name="_token"]');
        
        const response = await fetch('/api/ai/generate-event-description', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken ? csrfToken.content || csrfToken.value : ''
            },
            body: JSON.stringify({
                event_name: eventName,
                category: category,
                location: location
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            descriptionField.value = data.description;
            descriptionField.style.borderColor = '#10B981';
            setTimeout(() => {
                descriptionField.style.borderColor = '#E5E7EB';
            }, 2000);
            
            // Show success feedback
            showNotification('✨ Description generated successfully!', 'success');
        } else {
            showNotification('❌ ' + (data.message || 'Failed to generate description'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ An error occurred. Please try again.', 'error');
    } finally {
        // Reset button state
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
        btnText.innerHTML = '<svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Generate with AI';
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        z-index: 9999;
        animation: slideIn 0.3s ease-out;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        ${type === 'success' 
            ? 'background: #D1FAE5; color: #065F46; border-left: 4px solid #10B981;' 
            : 'background: #FEE2E2; color: #991B1B; border-left: 4px solid #EF4444;'}
    `;
    notification.textContent = message;
    
    // Add animation style
    if (!document.getElementById('notification-style')) {
        const style = document.createElement('style');
        style.id = 'notification-style';
        style.textContent = '@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }';
        document.head.appendChild(style);
    }
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endsection
