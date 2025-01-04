@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 bg-gray-50 border-b">
                <h1 class="text-2xl font-bold">Messages</h1>
            </div>

            <div class="divide-y">
                @forelse($conversations as $conversation)
                    @php
                        $otherUser = $conversation->buyer_id === Auth::id() ? $conversation->seller : $conversation->buyer;
                        $unreadCount = $conversation->unreadMessages()->where('sender_id', '!=', Auth::id())->count();
                    @endphp
                    <a href="{{ route('chat.show', $conversation) }}" 
                       class="block p-6 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($conversation->product && $conversation->product->images->where('is_primary', true)->first())
                                        <img src="{{ asset('storage/' . $conversation->product->images->where('is_primary', true)->first()->image_url) }}" 
                                             alt="Product image"
                                             class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-medium">{{ $otherUser->username }}</h3>
                                    @if($conversation->product)
                                        <p class="text-sm text-gray-500">{{ $conversation->product->title }}</p>
                                    @else
                                        <p class="text-sm text-gray-500">Support Chat</p>
                                    @endif
                                    @if($conversation->lastMessage)
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ Str::limit($conversation->lastMessage->message, 50) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                @if($unreadCount > 0)
                                    <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                                <span class="text-sm text-gray-500">
                                    {{ $conversation->lastMessage ? $conversation->lastMessage->created_at->diffForHumans() : '' }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        No conversations yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
