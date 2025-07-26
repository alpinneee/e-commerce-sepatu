@extends('layouts.customer')

@section('title', 'Order Details - Toko Sepatu')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
                    <p class="text-gray-600 mt-2">Order #{{ $order->order_number }}</p>
                </div>
                <a href="{{ route('profile.orders') }}" class="text-blue-600 hover:text-blue-800">
                    ← Back to Orders
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Status -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Status</h2>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Order Date</p>
                            <p class="text-gray-900">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items</h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                                                            @if($item->product && $item->product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded object-cover">
                                            @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $item->product->name ?? 'Product not available' }}</h3>
                                    <p class="text-sm text-gray-600">Size: {{ $item->size ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }} each</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Information -->
                @if($order->shipping_address)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Shipping Information</h2>
                    <div class="space-y-2">
                        <p class="text-gray-900"><strong>{{ $order->shipping_address->name }}</strong></p>
                        <p class="text-gray-600">{{ $order->shipping_address->address }}</p>
                        <p class="text-gray-600">{{ $order->shipping_address->city }}, {{ $order->shipping_address->province }} {{ $order->shipping_address->postal_code }}</p>
                        <p class="text-gray-600">Phone: {{ $order->shipping_address->phone }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Discount</span>
                            <span class="text-green-600">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="text-gray-900">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900">Total</span>
                                <span class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Payment Method</p>
                            <p class="text-gray-900">{{ ucfirst($order->payment_method) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-600">Payment Status</p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    @if($order->status === 'delivered')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <button class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Leave Review
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 