@props(['product'])

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <a href="{{ route('products.show', $product) }}">
        <div class="relative pb-[100%] overflow-hidden">
            @if($product->discount_price && $product->discount_price < $product->price)
                <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-md z-10">
                    {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF
                </span>
            @endif
            
            <img 
                src="{{ $product->image_url }}" 
                alt="{{ $product->name }}" 
                class="absolute top-0 left-0 w-full h-full object-cover"
                onerror="this.src='{{ asset('images/placeholder.png') }}'"
            >
        </div>
    </a>
    
    <div class="p-4">
        <div class="flex justify-between items-start mb-1">
            <h3 class="font-medium text-gray-900 truncate">
                <a href="{{ route('products.show', $product) }}" class="hover:text-blue-500">
                    {{ $product->name }}
                </a>
            </h3>
            <button type="button" 
                class="text-gray-400 hover:text-red-500 transition-colors wishlist-toggle" 
                data-product-id="{{ $product->id }}"
                title="Add to wishlist">
                <svg class="w-5 h-5" fill="{{ in_array($product->id, session('wishlist', [])) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        </div>
        
        <p class="text-sm text-gray-500 mb-2 truncate">{{ $product->category->name }}</p>
        
        <div class="flex items-center mb-2">
            <div class="flex text-yellow-400">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= round($product->reviews_avg_rating ?? 0))
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @else
                        <svg class="w-4 h-4 fill-current text-gray-300" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @endif
                @endfor
            </div>
            <span class="text-xs text-gray-500 ml-1">({{ $product->reviews_count ?? 0 }})</span>
        </div>
        
        <div class="flex items-center justify-between">
            <div>
                @if($product->discount_price && $product->discount_price < $product->price)
                    <span class="text-gray-400 line-through text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="text-red-500 font-bold">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                @else
                    <span class="text-gray-900 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>
            
            <button type="button" 
                class="bg-blue-500 hover:bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors add-to-cart" 
                data-product-id="{{ $product->id }}"
                title="Add to cart">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </button>
        </div>
    </div>
</div> 