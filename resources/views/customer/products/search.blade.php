@extends('layouts.customer')

@section('title', 'Hasil Pencarian: ' . $query)

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
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Hasil Pencarian</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="mt-6">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Hasil Pencarian: "{{ $query }}"</h1>
            <p class="mt-2 text-gray-600">{{ $products->total() }} produk ditemukan</p>
        </div>

        <div class="mt-8">
            @if($products->isEmpty())
                <div class="py-10 text-center">
                    <p class="text-gray-500">Tidak ada produk yang ditemukan untuk pencarian "{{ $query }}".</p>
                    <div class="mt-6">
                        <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-500">Lihat semua produk</a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
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
@endsection 