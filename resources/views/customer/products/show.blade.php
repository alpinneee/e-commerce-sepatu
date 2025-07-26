@extends('layouts.customer')

@section('title', $product->name)

@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('products.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Produk</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('products.category', $product->category->slug) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">{{ $product->category->name }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="mt-8 grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-2">
            <!-- Product Images -->
            <div class="lg:col-span-1">
                <div x-data="{ activeImage: 0 }">
                    <!-- Main Image -->
                    <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg">
                        @if($product->images->isNotEmpty())
                            @foreach($product->images as $index => $image)
                                <div x-show="activeImage === {{ $index }}" class="h-full w-full">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                        alt="{{ $product->name }}" 
                                        class="h-full w-full object-cover object-center">
                                </div>
                            @endforeach
                        @else
                            <div class="flex h-full items-center justify-center bg-gray-100">
                                <svg class="h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnail Images -->
                    @if($product->images->count() > 1)
                        <div class="mt-4 grid grid-cols-4 gap-2">
                            @foreach($product->images as $index => $image)
                                <button 
                                    @click="activeImage = {{ $index }}" 
                                    class="aspect-h-1 aspect-w-1 overflow-hidden rounded-md"
                                    :class="{ 'ring-2 ring-blue-500': activeImage === {{ $index }} }"
                                >
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                        alt="{{ $product->name }}" 
                                        class="h-full w-full object-cover object-center">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="lg:col-span-1">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $product->name }}</h1>
                
                <div class="mt-4">
                    <h2 class="sr-only">Product information</h2>
                    <div class="flex items-center">
                        @if($product->discount_price)
                            <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                            <p class="ml-3 text-lg text-gray-500 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <span class="ml-3 rounded-md bg-red-500 px-2 py-1 text-xs font-semibold text-white">
                                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                            </span>
                        @else
                            <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= ($product->reviews->avg('rating') ?? 0))
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <p class="ml-2 text-sm text-gray-500">{{ $product->reviews->count() }} ulasan</p>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-5 w-5 items-center justify-center rounded-full {{ $product->stock > 0 ? 'bg-green-100' : 'bg-red-100' }}">
                            <div class="h-2 w-2 rounded-full {{ $product->stock > 0 ? 'bg-green-500' : 'bg-red-500' }}"></div>
                        </div>
                        <p class="text-sm {{ $product->stock > 0 ? 'text-green-700' : 'text-red-700' }}">
                            {{ $product->stock > 0 ? 'Stok tersedia' : 'Stok habis' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-900">Deskripsi</h3>
                    <div class="mt-2 space-y-4 text-base text-gray-700">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-900">SKU</h3>
                        <p class="text-sm text-gray-500">{{ $product->sku }}</p>
                    </div>
                    <div class="mt-2 flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-900">Kategori</h3>
                        <a href="{{ route('products.category', $product->category->slug) }}" class="text-sm text-blue-600 hover:text-blue-500">{{ $product->category->name }}</a>
                    </div>
                    <div class="mt-2 flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-900">Berat</h3>
                        <p class="text-sm text-gray-500">{{ $product->weight }} gram</p>
                    </div>
                </div>

                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-8">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <button type="button" onclick="decrementQuantity()" class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 focus:z-10 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    -
                                </button>
                                <input type="number" name="quantity" id="quantity" min="1" max="{{ $product->stock }}" value="1" class="block w-full min-w-0 flex-1 rounded-none border-gray-300 px-3 py-2 text-center focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <button type="button" onclick="incrementQuantity({{ $product->stock }})" class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 focus:z-10 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    +
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="flex w-full items-center justify-center rounded-md border border-transparent bg-blue-600 px-8 py-3 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Tambahkan ke Keranjang
                        </button>
                    </form>
                @else
                    <div class="mt-8">
                        <button disabled class="flex w-full items-center justify-center rounded-md border border-transparent bg-gray-300 px-8 py-3 text-base font-medium text-gray-500 cursor-not-allowed">
                            Stok Habis
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-16 border-t border-gray-200 pt-8">
            <h2 class="text-xl font-bold tracking-tight text-gray-900">Ulasan Pelanggan</h2>
            
            <div class="mt-6">
                @if($product->reviews->isEmpty())
                    <div class="py-10 text-center">
                        <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
                    </div>
                @else
                    <div class="space-y-8">
                        @foreach($product->reviews as $review)
                            <div class="border-b border-gray-200 pb-6">
                                <div class="flex items-center mb-2">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <div class="flex items-center mb-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $review->user->name }}</span>
                                    <span class="mx-2 text-gray-500">•</span>
                                    <time datetime="{{ $review->created_at->format('Y-m-d') }}" class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</time>
                                </div>
                                <div class="mt-2 space-y-2 text-sm text-gray-600">
                                    <p>{{ $review->comment }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @auth
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900">Tambahkan Ulasan</h3>
                    <form action="{{ route('products.review', $product) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="mb-4">
                            <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                            <div class="mt-1 flex items-center">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only">
                                            <svg class="h-6 w-6 text-gray-300 hover:text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            @error('rating')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700">Komentar</label>
                            <div class="mt-1">
                                <textarea id="comment" name="comment" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Bagikan pengalaman Anda dengan produk ini"></textarea>
                            </div>
                            @error('comment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-8 rounded-md bg-gray-50 p-4">
                    <p class="text-sm text-gray-700">
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">Login</a> untuk menambahkan ulasan.
                    </p>
                </div>
            @endauth
        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-16 border-t border-gray-200 pt-8">
                <h2 class="text-xl font-bold tracking-tight text-gray-900">Produk Terkait</h2>
                
                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="group relative">
                            <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                                @if($relatedProduct->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $relatedProduct->images->where('is_primary', true)->first()->image_path) }}" 
                                        alt="{{ $relatedProduct->name }}" 
                                        class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                                @else
                                    <div class="flex h-full items-center justify-center bg-gray-100">
                                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                @if($relatedProduct->discount_price)
                                    <div class="absolute top-0 right-0 bg-red-500 text-white px-2 py-1 text-xs font-bold">
                                        -{{ round((($relatedProduct->price - $relatedProduct->discount_price) / $relatedProduct->price) * 100) }}%
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $relatedProduct->name }}
                                        </a>
                                    </h3>
                                </div>
                                <div>
                                    @if($relatedProduct->discount_price)
                                        <p class="text-sm font-medium text-gray-900">Rp {{ number_format($relatedProduct->discount_price, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500 line-through">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</p>
                                    @else
                                        <p class="text-sm font-medium text-gray-900">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function decrementQuantity() {
        const input = document.getElementById('quantity');
        if (input.value > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    function incrementQuantity(maxStock) {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) < maxStock) {
            input.value = parseInt(input.value) + 1;
        }
    }
</script>
@endpush
@endsection 