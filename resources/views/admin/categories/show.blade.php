@extends('layouts.admin')

@section('title', 'Category Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Category Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Edit Category
            </a>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                ← Back to Categories
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Category Image -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Category Image</h2>
                @if($category->image)
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" 
                         class="w-full h-64 object-cover rounded-lg">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <p class="text-gray-500">No image uploaded</p>
                    </div>
                @endif
            </div>

            <!-- Category Information -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Category Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p class="text-lg font-semibold">{{ $category->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Slug</label>
                        <p class="text-lg">{{ $category->slug }}</p>
                    </div>

                    @if($category->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="text-gray-600">{{ $category->description }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="px-2 py-1 text-sm rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Products Count</label>
                        <p class="text-lg">{{ $category->products_count ?? 0 }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created At</label>
                        <p class="text-gray-600">{{ $category->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Updated At</label>
                        <p class="text-gray-600">{{ $category->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products in this category -->
        @if($category->products && $category->products->count() > 0)
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Products in this Category</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($category->products as $product)
                <div class="border rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        @if($product->images->first())
                            <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" 
                                 class="w-16 h-16 object-cover rounded">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-gray-500 text-xs">No image</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="font-medium">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">Stock: {{ $product->stock }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 