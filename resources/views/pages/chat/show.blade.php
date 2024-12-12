@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col h-[600px]">
            <!-- Header -->
            <div class="p-4 bg-gray-50 border-b flex items-center space-x-4">
                <a href="{{ route('chat.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                @php
                    $otherUser = $conversation->buyer_id === Auth::id() ? $conversation->seller : $conversation->buyer;
                @endphp
                <div>
                    <h2 class="font-semibold">{{ $otherUser->username }}</h2>
                    <p class="text-sm text-gray-500">{{ $conversation->product->title }}</p>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messages-container">
                @foreach($conversation->messages as $message)
                    <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%] {{ $message->sender_id === Auth::id() ? 'bg-blue-500 text-white' : 'bg-gray-100' }} rounded-lg px-4 py-2">
                            <p class="text-sm">{{ $message->message }}</p>
                            <p class="text-xs {{ $message->sender_id === Auth::id() ? 'text-blue-100' : 'text-gray-500' }} mt-1">
                                {{ $message->created_at->format('g:i A') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t">
                <form action="{{ route('chat.message', $conversation) }}" method="POST" class="flex space-x-4">
                    @csrf
                    <input type="text" 
                           name="message" 
                           placeholder="Type your message..." 
                           class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                           required>
                    <button type="submit" 
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Scroll to bottom of messages container
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
</script>
@endsection
