@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold text-gray-800">Product Reports</h3>
                <p class="text-gray-600 mt-2">View and manage reported products</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" 
               class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                View Reports
            </a>
        </div>
    </div>

    <!-- Users Section -->
    <div class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Manage Users</h2>
            <form action="{{ route('admin.index') }}" method="GET" class="flex items-center">
                <div class="relative">
                    <input type="text" 
                           name="user_search" 
                           value="{{ request('user_search') }}"
                           placeholder="Search users..." 
                           class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute left-3 top-2.5">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Search
                </button>
            </form>
        </div>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->email !== 'adminb@gmail.com')
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('admin.viewUser', $user->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            View Profile
                                        </a>
                                        <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure? This will delete the user and all their products.')">
                                                Delete User
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400">Admin</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Manage Products</h2>
            <form action="{{ route('admin.index') }}" method="GET" class="flex items-center">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search products..." 
                           class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute left-3 top-2.5">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Search
                </button>
            </form>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
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
                        <div class="text-sm text-gray-500 mb-4">
                            <p>Category: {{ $product->category }}</p>
                            <p>Seller: {{ $product->seller->username }}</p>
                            <p>Posted on: {{ $product->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('marketplace.show', $product->id) }}" 
                               class="text-blue-500 hover:text-blue-700">
                                View Details
                            </a>
                            <form action="{{ route('admin.reports.deleteProduct', $product->id) }}" method="POST"
                                  onsubmit="return handleProductDelete(event, {{ $product->id }})">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-500 hover:text-red-700">
                                    Delete Product
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>

<script>
    function handleProductDelete(event, productId) {
        event.preventDefault();
        
        if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            return false;
        }

        fetch(`{{ url('/admin/products') }}/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Show success message
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50';
                notification.textContent = data.message;
                document.body.appendChild(notification);
                
                // Remove the product card from the grid
                const productCard = event.target.closest('.bg-white');
                if (productCard) {
                    productCard.remove();
                }
                
                // Remove notification after 3 seconds
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            } else {
                throw new Error(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            // Show error message
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50';
            notification.textContent = error.message || 'An error occurred while deleting the product';
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
            
            console.error('Error:', error);
        });

        return false;
    }
</script>
@endsection
