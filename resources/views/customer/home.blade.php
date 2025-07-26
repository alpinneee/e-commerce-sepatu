@extends('layouts.customer')

@section('title', 'Beranda')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative">
        <div class="mx-auto max-w-7xl">
            <div class="relative z-10 pt-14 lg:w-full lg:max-w-2xl">
                <div class="relative px-6 py-32 sm:py-40 lg:px-8 lg:py-56 lg:pr-0">
                    <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                            Koleksi Sepatu Terbaru
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-gray-600">
                            Temukan koleksi sepatu terbaik untuk pria dan wanita. Kualitas premium dengan harga terjangkau.
                        </p>
                        <div class="mt-10 flex items-center gap-x-6">
                            <a href="{{ route('products.index') }}" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                Belanja Sekarang
                            </a>
                            <a href="{{ route('categories.index') }}" class="text-sm font-semibold leading-6 text-gray-900">
                                Lihat Kategori <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="aspect-[3/2] object-cover lg:aspect-auto lg:h-full lg:w-full" src="https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Sepatu">
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Produk Unggulan</h2>
            <a href="{{ route('products.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-500">
                Lihat Semua <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            @forelse($featuredProducts as $product)
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
            @empty
                <div class="col-span-full py-10 text-center">
                    <p class="text-gray-500">Tidak ada produk unggulan saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Categories Section -->
    <div class="bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Kategori</h2>
                <a href="{{ route('categories.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-500">
                    Lihat Semua <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($categories as $category)
                    <a href="{{ route('products.category', $category->slug) }}" class="group">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-100 sm:aspect-h-3 sm:aspect-w-2">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-full w-full object-cover object-center group-hover:opacity-75">
                            @else
                                <div class="flex h-full items-center justify-center bg-gray-100 group-hover:bg-gray-200">
                                    <span class="text-xl font-medium text-gray-600">{{ $category->name }}</span>
                                </div>
                            @endif
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-gray-900">{{ $category->name }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ $category->products_count ?? 0 }} produk</p>
                    </a>
                @empty
                    <div class="col-span-full py-10 text-center">
                        <p class="text-gray-500">Tidak ada kategori saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sale Products Section -->
    @if($saleProducts->isNotEmpty())
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Produk Diskon</h2>
                <a href="{{ route('products.index') }}?sale=1" class="text-sm font-semibold text-blue-600 hover:text-blue-500">
                    Lihat Semua <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @foreach($saleProducts as $product)
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
                            <div class="absolute top-0 right-0 bg-red-500 text-white px-2 py-1 text-xs font-bold">
                                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                            </div>
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
                                <p class="text-sm font-medium text-gray-900">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection 