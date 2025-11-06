@extends('layouts.organization-app')

@section('title', 'Analytics & Impact - OneHelp')

@section('content')
<div class="welcome-section">
    <h1 style="font-size: 28px; font-weight: 600; color: #2C3E50; margin-bottom: 10px;">Analytics & Impact</h1>
    <p style="font-size: 14px; color: #5A6C7D;">Track your organization's performance and impact metrics.</p>
</div>

<div class="card">
    <div style="text-align: center; padding: 60px 20px;">
        <svg style="width: 90px; height: 90px; color: #D1D5DB; margin: 0 auto 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <h2 style="font-size: 22px; font-weight: 600; color: #2C3E50; margin-bottom: 12px;">Analytics Coming Soon</h2>
        <p style="font-size: 14px; color: #6B7280; margin-bottom: 24px; max-width: 500px; margin-left: auto; margin-right: auto;">
            We're working on comprehensive analytics to help you track your impact, volunteer engagement, and event performance.
        </p>
        <a href="{{ route('organization.dashboard') }}" style="display: inline-block; padding: 12px 24px; background: #7CB5B3; color: white; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px;">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection