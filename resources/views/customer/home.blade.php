@extends('layouts.customer')

@section('title', 'Beranda')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative">
        <div class="mx-auto max-w-7xl">
            <div class="relative z-10 pt-8 lg:w-full lg:max-w-2xl">
                <div class="relative px-4 py-16 sm:py-24 lg:px-6 lg:py-32 lg:pr-0">
                    <div class="mx-auto max-w-xl lg:mx-0 lg:max-w-lg">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                            Koleksi Sepatu Terbaru
                        </h1>
                        <p class="mt-4 text-base leading-7 text-gray-600">
                            Temukan koleksi sepatu terbaik untuk pria dan wanita. Kualitas premium dengan harga terjangkau.
                        </p>
                        <div class="mt-6 flex items-center gap-x-3">
                            <a href="{{ route('products.index') }}" class="rounded bg-gray-900 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-700 transition">
                                Belanja Sekarang
                            </a>
                            <a href="{{ route('categories.index') }}" class="text-xs font-semibold leading-6 text-gray-700 hover:underline">
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
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Produk Unggulan</h2>
            <a href="{{ route('products.index') }}" class="text-xs font-semibold text-gray-700 hover:underline">
                Lihat Semua <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-6 sm:grid-cols-3 lg:grid-cols-4 xl:gap-x-6">
            @forelse($featuredProducts as $product)
                <div class="group relative border border-gray-200 rounded-md p-2 bg-white hover:shadow-sm transition">
                    <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded bg-gray-100 lg:aspect-none group-hover:opacity-80 lg:h-48 flex items-center justify-center">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_path) }}" 
                                alt="{{ $product->name }}" 
                                class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                        @else
                            <div class="flex h-full items-center justify-center bg-gray-100">
                                <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="mt-2 flex justify-between items-center">
                        <div>
                            <h3 class="text-xs font-medium text-gray-800 truncate">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="mt-0.5 text-[11px] text-gray-500">{{ $product->category->name }}</p>
                        </div>
                        <div class="text-right">
                            @if($product->discount_price)
                                <span class="text-xs font-bold text-gray-900">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                <div class="text-[10px] text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @else
                                <span class="text-xs font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center">
                    <p class="text-gray-400 text-sm">Tidak ada produk unggulan saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Categories Section -->
    <div class="bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Kategori</h2>
                <a href="{{ route('categories.index') }}" class="text-xs font-semibold text-gray-700 hover:underline">
                    Lihat Semua <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-3 lg:grid-cols-4">
                @forelse($categories as $category)
                    <a href="{{ route('products.category', $category->slug) }}" class="group border border-gray-200 rounded-md p-2 bg-white hover:shadow-sm transition flex flex-col items-center">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded bg-gray-100 flex items-center justify-center">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-20 w-20 object-cover object-center group-hover:opacity-80 rounded">
                            @else
                                <div class="flex h-20 w-20 items-center justify-center bg-gray-100 group-hover:bg-gray-200 rounded">
                                    <span class="text-base font-medium text-gray-600">{{ $category->name }}</span>
                                </div>
                            @endif
                        </div>
                        <h3 class="mt-2 text-xs font-semibold text-gray-900 text-center">{{ $category->name }}</h3>
                        <p class="mt-0.5 text-[11px] text-gray-500">{{ $category->products_count ?? 0 }} produk</p>
                    </a>
                @empty
                    <div class="col-span-full py-8 text-center">
                        <p class="text-gray-400 text-sm">Tidak ada kategori saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sale Products Section -->
    @if($saleProducts->isNotEmpty())
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Produk Diskon</h2>
                <a href="{{ route('products.index') }}?sale=1" class="text-xs font-semibold text-gray-700 hover:underline">
                    Lihat Semua <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-6 sm:grid-cols-3 lg:grid-cols-4 xl:gap-x-6">
                @foreach($saleProducts as $product)
                    <div class="group relative border border-gray-200 rounded-md p-2 bg-white hover:shadow-sm transition">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded bg-gray-100 lg:aspect-none group-hover:opacity-80 lg:h-48 flex items-center justify-center">
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_path) }}" 
                                    alt="{{ $product->name }}" 
                                    class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                            @else
                                <div class="flex h-full items-center justify-center bg-gray-100">
                                    <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-1 right-1 bg-gray-900 text-white px-2 py-0.5 text-[10px] font-bold rounded">
                                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                            </div>
                        </div>
                        <div class="mt-2 flex justify-between items-center">
                            <div>
                                <h3 class="text-xs font-medium text-gray-800 truncate">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="mt-0.5 text-[11px] text-gray-500">{{ $product->category->name }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-gray-900">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                <div class="text-[10px] text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection 