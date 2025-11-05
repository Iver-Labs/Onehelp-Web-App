@extends('layouts.volunteer-app')

@section('title', 'Messages - OneHelp')

@section('content')
<div class="max-w-6xl">
    <h1 class="text-3xl font-bold mb-8" style="color: #2C3E50;">Messages</h1>
    
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
        <div class="flex h-[600px]">
            <!-- Message List Sidebar -->
            <div class="w-1/3 border-r border-gray-200 overflow-y-auto">
                <!-- Search -->
                <div class="p-4 border-b border-gray-200">
                    <input type="text" placeholder="Search messages..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 text-sm" style="--tw-ring-color: #7CC5C5;">
                </div>
                
                <!-- Sample Message -->
                <div class="p-4 border-b border-gray-200 hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0" style="background-color: #7CC5C5;">
                            LS
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-semibold text-sm truncate" style="color: #2C3E50;">Lea Santos</h3>
                                <span class="text-xs text-gray-400">10/16/2024</span>
                            </div>
                            <p class="text-sm text-gray-600 truncate">New message about photography exhibition</p>
                        </div>
                    </div>
                </div>
                
                <!-- Empty State -->
                <div class="p-8 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500 font-medium">No more messages</p>
                    <p class="text-gray-400 text-sm">Your conversations will appear here</p>
                </div>
            </div>
            
            <!-- Message Content Area -->
            <div class="flex-1 flex flex-col">
                <!-- Message Header -->
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm" style="background-color: #7CC5C5;">
                            LS
                        </div>
                        <div>
                            <h3 class="font-semibold" style="color: #2C3E50;">Lea Santos</h3>
                            <p class="text-xs text-gray-500">Organization Representative</p>
                        </div>
                    </div>
                </div>
                
                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
                    <!-- Sample Message -->
                    <div class="mb-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0" style="background-color: #7CC5C5;">
                                LS
                            </div>
                            <div class="flex-1">
                                <div class="bg-white rounded-lg p-3 shadow-sm">
                                    <p class="text-sm" style="color: #2C3E50;">Hi! Thank you for your interest in the photography exhibition event. We're excited to have you join us!</p>
                                </div>
                                <span class="text-xs text-gray-400 mt-1 block">10/16/2024 2:30 PM</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Message Input -->
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center gap-3">
                        <input type="text" placeholder="Type your message..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 text-sm" style="--tw-ring-color: #7CC5C5;">
                        <button class="px-6 py-2 text-white rounded-lg font-medium hover:opacity-90 transition" style="background-color: #7CC5C5;">
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection