@extends('layouts.volunteer-app')

@section('title', 'Account Settings - OneHelp')

@section('content')
<div class="max-w-4xl">
    <h1 class="text-3xl font-bold text-text-dark mb-8">Account Settings</h1>
    
    <!-- Account Information -->
    <div class="bg-card-white rounded-2xl shadow-md p-8 border border-gray-200 mb-6">
        <h2 class="text-xl font-bold text-text-dark mb-6">Account Information</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <p class="text-text-dark">{{ $user->email }}</p>
                @if(!$user->email_verified_at)
                <p class="text-sm text-orange-600 mt-1">⚠️ Email not verified. Please check your inbox.</p>
                @else
                <p class="text-sm text-green-600 mt-1">✓ Email verified</p>
                @endif
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                <p class="text-text-dark">{{ $user->username }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Account Type</label>
                <p class="text-text-dark capitalize">{{ $user->user_type }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Member Since</label>
                <p class="text-text-dark">{{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Change Password -->
    <div class="bg-card-white rounded-2xl shadow-md p-8 border border-gray-200 mb-6">
        <h2 class="text-xl font-bold text-text-dark mb-6">Change Password</h2>
        
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-teal">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-teal">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-teal">
            </div>
            
            <button type="submit" class="px-6 py-2 bg-accent-teal text-white rounded-lg font-medium hover:bg-opacity-90 transition">
                Update Password
            </button>
        </form>
    </div>
    
    <!-- Notification Preferences -->
    <div class="bg-card-white rounded-2xl shadow-md p-8 border border-gray-200">
        <h2 class="text-xl font-bold text-text-dark mb-6">Notification Preferences</h2>
        
        <div class="space-y-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" class="w-5 h-5 text-accent-teal rounded focus:ring-accent-teal" checked>
                <span class="text-text-dark">Email notifications for event updates</span>
            </label>
            
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" class="w-5 h-5 text-accent-teal rounded focus:ring-accent-teal" checked>
                <span class="text-text-dark">Email notifications for application status changes</span>
            </label>
            
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" class="w-5 h-5 text-accent-teal rounded focus:ring-accent-teal">
                <span class="text-text-dark">Email notifications for new messages</span>
            </label>
            
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" class="w-5 h-5 text-accent-teal rounded focus:ring-accent-teal">
                <span class="text-text-dark">Weekly volunteer opportunity digest</span>
            </label>
        </div>
        
        <button type="submit" class="mt-6 px-6 py-2 bg-accent-teal text-white rounded-lg font-medium hover:bg-opacity-90 transition">
            Save Preferences
        </button>
    </div>
</div>
@endsection