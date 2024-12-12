@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Product</h1>

            <form action="{{ route('marketplace.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" value="{{ old('title', $product->title) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="4" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price (LKR)</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select a Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}" {{ old('category', $product->category) == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }} - {{ $category->description }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <select name="location" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Location</option>
                            @foreach([
                                'Ampara', 'Anuradhapura', 'Badulla', 'Batticaloa', 'Colombo',
                                'Galle', 'Gampaha', 'Hambantota', 'Jaffna', 'Kalutara',
                                'Kandy', 'Kegalle', 'Kilinochchi', 'Kurunegala', 'Mannar',
                                'Matale', 'Matara', 'Monaragala', 'Mullaitivu', 'Nuwara Eliya',
                                'Polonnaruwa', 'Puttalam', 'Ratnapura', 'Trincomalee', 'Vavuniya'
                            ] as $district)
                                <option value="{{ $district }}" {{ old('location', $product->location) == $district ? 'selected' : '' }}>
                                    {{ $district }}
                                </option>
                            @endforeach
                        </select>
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Images -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($product->images as $image)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $image->image_url) }}" 
                                         alt="Product image" 
                                         class="w-full h-32 object-cover rounded-lg">
                                    @if($image->is_primary)
                                        <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">Primary</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Add New Images -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Upload New Images</label>
                        <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif"
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                        <p class="mt-1 text-sm text-gray-500">Upload new images to replace the current ones. Accepted formats: JPEG, PNG, GIF. Max size: 2MB per image.</p>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between pt-6">
                        <a href="{{ route('profile.index') }}" 
                           class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition">
                            Update Product
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
