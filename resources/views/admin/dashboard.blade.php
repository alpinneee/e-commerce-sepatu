@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600">Selamat datang di panel admin Toko Sepatu</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-500">
                {{ now()->format('d M Y, H:i') }}
            </div>
            <a href="{{ route('admin.orders.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                Kelola Pesanan
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                    <p class="text-xs text-gray-500">Hari ini: {{ $todayOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pembayaran Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($pendingPayments) }}</p>
                    <p class="text-xs text-gray-500">Perlu konfirmasi</p>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue) }}</p>
                    <p class="text-xs text-gray-500">Hari ini: Rp {{ number_format($todayRevenue) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalProducts) }}</p>
                    <p class="text-xs text-gray-500">{{ number_format($totalUsers) }} pengguna</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Overview -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Pesanan</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">{{ $pendingOrders }}</div>
                <div class="text-sm text-gray-600">Pending</div>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ $processingOrders }}</div>
                <div class="text-sm text-gray-600">Processing</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="text-2xl font-bold text-purple-600">{{ $shippedOrders }}</div>
                <div class="text-sm text-gray-600">Shipped</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ $deliveredOrders }}</div>
                <div class="text-sm text-gray-600">Delivered</div>
            </div>
        </div>
    </div>

    <!-- Orders Needing Attention & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Orders Needing Attention -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Bukti Pembayaran Masuk</h3>
                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ $ordersNeedingAttention->count() }}
                </span>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                @if($ordersNeedingAttention->count() > 0)
                    <div class="space-y-4">
                        @foreach($ordersNeedingAttention as $order)
                        <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-900">#{{ $order->order_number }}</p>
                                    <span class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $order->user->name ?? 'Guest' }}</p>
                                <p class="text-sm font-medium text-red-600">Rp {{ number_format($order->total_amount) }}</p>
                                <div class="flex items-center space-x-2 mt-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        📸 Bukti Upload
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $order->payment_method_label }}</span>
                                </div>
                                <p class="text-xs text-green-600 mt-1">Uploaded: {{ $order->payment_proof_uploaded_at->diffForHumans() }}</p>
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 transition">
                                    Verifikasi
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500">Tidak ada bukti pembayaran baru!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Pesanan Terbaru</h3>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-900">#{{ $order->order_number }}</p>
                                    <span class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $order->user->name ?? 'Guest' }}</p>
                                <p class="text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount) }}</p>
                                <div class="flex items-center space-x-2 mt-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $order->status_label }}
                                    </span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                                        @endif">
                                        {{ $order->payment_status_label }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="bg-gray-600 text-white px-3 py-1 rounded text-xs hover:bg-gray-700 transition">
                                    Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada pesanan</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Methods & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Methods Statistics -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Metode Pembayaran Populer</h3>
            </div>
            <div class="p-6">
                @if($paymentMethodStats->count() > 0)
                    <div class="space-y-4">
                        @foreach($paymentMethodStats as $stat)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">
                                    @php
                                        $labels = [
                                            'qris' => 'QRIS',
                                            'gopay' => 'GoPay', 
                                            'ovo' => 'OVO',
                                            'dana' => 'DANA',
                                            'shopeepay' => 'ShopeePay',
                                            'bca' => 'Bank BCA',
                                            'mandiri' => 'Bank Mandiri',
                                            'bri' => 'Bank BRI',
                                            'bni' => 'Bank BNI',
                                            'cod' => 'Cash on Delivery'
                                        ];
                                    @endphp
                                    {{ $labels[$stat->payment_method] ?? ucfirst($stat->payment_method) }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $stat->count }} transaksi</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">Rp {{ number_format($stat->total) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada transaksi</p>
                @endif
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Produk Terlaris</h3>
            </div>
            <div class="p-6">
                @if($topProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($topProducts as $item)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                @if($item['product'] && $item['product']->images->count() > 0)
                                    <img src="{{ $item['product']->images->first()->image_url ?? '/images/placeholder.jpg' }}" 
                                         alt="{{ $item['product']->name }}" 
                                         class="w-12 h-12 object-cover rounded-lg mr-4">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item['product']->name ?? 'Product Not Found' }}</p>
                                    <p class="text-sm text-gray-600">{{ $item['total_quantity'] }} terjual</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">Rp {{ number_format($item['product']->price ?? 0) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada produk terjual</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Penjualan 7 Hari Terakhir</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-7 gap-4">
                @foreach($salesData as $data)
                <div class="text-center">
                    <div class="mb-2">
                        <div class="h-20 bg-gray-100 rounded-lg flex items-end justify-center">
                            @if($data['total'] > 0)
                                <div class="bg-blue-500 rounded-t w-full" 
                                     style="height: {{ max(10, ($data['total'] / max(array_column($salesData, 'total'))) * 80) }}%">
                                </div>
                            @endif
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-900">{{ $data['formatted_date'] }}</p>
                    <p class="text-xs text-gray-600">{{ $data['count'] }} pesanan</p>
                    <p class="text-xs text-gray-600">Rp {{ number_format($data['total']) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 