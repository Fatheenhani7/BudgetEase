@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                @if($product->images->count() > 0)
                    <img src="{{ Storage::url($product->images->first()->image_url) }}" alt="{{ $product->title }}" class="w-full h-auto">
                @else
                    <div class="bg-gray-200 w-full h-64 flex items-center justify-center">
                        <span class="text-gray-500">No image available</span>
                    </div>
                @endif
            </div>
            <div class="md:w-1/2 p-8">
                <h1 class="text-2xl font-bold mb-4">{{ $product->title }}</h1>
                <p class="text-gray-600 mb-4">{{ $product->description }}</p>
                <div class="mb-4">
                    <span class="text-gray-700 font-semibold">Price:</span>
                    <span class="text-xl text-green-600 font-bold">LKR {{ number_format($product->price, 2) }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-gray-700 font-semibold">Category:</span>
                    <span class="text-gray-600">{{ $product->category }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-gray-700 font-semibold">Condition:</span>
                    <span class="text-gray-600">{{ $product->condition_status }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-gray-700 font-semibold">Location:</span>
                    <span class="text-gray-600">{{ $product->location }}</span>
                </div>

                <!-- Seller Information -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h2 class="text-lg font-bold mb-3">Seller Information</h2>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3 mb-4">
                            @if($product->seller->profile_image)
                                <img src="{{ asset('storage/' . $product->seller->profile_image) }}" alt="{{ $product->seller->username }}" class="w-12 h-12 rounded-full">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-xl text-gray-600">{{ substr($product->seller->username, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $product->seller->username }}</h3>
                                <p class="text-sm text-gray-500">Member since {{ $product->seller->created_at->format('F Y') }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <span class="text-gray-700 font-semibold">Location:</span>
                            <span class="text-gray-600">{{ $product->seller->location ?? 'Not specified' }}</span>
                        </div>
                        
                        <div>
                            <span class="text-gray-700 font-semibold">Products Listed:</span>
                            <span class="text-gray-600">{{ $product->seller->products()->count() }}</span>
                        </div>

                        <div class="flex items-center mt-4">
                            <a href="{{ route('marketplace.seller-products', $product->seller_id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                View All Products from this Seller
                                <span class="inline-block ml-1">→</span>
                            </a>
                        </div>

                        @if(auth()->id() !== $product->seller_id)
                            <div class="mt-4 space-y-2">
                                <form action="{{ route('chat.store', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 w-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                        </svg>
                                        Contact Seller
                                    </button>
                                </form>

                                <button onclick="openReportModal()" 
                                    class="bg-red-500 hover:bg-red-700 text-white px-6 py-2 rounded w-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd" />
                                    </svg>
                                    Report Product
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Products Section -->
    @if($similarProducts->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Similar Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($similarProducts as $similarProduct)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <a href="{{ route('marketplace.show', $similarProduct) }}">
                        @if($similarProduct->images->count() > 0)
                            <img src="{{ Storage::url($similarProduct->images->first()->image_url) }}" 
                                 alt="{{ $similarProduct->title }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">No image available</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $similarProduct->title }}</h3>
                            <p class="text-green-600 font-bold">LKR {{ number_format($similarProduct->price, 2) }}</p>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $similarProduct->location }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Report Modal -->
    <div id="reportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Report Product</h3>
                    <button onclick="closeReportModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="reportForm" class="mt-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="reason">
                            Reason for Report
                        </label>
                        <select name="reason" id="reason" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select a reason</option>
                            <option value="Inappropriate Content">Inappropriate Content</option>
                            <option value="Misleading Information">Misleading Information</option>
                            <option value="Suspicious Activity">Suspicious Activity</option>
                            <option value="Counterfeit Product">Counterfeit Product</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                            Description
                        </label>
                        <textarea name="description" id="description" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            rows="4" placeholder="Please provide more details about your report"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeReportModal()"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReportModal() {
            document.getElementById('reportModal').classList.remove('hidden');
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
        }

        // Handle report form submission
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch('/products/report', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: this.querySelector('[name="product_id"]').value,
                    reason: this.querySelector('[name="reason"]').value,
                    description: this.querySelector('[name="description"]').value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Thank you for your report. We will review it shortly.');
                    closeReportModal();
                } else {
                    alert(data.message || 'There was an error submitting your report. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error submitting your report. Please try again.');
            });
        });
    </script>
</div>
@endsection
