@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">Place Advertisement</h1>
        
        <form action="{{ route('marketplace.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Product Title</label>
                <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Price (LKR)</label>
                <input type="number" name="price" required min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select a Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->name }}">{{ $category->name }} - {{ $category->description }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Condition</label>
                <select name="condition_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Condition</option>
                    <option value="New">New</option>
                    <option value="Like New">Like New</option>
                    <option value="Used">Used</option>
                    <option value="Fair">Fair</option>
                </select>
            </div>

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
                        <option value="{{ $district }}" {{ old('location') == $district ? 'selected' : '' }}>
                            {{ $district }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Product Images</label>
                <input type="file" name="images[]" multiple required accept="image/*" class="mt-1 block w-full">
                <p class="mt-1 text-sm text-gray-500">You can select multiple images. First image will be the primary image.</p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Post Advertisement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
