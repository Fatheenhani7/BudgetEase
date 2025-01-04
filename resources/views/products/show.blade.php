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
                        document.getElementById('reportForm').reset();
                    }

                    function showNotification(message, type = 'success') {
                        const notification = document.createElement('div');
                        notification.className = `fixed top-4 right-4 px-6 py-3 rounded shadow-lg z-50 ${
                            type === 'success' ? 'bg-green-500' : 'bg-red-500'
                        } text-white`;
                        notification.textContent = message;
                        document.body.appendChild(notification);
                        
                        setTimeout(() => {
                            notification.remove();
                        }, 3000);
                    }

                    document.getElementById('reportForm').addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const formData = new FormData(this);
                        const data = {
                            product_id: formData.get('product_id'),
                            reason: formData.get('reason'),
                            description: formData.get('description')
                        };

                        fetch('{{ route("product.report") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(async response => {
                            const data = await response.json();
                            if (!response.ok) {
                                throw new Error(data.message || 'An error occurred');
                            }
                            return data;
                        })
                        .then(data => {
                            showNotification(data.message, 'success');
                            closeReportModal();
                        })
                        .catch(error => {
                            showNotification(error.message, 'error');
                            console.error('Error:', error);
                        });
                    });
                </script>

                @if(auth()->check() && auth()->id() !== $product->seller_id)
                    <div class="p-8 border-t">
                        <h2 class="text-xl font-bold mb-4">Rate this Product</h2>
                        
                        @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('products.rate', $product) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                                <div class="flex space-x-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="rating" value="{{ $i }}" class="mr-2" required>
                                            <span>{{ $i }} Star{{ $i !== 1 ? 's' : '' }}</span>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Review (Optional)</label>
                                <textarea name="review" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                            </div>

                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Submit Rating
                            </button>
                        </form>
                    </div>
                @endif

                @if($product->ratings->count() > 0)
                    <div class="p-8 border-t">
                        <h2 class="text-xl font-bold mb-4">Product Reviews</h2>
                        <div class="space-y-4">
                            @foreach($product->ratings as $rating)
                                <div class="bg-gray-50 p-4 rounded">
                                    <div class="flex items-center mb-2">
                                        <div class="text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating->rating)
                                                    ★
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-gray-600">{{ $rating->user->name }}</span>
                                    </div>
                                    @if($rating->review)
                                        <p class="text-gray-700">{{ $rating->review }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
