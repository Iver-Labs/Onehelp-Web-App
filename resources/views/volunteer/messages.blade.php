@extends('layouts.volunteer-app')

@section('title', 'Messages - OneHelp')

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-header">
        <div class="welcome-avatar">
            @if(auth()->user()->volunteer && auth()->user()->volunteer->profile_image)
                <img src="{{ asset('storage/' . auth()->user()->volunteer->profile_image) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            @endif
        </div>
        <div class="welcome-text">
            <h1>Welcome back, {{ auth()->user()->volunteer->first_name ?? 'Volunteer' }}!</h1>
            <p>See the difference you're making.</p>
        </div>
    </div>
</div>

<!-- Messages Card -->
<div class="card" style="padding: 0; overflow: hidden; height: 600px;">
    <div style="display: flex; height: 100%;">
        <!-- Left Sidebar - Conversations List -->
        <div style="width: 35%; border-right: 2px solid #E5E7EB; overflow-y: auto;">
            <!-- Search Header -->
            <div style="padding: 20px; border-bottom: 2px solid #E5E7EB;">
                <h2 style="font-size: 18px; font-weight: 600; color: #2C3E50; margin-bottom: 12px;">Messages</h2>
                <input type="text" placeholder="Search conversations..." 
                       style="width: 100%; padding: 10px 14px; border: 2px solid #D1D5DB; border-radius: 12px; font-size: 13px;">
            </div>

            <!-- Conversations List -->
            <div>
                @if($conversations->isEmpty())
                    <div style="padding: 40px 20px; text-align: center;">
                        <svg style="width: 60px; height: 60px; color: #D1D5DB; margin: 0 auto 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <p style="font-size: 14px; color: #6B7280; font-weight: 500;">No messages yet</p>
                        <p style="font-size: 12px; color: #9CA3AF; margin-top: 5px;">Your conversations will appear here</p>
                    </div>
                @else
                    @foreach($conversations as $conversation)
                    <a href="{{ route('volunteer.messages', ['user_id' => $conversation['user']->user_id]) }}" 
                       style="display: block; padding: 15px 20px; border-bottom: 1px solid #F0F0F0; text-decoration: none; transition: background 0.2s; {{ request('user_id') == $conversation['user']->user_id ? 'background: #F9FAFB;' : '' }}"
                       onmouseover="this.style.background='#F9FAFB'"
                       onmouseout="this.style.background='{{ request('user_id') == $conversation['user']->user_id ? '#F9FAFB' : 'transparent' }}'">
                        <div style="display: flex; align-items: start; gap: 12px;">
                            <!-- Avatar -->
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: #E8D4A7; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #2C3E50; font-weight: 600; font-size: 14px;">
                                {{ strtoupper(substr($conversation['user']->organization->org_name ?? $conversation['user']->email, 0, 2)) }}
                            </div>
                            
                            <!-- Message Info -->
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
                                    <h3 style="font-size: 14px; font-weight: 600; color: #2C3E50; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $conversation['user']->organization->org_name ?? $conversation['user']->email }}
                                    </h3>
                                    <span style="font-size: 11px; color: #9CA3AF; white-space: nowrap; margin-left: 8px;">
                                        {{ $conversation['latest_message']->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p style="font-size: 13px; color: #6B7280; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ Str::limit($conversation['latest_message']->message, 50) }}
                                </p>
                                @if($conversation['unread_count'] > 0)
                                <span style="display: inline-block; margin-top: 6px; padding: 3px 8px; background: #5BA8C9; color: white; border-radius: 10px; font-size: 11px; font-weight: 600;">
                                    {{ $conversation['unread_count'] }} new
                                </span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Right Panel - Messages -->
        <div style="flex: 1; display: flex; flex-direction: column;">
            @if($selectedUser)
                <!-- Chat Header -->
                <div style="padding: 20px 25px; border-bottom: 2px solid #E5E7EB; background: #F9FAFB;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 45px; height: 45px; border-radius: 50%; background: #7CB5B3; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
                            {{ strtoupper(substr($selectedUser->organization->org_name ?? $selectedUser->email, 0, 2)) }}
                        </div>
                        <div>
                            <h3 style="font-size: 16px; font-weight: 600; color: #2C3E50; margin: 0;">
                                {{ $selectedUser->organization->org_name ?? $selectedUser->email }}
                            </h3>
                            <p style="font-size: 12px; color: #6B7280; margin: 0;">Organization</p>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div style="flex: 1; overflow-y: auto; padding: 25px; background: #F9FAFB;">
                    @if($messages->isEmpty())
                        <div style="text-align: center; padding: 60px 20px;">
                            <svg style="width: 70px; height: 70px; color: #D1D5DB; margin: 0 auto 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p style="font-size: 14px; color: #6B7280; font-weight: 500;">No messages yet</p>
                            <p style="font-size: 12px; color: #9CA3AF; margin-top: 5px;">Start the conversation!</p>
                        </div>
                    @else
                        @foreach($messages as $message)
                            @php
                                $isCurrentUser = $message->sender_id == auth()->id();
                            @endphp
                            <div style="margin-bottom: 20px; display: flex; {{ $isCurrentUser ? 'justify-content: flex-end;' : 'justify-content: flex-start;' }}">
                                <div style="max-width: 70%;">
                                    <div style="background: {{ $isCurrentUser ? '#7CB5B3' : 'white' }}; color: {{ $isCurrentUser ? 'white' : '#2C3E50' }}; padding: 12px 16px; border-radius: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                                        <p style="margin: 0; font-size: 14px; line-height: 1.5;">{{ $message->message }}</p>
                                    </div>
                                    <div style="margin-top: 5px; font-size: 11px; color: #9CA3AF; {{ $isCurrentUser ? 'text-align: right;' : 'text-align: left;' }}">
                                        {{ $message->created_at->format('M d, g:i A') }}
                                        @if($isCurrentUser && $message->is_read)
                                            <span style="color: #7CB5B3;">✓✓</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Message Input -->
                <div style="padding: 20px 25px; border-top: 2px solid #E5E7EB; background: white;">
                    <form method="POST" action="{{ route('volunteer.messages.send') }}" style="display: flex; gap: 12px; align-items: center;">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $selectedUser->user_id }}">
                        <input type="text" name="message" placeholder="Type your message..." required
                               style="flex: 1; padding: 12px 16px; border: 2px solid #D1D5DB; border-radius: 12px; font-size: 14px; outline: none;">
                        <button type="submit" 
                                style="padding: 12px 24px; background: #7CB5B3; color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                            Send
                        </button>
                    </form>
                </div>
            @else
                <!-- No Conversation Selected -->
                <div style="flex: 1; display: flex; align-items: center; justify-content: center; background: #F9FAFB;">
                    <div style="text-align: center;">
                        <svg style="width: 90px; height: 90px; color: #D1D5DB; margin: 0 auto 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <h3 style="font-size: 18px; font-weight: 600; color: #4A5568; margin-bottom: 8px;">Select a conversation</h3>
                        <p style="font-size: 14px; color: #9CA3AF;">Choose a conversation from the left to view messages</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection