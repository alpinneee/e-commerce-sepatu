@extends('layouts.admin')

@section('title', 'Product Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Product Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Edit Product
            </a>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                ← Back to Products
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Product Images</h2>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($product->images as $image)
                        <div>
                            <img src="{{ $image->image_url }}" alt="{{ $product->name }}" 
                                 class="w-full h-48 object-cover rounded-lg">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Product Information -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Product Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p class="text-lg font-semibold">{{ $product->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <p class="text-lg">{{ $product->category->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <p class="text-lg font-semibold text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>

                    @if($product->discount_price)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Discount Price</label>
                        <p class="text-lg font-semibold text-red-600">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stock</label>
                        <p class="text-lg">{{ $product->stock }}</p>
                    </div>

                    @if($product->sku)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SKU</label>
                        <p class="text-lg">{{ $product->sku }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="px-2 py-1 text-sm rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    @if($product->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="text-gray-600">{{ $product->description }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created At</label>
                        <p class="text-gray-600">{{ $product->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Updated At</label>
                        <p class="text-gray-600">{{ $product->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 