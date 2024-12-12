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
                            <h1 class="text-2xl font-bold text-gray-900">{{ '@' . $user->username }}</h1>
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

        <!-- User's Products -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">My Products</h2>
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
                                                class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600 transition">
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
                    <p class="text-gray-500 mb-4">You haven't listed any products yet.</p>
                    <a href="{{ route('marketplace.create') }}" 
                       class="inline-block bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition">
                        Create Your First Listing
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection