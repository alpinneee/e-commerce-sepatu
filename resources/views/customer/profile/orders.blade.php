@extends('layouts.customer')

@section('title', 'My Orders - Toko Sepatu')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="text-gray-600 mt-2">View your order history and track your purchases</p>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-600">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="mt-2 lg:mt-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Amount</p>
                                    <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Payment Method</p>
                                    <p class="text-gray-900">{{ $order->payment_method_label }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Payment Status</p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $order->payment_status_label }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Items</p>
                                    <p class="text-gray-900">{{ $order->items->count() }} items</p>
                                </div>
                            </div>

                            <!-- Order Items Preview -->
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Order Items</h4>
                                <div class="space-y-3">
                                    @foreach($order->items->take(3) as $item)
                                        <div class="flex items-center space-x-4">
                                            @if($item->product && $item->product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}" class="w-12 h-12 rounded object-cover">
                                            @else
                                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ $item->product->name ?? 'Product not available' }}</p>
                                                <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 3)
                                        <p class="text-sm text-gray-600">+ {{ $order->items->count() - 3 }} more items</p>
                                    @endif
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-4">
                                        <a href="{{ route('profile.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            View Order Details
                                        </a>
                                        @if($order->payment_method !== 'cod' && $order->payment_status === 'pending')
                                            <a href="{{ route('profile.orders.show', $order) }}" class="text-green-600 hover:text-green-800 font-medium">
                                                📱 Cara Pembayaran
                                            </a>
                                        @endif
                                    </div>
                                    @if($order->status === 'delivered')
                                        <button class="text-green-600 hover:text-green-800 font-medium">
                                            Leave Review
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="mt-8">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No orders yet</h3>
                <p class="mt-2 text-gray-600">You haven't placed any orders yet. Start shopping to see your order history here.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Start Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 