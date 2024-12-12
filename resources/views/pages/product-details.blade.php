@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Product Images -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div x-data="{ activeImage: null }" x-init="activeImage = '{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_url) }}'">
                <div class="relative bg-gray-100">
                    <img :src="activeImage" 
                         alt="{{ $product->title }}" 
                         class="w-full h-[500px] object-contain mx-auto p-4">
                </div>

                <!-- Thumbnail Gallery -->
                @if($product->images->count() > 1)
                    <div class="p-4 flex gap-4 overflow-x-auto bg-white">
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->image_url) }}" 
                                 alt="{{ $product->title }}" 
                                 class="w-24 h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition"
                                 @click="activeImage = '{{ asset('storage/' . $image->image_url) }}'"
                                 :class="{'ring-2 ring-blue-500': activeImage === '{{ asset('storage/' . $image->image_url) }}'}"
                            >
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h1 class="text-3xl font-bold mb-4">{{ $product->title }}</h1>
            
            <div class="flex items-center justify-between mb-6">
                <span class="text-2xl font-bold text-blue-600">LKR {{ number_format($product->price, 2) }}</span>
                <span class="text-gray-500">{{ $product->location }}</span>
            </div>

            <div class="border-t border-gray-200 py-4">
                <h2 class="text-xl font-semibold mb-2">Description</h2>
                <div class="mt-4">
                    <p class="text-gray-600">{{ $product->description }}</p>
                </div>
                <div class="mt-4">
                    <p class="text-lg font-semibold">Price: LKR {{ number_format($product->price, 2) }}</p>
                </div>
                
                <!-- Add Chat with Seller button -->
                @if(Auth::check() && Auth::id() !== $product->seller_id)
                    <div class="mt-6">
                        <form action="{{ route('chat.store', $product) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                                Chat with Seller
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-200 py-4">
                <h2 class="text-xl font-semibold mb-2">Details</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-500">Category:</span>
                        <span class="ml-2">{{ $product->category }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Condition:</span>
                        <span class="ml-2">{{ $product->condition_status }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Posted on:</span>
                        <span class="ml-2">{{ $product->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seller Information -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Seller Information</h2>
            <div class="flex items-center space-x-4 mb-4">
                @if($product->seller->profile_picture)
                    <img src="{{ asset('storage/' . $product->seller->profile_picture) }}" 
                         alt="{{ $product->seller->username }}" 
                         class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500 text-xl">{{ substr($product->seller->username, 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <h3 class="text-lg font-semibold">{{ $product->seller->username }}</h3>
                    <p class="text-gray-600">{{ $product->seller->email }}</p>
                    <p class="text-gray-500 text-sm">Member since {{ $product->seller->created_at->format('M Y') }}</p>
                    <a href="{{ route('marketplace.seller.products', $product->seller) }}" 
                       class="text-blue-500 hover:text-blue-600 text-sm mt-1 inline-block">
                        View all products from this seller
                    </a>
                </div>
            </div>
            
            @if(Auth::check() && Auth::id() !== $product->seller_id)
                <form action="{{ route('chat.store', $product) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition">
                        Contact Seller
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
