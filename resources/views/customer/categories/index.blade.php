@extends('layouts.customer')

@section('title', 'Kategori Produk')

@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-xs font-medium text-gray-700 hover:text-gray-900">
                        <svg class="w-3 h-3 mr-1.5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Beranda
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-xs font-medium text-gray-500 md:ml-1">Kategori</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="mt-4">
            <h1 class="text-xl font-semibold tracking-tight text-gray-900">Kategori Produk</h1>
            <p class="mt-1 text-xs text-gray-500">Pilih kategori untuk melihat produk yang tersedia</p>
        </div>

        <div class="mt-6">
            @if($categories->isEmpty())
                <div class="py-8 text-center">
                    <p class="text-gray-400 text-sm">Tidak ada kategori yang tersedia.</p>
                </div>
            @else
                <div class="grid grid-cols-2 gap-x-4 gap-y-6 sm:grid-cols-3 lg:grid-cols-4 xl:gap-x-6">
                    @foreach($categories as $category)
                        <div class="group relative border border-gray-200 rounded-md p-2 bg-white hover:shadow-sm transition flex flex-col items-center">
                            <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded bg-gray-100 flex items-center justify-center">
                                @if($category->image)
                                    <img src="{{ $category->image_url }}" 
                                        alt="{{ $category->name }}" 
                                        class="h-20 w-20 object-cover object-center group-hover:opacity-80 rounded">
                                @else
                                    <div class="flex h-20 w-20 items-center justify-center bg-gray-100 group-hover:bg-gray-200 rounded">
                                        <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-2 w-full text-center">
                                <h3 class="text-xs font-semibold text-gray-900">
                                    <a href="{{ route('products.category', $category->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $category->name }}
                                    </a>
                                </h3>
                                @if($category->description)
                                    <p class="mt-0.5 text-[11px] text-gray-500 line-clamp-2">{{ $category->description }}</p>
                                @endif
                                <p class="mt-0.5 text-[11px] text-gray-400">{{ $category->active_products_count }} produk</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 