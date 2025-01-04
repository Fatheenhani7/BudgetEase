@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Profile Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-start space-x-6">
                <!-- Profile Picture -->
                <div class="flex-shrink-0">
                    @if($user->profile && $user->profile->profile_picture_url)
                        <img src="{{ Storage::url($user->profile->profile_picture_url) }}" 
                             alt="Profile picture"
                             class="w-32 h-32 object-cover rounded-full">
                    @else
                        <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-4xl text-gray-500">{{ substr($user->username, 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->username }}</h1>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" 
                           class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                            Edit Profile
                        </a>
                    </div>

                    @if($user->profile)
                        @if($user->profile->phone_number)
                            <p class="mt-2 text-gray-600">
                                <span class="font-medium">Phone:</span> {{ $user->profile->phone_number }}
                            </p>
                        @endif
                        @if($user->profile->bio)
                            <p class="mt-2 text-gray-600">
                                <span class="font-medium">Bio:</span> {{ $user->profile->bio }}
                            </p>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabs Container -->
        <div class="bg-white rounded-lg shadow-lg p-6" x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'products' }">
            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="#products"
                       @click="activeTab = 'products'"
                       :class="{ 'border-blue-500 text-blue-600': activeTab === 'products', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'products' }"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        My Products
                    </a>
                    <a href="#messages"
                       @click="activeTab = 'messages'"
                       :class="{ 'border-blue-500 text-blue-600': activeTab === 'messages', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'messages' }"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Messages
                    </a>
                    <a href="#reports"
                       @click="activeTab = 'reports'"
                       :class="{ 'border-blue-500 text-blue-600': activeTab === 'reports', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'reports' }"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm relative">
                        Reported Products
                        @if($reportedProducts->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ $reportedProducts->count() }}
                            </span>
                        @endif
                    </a>
                </nav>
            </div>

            <!-- Products Tab -->
            <div x-show="activeTab === 'products'" class="mt-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex-1 max-w-xl mr-4">
                        <div class="relative">
                            <input type="text" 
                                   id="product-search" 
                                   placeholder="Search your products..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('marketplace.create') }}" 
                       class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
                        Add New Product
                    </a>
                </div>

                @if($products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                                @if($product->images->where('is_primary', true)->first())
                                    <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_url) }}" 
                                         alt="{{ $product->title }}" 
                                         class="w-full h-48 object-cover">
                                @endif
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold mb-2">{{ $product->title }}</h3>
                                    <p class="text-gray-600 mb-2">{{ Str::limit($product->description, 100) }}</p>
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-lg font-bold text-blue-600">LKR {{ number_format($product->price, 2) }}</span>
                                        <span class="text-sm text-gray-500">{{ $product->location }}</span>
                                    </div>
                                    <div class="flex justify-between gap-2">
                                        <a href="{{ route('marketplace.show', $product->id) }}" 
                                           class="flex-1 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition text-center">
                                            View
                                        </a>
                                        <a href="{{ route('marketplace.edit', $product->id) }}" 
                                           class="flex-1 bg-yellow-500 text-white py-2 rounded-md hover:bg-yellow-600 transition text-center">
                                            Edit
                                        </a>
                                        <form action="{{ route('profile.delete-product', $product->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this product?');"
                                              class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600 transition text-center">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600 mb-4">You haven't listed any products yet.</p>
                        <a href="{{ route('marketplace.create') }}" 
                           class="inline-block bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition">
                            List Your First Product
                        </a>
                    </div>
                @endif
            </div>

            <!-- Messages Tab -->
            <div x-show="activeTab === 'messages'" class="mt-6">
                <div class="space-y-4">
                    @if($conversations->count() > 0)
                        @foreach($conversations as $conversation)
                            <div class="bg-white border rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between">
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
                                            @php
                                                $otherUser = $conversation->buyer_id === Auth::id() ? $conversation->seller : $conversation->buyer;
                                            @endphp
                                            <h3 class="font-semibold">
                                                @if($conversation->product)
                                                    {{ $conversation->product->title }}
                                                @else
                                                    Support Chat
                                                @endif
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                With: {{ $otherUser->username }}
                                                @if($conversation->lastMessage)
                                                    · {{ $conversation->lastMessage->created_at->diffForHumans() }}
                                                @endif
                                            </p>
                                            @if($conversation->lastMessage)
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ Str::limit($conversation->lastMessage->message, 50) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('chat.show', $conversation) }}" 
                                       class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                                        View Chat
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No messages found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reported Products Tab -->
            <div x-show="activeTab === 'reports'" class="mt-6">
                @if($reportedProducts->count() > 0)
                    <div class="space-y-6">
                        @foreach($reportedProducts as $report)
                            <div class="bg-white border rounded-lg overflow-hidden shadow-sm">
                                <div class="p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $report->product->title }}</h3>
                                            <p class="text-sm text-gray-500">Reported on: {{ $report->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                                            @if($report->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($report->status === 'reviewed') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </div>

                                    <div class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-900">Report Reason:</h4>
                                        <p class="mt-1 text-gray-600">{{ $report->reason }}</p>
                                    </div>
                                    
                                    @if($report->admin_notes)
                                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                            <h4 class="text-sm font-medium text-blue-900">Admin Feedback:</h4>
                                            <p class="mt-1 text-blue-800">{{ $report->admin_notes }}</p>
                                        </div>
                                    @endif

                                    <div class="mt-4 flex justify-between items-center">
                                        <a href="{{ route('marketplace.show', $report->product->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Product →
                                        </a>
                                        
                                        @php
                                            $adminUser = \App\Models\UserInfo::where('email', 'adminb@gmail.com')->first();
                                            $conversation = \App\Models\Conversation::where(function($query) use ($report, $adminUser) {
                                                $query->where('buyer_id', Auth::id())
                                                    ->where('seller_id', $adminUser->id)
                                                    ->where('product_id', $report->product_id);
                                            })->orWhere(function($query) use ($report, $adminUser) {
                                                $query->where('seller_id', Auth::id())
                                                    ->where('buyer_id', $adminUser->id)
                                                    ->where('product_id', $report->product_id);
                                            })->first();
                                        @endphp

                                        @if($conversation)
                                            <a href="{{ route('chat.show', $conversation->id) }}" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                Continue Chat with Admin
                                            </a>
                                        @else
                                            <form action="{{ route('chat.store', $report->product->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <input type="hidden" name="admin_chat" value="1">
                                                <button type="submit" 
                                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                    Chat with Admin
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No reported products found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('product-search');
    const productCards = document.querySelectorAll('.grid > div');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        productCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            const location = card.querySelector('.text-gray-500').textContent.toLowerCase();

            if (title.includes(searchTerm) || 
                description.includes(searchTerm) || 
                location.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
@endsection