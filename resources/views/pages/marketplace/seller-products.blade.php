@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Seller Info -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">{{ $seller->username }}'s Products</h1>
                    <p class="text-gray-600">Member since {{ $seller->created_at->format('M Y') }}</p>
                </div>
                @if(Auth::check() && Auth::id() !== $seller->id)
                    <form action="{{ route('chat.store', ['product' => $products->first()]) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                            Message Seller
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <a href="{{ route('marketplace.show', $product) }}" class="block">
                        @if($product->images->where('is_primary', true)->first())
                            <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_url) }}" 
                                 alt="{{ $product->title }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">No image</span>
                            </div>
                        @endif
                    </a>

                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">
                            <a href="{{ route('marketplace.show', $product) }}" class="hover:text-blue-500">
                                {{ $product->title }}
                            </a>
                        </h3>
                        <p class="text-gray-600 mb-2">{{ Str::limit($product->description, 100) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-blue-600">LKR {{ number_format($product->price, 2) }}</span>
                            <span class="text-sm text-gray-500">{{ $product->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No products found.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
