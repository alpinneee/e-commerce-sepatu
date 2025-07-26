@extends('layouts.customer')

@section('title', 'Produk')

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
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Produk</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="mt-6">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Semua Produk</h1>
        </div>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filter Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Filter</h2>
                    
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Category Filter -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Kategori</h3>
                            <div class="space-y-2">
                                @foreach($categories as $category)
                                    <div class="flex items-center">
                                        <input 
                                            id="category-{{ $category->id }}" 
                                            name="category" 
                                            value="{{ $category->id }}" 
                                            type="radio" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500"
                                            {{ request('category') == $category->id ? 'checked' : '' }}
                                        >
                                        <label for="category-{{ $category->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Harga</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label for="min_price" class="sr-only">Harga Minimum</label>
                                    <input 
                                        type="number" 
                                        id="min_price" 
                                        name="min_price" 
                                        placeholder="Min" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        value="{{ request('min_price') }}"
                                        min="{{ $minPrice ?? 0 }}"
                                    >
                                </div>
                                <div>
                                    <label for="max_price" class="sr-only">Harga Maksimum</label>
                                    <input 
                                        type="number" 
                                        id="max_price" 
                                        name="max_price" 
                                        placeholder="Max" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        value="{{ request('max_price') }}"
                                        max="{{ $maxPrice ?? 999999999 }}"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Sort Filter -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Urutkan</h3>
                            <select 
                                name="sort" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama: Z-A</option>
                            </select>
                        </div>

                        <!-- Search -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Cari</h3>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="search" 
                                    placeholder="Cari produk..." 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    value="{{ request('search') }}"
                                >
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <button 
                                type="submit" 
                                class="flex-1 rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                            >
                                Terapkan
                            </button>
                            <a 
                                href="{{ route('products.index') }}" 
                                class="flex-1 rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-200"
                            >
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="lg:col-span-3">
                @if($products->isEmpty())
                    <div class="py-10 text-center">
                        <p class="text-gray-500">Tidak ada produk yang ditemukan.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                        @foreach($products as $product)
                            <div class="group relative">
                                <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                                    @if($product->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_path) }}" 
                                            alt="{{ $product->name }}" 
                                            class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                                    @else
                                        <div class="flex h-full items-center justify-center bg-gray-100">
                                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    @if($product->discount_price)
                                        <div class="absolute top-0 right-0 bg-red-500 text-white px-2 py-1 text-xs font-bold">
                                            -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4 flex justify-between">
                                    <div>
                                        <h3 class="text-sm text-gray-700">
                                            <a href="{{ route('products.show', $product->slug) }}">
                                                <span aria-hidden="true" class="absolute inset-0"></span>
                                                {{ $product->name }}
                                            </a>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">{{ $product->category->name }}</p>
                                    </div>
                                    <div>
                                        @if($product->discount_price)
                                            <p class="text-sm font-medium text-gray-900">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                                            <p class="text-sm text-gray-500 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        @else
                                            <p class="text-sm font-medium text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 