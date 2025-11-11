@extends('layouts.volunteer-app')

@section('title', 'Account Settings - OneHelp')

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
<!-- Success/Error Messages -->
@if(session('success'))
<div style="background: #D1FAE5; color: #065F46; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
    ✓ {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background: #FEE2E2; color: #991B1B; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
    ✗ {{ session('error') }}
</div>
@endif

<!-- Account Settings Card (Centered) -->
<div style="display: flex; justify-content: center;">
    <div class="card" style="max-width: 550px; width: 100%;">
        <h2 class="card-title" style="text-align: center;">Account Settings</h2>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Username</label>
            <input type="text" value="{{ auth()->user()->email }}" disabled
                   style="width: 100%; padding: 12px 16px; border: 2px solid #D1D5DB; border-radius: 20px; font-size: 14px; background: #E5E7EB; color: #6B7280;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Email</label>
            <input type="email" value="{{ auth()->user()->email }}" disabled
                   style="width: 100%; padding: 12px 16px; border: 2px solid #D1D5DB; border-radius: 20px; font-size: 14px; background: #E5E7EB; color: #6B7280;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Password</label>
            <input type="password" value="••••••••" disabled
                   style="width: 100%; padding: 12px 16px; border: 2px solid #D1D5DB; border-radius: 20px; font-size: 14px; background: #E5E7EB; color: #6B7280;">
        </div>

        <button onclick="openChangePasswordModal()" 
                style="width: 100%; padding: 12px; background: #E8D4A7; color: #2C3E50; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; margin-bottom: 12px;">
            Change Password
        </button>

        <button onclick="openDeactivateModal()" 
                style="width: 100%; padding: 12px; background: #E8D4A7; color: #2C3E50; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
            Deactivate Account
        </button>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 30px; max-width: 450px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <h3 style="font-size: 20px; font-weight: 600; color: #2C3E50; margin-bottom: 20px; text-align: center;">Change Password</h3>
        
        <form method="POST" action="{{ route('volunteer.password.update') }}">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Current Password</label>
                <input type="password" name="current_password" required
                       style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 12px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">New Password</label>
                <input type="password" name="new_password" required
                       style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 12px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" required
                       style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 12px; font-size: 14px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeChangePasswordModal()" 
                        style="flex: 1; padding: 12px; background: #E5E7EB; color: #4A5568; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" 
                        style="flex: 1; padding: 12px; background: #7CB5B3; color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Deactivate Account Modal -->
<div id="deactivateModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 30px; max-width: 450px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <h3 style="font-size: 20px; font-weight: 600; color: #DC2626; margin-bottom: 15px; text-align: center;">Deactivate Account</h3>
        <p style="font-size: 14px; color: #4A5568; margin-bottom: 20px; text-align: center; line-height: 1.6;">
            Are you sure you want to deactivate your account? This action can be reversed by contacting support.
        </p>
        
        <form method="POST" action="{{ route('volunteer.account.deactivate') }}">
            @csrf
            @method('DELETE')
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #4A5568; margin-bottom: 6px;">Enter your password to confirm</label>
                <input type="password" name="password" required
                       style="width: 100%; padding: 12px 16px; border: 2px solid #4A5568; border-radius: 12px; font-size: 14px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeDeactivateModal()" 
                        style="flex: 1; padding: 12px; background: #E5E7EB; color: #4A5568; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" 
                        style="flex: 1; padding: 12px; background: #DC2626; color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    Deactivate
                </button>
            </div>
        </form>
    </div>
</div>

<script nonce="{{ $cspNonce ?? '' }}">
function openChangePasswordModal() {
    document.getElementById('changePasswordModal').style.display = 'flex';
}

function closeChangePasswordModal() {
    document.getElementById('changePasswordModal').style.display = 'none';
}

function openDeactivateModal() {
    document.getElementById('deactivateModal').style.display = 'flex';
}

function closeDeactivateModal() {
    document.getElementById('deactivateModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('changePasswordModal').addEventListener('click', function(e) {
    if (e.target === this) closeChangePasswordModal();
});

document.getElementById('deactivateModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeactivateModal();
});
</script>
@endsection