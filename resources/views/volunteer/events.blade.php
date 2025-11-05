@extends('layouts.volunteer-app')

@section('title', 'View Events - OneHelp')

@section('content')
<div class="welcome-section">
    <h1 style="font-size: 28px; font-weight: 600; color: #2C3E50; margin-bottom: 10px;">Browse Events</h1>
    <p style="font-size: 14px; color: #5A6C7D;">Find opportunities to make a difference in your community.</p>
</div>

<div class="card" style="margin-top: 20px;">
    <div style="text-align: center; padding: 60px 20px;">
        <!-- PLACEHOLDER: Events icon -->
        <svg style="width: 80px; height: 80px; color: #D1D5DB; margin: 0 auto 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <h2 style="font-size: 20px; font-weight: 600; color: #2C3E50; margin-bottom: 10px;">Events Coming Soon</h2>
        <p style="font-size: 14px; color: #6B7280; margin-bottom: 20px;">This page will display all available volunteer events.</p>
        <a href="{{ route('volunteer.dashboard') }}" style="display: inline-block; padding: 10px 24px; background: #7CB5B3; color: white; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 14px;">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection