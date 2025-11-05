@extends('layouts.volunteer-app')

@section('title', 'My Profile - OneHelp')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="flex items-center gap-4">
            <img class="w-24 h-24 rounded-full border-4 border-white shadow-md" src="{{ $volunteer->profile_photo_path ?? 'https://via.placeholder.com/150' }}" alt="Profile Photo">
            <div>
                <h1 class="text-3xl font-bold text-text-dark">{{ $volunteer->first_name }} {{ $volunteer->last_name }}</h1>
                <p class="text-gray-600">Volunteer</p>
            </div>
        </div>
        <a href="#" class="mt-4 sm:mt-0 inline-flex items-center justify-center px-6 py-3 bg-accent-teal text-white font-semibold rounded-xl shadow-md hover:bg-accent-dark-teal transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L14.732 5.232z"></path>
            </svg>
            Edit Profile
        </a>
    </div>

    <!-- Profile Details Card -->
    <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-200">
        <h2 class="text-xl font-bold text-text-dark mb-6">Personal Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <!-- First Name -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">First Name</label>
                <p class="text-text-dark font-semibold">{{ $volunteer->first_name }}</p>
            </div>
            
            <!-- Last Name -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Last Name</label>
                <p class="text-text-dark font-semibold">{{ $volunteer->last_name }}</p>
            </div>
            
            <!-- Date of Birth -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Date of Birth</label>
                <p class="text-text-dark font-semibold">{{ \Carbon\Carbon::parse($volunteer->date_of_birth)->format('F j, Y') }}</p>
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                <p class="text-text-dark font-semibold">{{ $volunteer->phone_number ?? 'Not provided' }}</p>
            </div>

            <!-- Address -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                <p class="text-text-dark font-semibold">{{ $volunteer->address ?? 'Not provided' }}</p>
            </div>
            
            <!-- Bio -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500 mb-1">Bio</label>
                <p class="text-gray-600 italic">{{ $volunteer->bio ?? 'No bio yet.' }}</p>
            </div>
        </div>

        <!-- Divider -->
        <hr class="my-8 border-gray-200">

        <!-- Stats Section -->
        <div class="flex justify-around text-center">
            <div>
                <p class="text-2xl font-bold text-accent-teal">{{ $totalHours }}</p>
                <p class="text-sm text-gray-600 font-medium">Hours Contributed</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-accent-teal">{{ $completedEvents }}</p>
                <p class="text-sm text-gray-600 font-medium">Events Completed</p>
            </div>
        </div>
    </div>
</div>
@endsection
