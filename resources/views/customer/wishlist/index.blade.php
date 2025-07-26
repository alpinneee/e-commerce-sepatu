@extends('layouts.customer')

@section('title', 'My Wishlist - Toko Sepatu')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Wishlist</h1>
                    <p class="text-gray-600 mt-2">Save your favorite products for later</p>
                </div>
                @if($wishlistItems->count() > 0)
                    <button onclick="clearWishlist()" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Clear All
                    </button>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($wishlistItems->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlistItems as $item)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Product Image -->
                        <div class="relative">
                            @if($item->product->images->count() > 0)
                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Wishlist Button -->
                            <button onclick="removeFromWishlist({{ $item->product->id }})" 
                                    class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>

                            <!-- Category Badge -->
                            @if($item->product->category)
                                <div class="absolute top-2 left-2">
                                    <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                        {{ $item->product->category->name }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-blue-600">
                                    {{ $item->product->name }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ Str::limit($item->product->description, 80) }}
                            </p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xl font-bold text-gray-900">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </span>
                                
                                @if($item->product->stock > 0)
                                    <span class="text-green-600 text-sm font-medium">In Stock</span>
                                @else
                                    <span class="text-red-600 text-sm font-medium">Out of Stock</span>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                @if($item->product->stock > 0)
                                    <button onclick="addToCart({{ $item->product->id }})" 
                                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                        Add to Cart
                                    </button>
                                @else
                                    <button disabled 
                                            class="w-full bg-gray-400 text-white py-2 px-4 rounded-md cursor-not-allowed">
                                        Out of Stock
                                    </button>
                                @endif
                                
                                <a href="{{ route('products.show', $item->product->slug) }}" 
                                   class="block w-full text-center border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($wishlistItems->hasPages())
                <div class="mt-8">
                    {{ $wishlistItems->links() }}
                </div>
            @endif
        @else
            <!-- Empty Wishlist -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-600 mb-6">Start adding products to your wishlist to save them for later.</p>
                <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Clear Wishlist Confirmation Modal -->
<div id="clearWishlistModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Clear Wishlist</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to remove all items from your wishlist? This action cannot be undone.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmClearWishlist" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Clear
                </button>
                <button onclick="closeClearWishlistModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function removeFromWishlist(productId) {
    if (confirm('Are you sure you want to remove this product from your wishlist?')) {
        fetch('/wishlist/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error removing product from wishlist');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing product from wishlist');
        });
    }
}

function addToCart(productId) {
    // Redirect to product page with add to cart parameter
    window.location.href = `/products/${productId}?add_to_cart=1&quantity=1`;
}

function clearWishlist() {
    document.getElementById('clearWishlistModal').classList.remove('hidden');
}

function closeClearWishlistModal() {
    document.getElementById('clearWishlistModal').classList.add('hidden');
}

document.getElementById('confirmClearWishlist').addEventListener('click', function() {
    fetch('/wishlist/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            location.reload();
        } else {
            alert('Error clearing wishlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error clearing wishlist');
    });
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('clearWishlistModal');
    if (event.target === modal) {
        closeClearWishlistModal();
    }
}
</script>
@endsection 