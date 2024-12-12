@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Category Navigation -->
    <div class="bg-white shadow-md rounded-lg mb-8">
        <div class="p-4">
            <h2 class="text-xl font-semibold mb-4">Browse by Category</h2>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('marketplace.index') }}" 
                   class="px-4 py-2 rounded-full {{ request('category') === null ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All Furniture
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('marketplace.index', ['category' => $category->name]) }}" 
                       class="px-4 py-2 rounded-full {{ request('category') === $category->name ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-4">
            <div>
                <select name="location" class="rounded-md border-gray-300" onchange="window.location.href=this.value">
                    <option value="{{ route('marketplace.index', array_merge(request()->except('location'), ['location' => 'all'])) }}"
                            {{ !request('location') || request('location') === 'all' ? 'selected' : '' }}>
                        All Locations
                    </option>
                    @foreach([
                        'Ampara', 'Anuradhapura', 'Badulla', 'Batticaloa', 'Colombo',
                        'Galle', 'Gampaha', 'Hambantota', 'Jaffna', 'Kalutara',
                        'Kandy', 'Kegalle', 'Kilinochchi', 'Kurunegala', 'Mannar',
                        'Matale', 'Matara', 'Monaragala', 'Mullaitivu', 'Nuwara Eliya',
                        'Polonnaruwa', 'Puttalam', 'Ratnapura', 'Trincomalee', 'Vavuniya'
                    ] as $district)
                        <option value="{{ route('marketplace.index', array_merge(request()->except('location'), ['location' => $district])) }}"
                                {{ request('location') === $district ? 'selected' : '' }}>
                            {{ $district }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="sort" class="rounded-md border-gray-300" onchange="window.location.href=this.value">
                    <option value="{{ route('marketplace.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}"
                            {{ request('sort') === 'latest' ? 'selected' : '' }}>
                        Latest
                    </option>
                    <option value="{{ route('marketplace.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}"
                            {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                        Price: Low to High
                    </option>
                    <option value="{{ route('marketplace.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}"
                            {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                        Price: High to Low
                    </option>
                </select>
            </div>
        </div>

        <a href="{{ route('marketplace.create') }}" 
           class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition">
            Place Advertisement
        </a>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow overflow-hidden">
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
                    <div class="text-sm text-gray-500 mb-2">
                        <p>Category: {{ $product->category }}</p>
                        <p>Posted on: {{ $product->created_at->format('M d, Y') }}</p>
                    </div>
                    <a href="{{ route('marketplace.show', $product->id) }}" 
                       class="block w-full text-center bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500">No products found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection
