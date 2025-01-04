@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('admin.index') }}" class="inline-flex items-center text-blue-500 hover:text-blue-700 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Admin Dashboard
        </a>

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
                            <p class="text-gray-600">Member since {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        @if($user->email !== 'adminb@gmail.com')
                            <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition"
                                        onclick="return confirm('Are you sure? This will delete the user and all their data.')">
                                    Delete User
                                </button>
                            </form>
                        @endif
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

            <!-- User Stats -->
            <div class="grid grid-cols-1 gap-4 mt-8">
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-blue-700">Products</h3>
                    <p class="text-2xl font-bold">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <!-- Tabs Container -->
        <div class="bg-white rounded-lg shadow-lg p-6" x-data="{ activeTab: 'products' }">
            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="#products"
                       @click="activeTab = 'products'"
                       :class="{ 'border-blue-500 text-blue-600': activeTab === 'products', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'products' }"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Products
                    </a>
                </nav>
            </div>

            <!-- Products Tab -->
            <div x-show="activeTab === 'products'" class="mt-6">
                @if($user->products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($user->products as $product)
                            <div class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                                @if($product->images->where('is_primary', true)->first())
                                    <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_url) }}" 
                                         alt="{{ $product->title }}" 
                                         class="w-full h-48 object-cover">
                                @endif
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold mb-2">{{ $product->title }}</h3>
                                    <p class="text-gray-600 mb-2">{{ Str::limit($product->description, 100) }}</p>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-lg font-bold text-blue-600">LKR {{ number_format($product->price, 2) }}</span>
                                        <span class="text-sm text-gray-500">{{ $product->location }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500 mb-4">
                                        <p>Category: {{ $product->category }}</p>
                                        <p>Posted on: {{ $product->created_at ? $product->created_at->format('M d, Y') : 'N/A' }}</p>
                                    </div>
                                    <div class="flex justify-between gap-2">
                                        <a href="{{ route('marketplace.show', $product->id) }}" 
                                           class="flex-1 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition text-center">
                                            View
                                        </a>
                                        <form action="{{ route('admin.deleteProduct', $product->id) }}" 
                                              method="POST" 
                                              class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600 transition text-center"
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
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
                        <p class="text-gray-500">No products found for this user.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
