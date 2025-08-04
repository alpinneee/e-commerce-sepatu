@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan #{{ $order->order_number }}</h1>
            </div>
            <p class="text-gray-600">Kelola detail pesanan pelanggan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.orders.invoice', $order) }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold text-sm">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Invoice
            </a>
        </div>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details (Left Column) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Informasi Pesanan
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Nomor Pesanan</label>
                            <p class="text-gray-900 font-semibold">#{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Tanggal Pesanan</label>
                            <p class="text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Status Pesanan</label>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Status Pembayaran</label>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                @if($order->payment_status == 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                                @elseif($order->payment_status == 'refunded') bg-blue-100 text-blue-800
                                @endif">
                                {{ $order->payment_status_label }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <p class="text-gray-900">{{ $order->payment_method_label }}</p>
                        </div>
                        @if($order->shipping_expedition_name)
                        <div>
                            <label class="text-sm font-medium text-gray-700">Ekspedisi</label>
                            <p class="text-gray-900">{{ $order->shipping_expedition_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Estimasi Pengiriman</label>
                            <p class="text-gray-900">{{ $order->shipping_estimation }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-gray-700">Total Pembayaran</label>
                            <p class="text-gray-900 font-bold text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                        
                        @if($order->payment_proof)
                        <div>
                            <label class="text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                            <div class="mt-2">
                                <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-green-900">Bukti pembayaran tersedia</p>
                                            <p class="text-xs text-green-700">Diupload: {{ $order->payment_proof_uploaded_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                                       class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 transition font-semibold">
                                        Lihat Bukti
                                    </a>
                                </div>
                            </div>
                        </div>
                        @elseif($order->payment_method !== 'cod' && $order->payment_status === 'pending')
                        <div>
                            <label class="text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                            <div class="mt-2">
                                <div class="flex items-center space-x-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-yellow-800">Menunggu customer upload bukti pembayaran</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Pelanggan
                </h2>
                
                @php
                    $customer = $order->user;
                    $shippingAddress = $order->shipping_address_object;
                @endphp
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Data Pelanggan</h3>
                        <div class="space-y-2">
                            <p class="text-gray-900"><strong>Nama:</strong> {{ $customer->name ?? 'Guest' }}</p>
                            <p class="text-gray-900"><strong>Email:</strong> {{ $customer->email ?? $shippingAddress->email }}</p>
                            @if($customer?->phone)
                            <p class="text-gray-900"><strong>Telepon:</strong> {{ $customer->phone }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Alamat Pengiriman</h3>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="font-medium text-gray-900">{{ $shippingAddress->name }}</p>
                            <p class="text-gray-700">{{ $shippingAddress->phone }}</p>
                            <p class="text-gray-700">{{ $shippingAddress->address }}</p>
                            <p class="text-gray-700">{{ $shippingAddress->city }}, {{ $shippingAddress->province }} {{ $shippingAddress->postal_code }}</p>
                        </div>
                    </div>
                </div>
                
                @if($order->notes)
                <div class="mt-4">
                    <h3 class="font-medium text-gray-900 mb-2">Catatan Pesanan</h3>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-700">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Produk Pesanan
                </h2>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                        <img src="{{ $item->product->images->first()?->image_url ?? '/images/placeholder.jpg' }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-16 h-16 object-cover rounded-lg">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-600">SKU: {{ $item->product->sku }}</p>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Total: Rp {{ number_format($item->total, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    Ringkasan Pembayaran
                </h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon</span>
                        <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($order->cod_fee > 0)
                    <div class="flex justify-between text-orange-600">
                        <span>Biaya COD</span>
                        <span>Rp {{ number_format($order->cod_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <hr class="my-3">
                    
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar (Right Column) -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Aksi Cepat</h2>
                
                <!-- Payment Confirmation -->
                @if($order->payment_status === 'pending')
                <div class="space-y-3 mb-6">
                    <h3 class="font-medium text-gray-900">Konfirmasi Pembayaran</h3>
                    <form action="{{ route('admin.orders.confirm-payment', $order) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition font-semibold"
                                onclick="return confirm('Konfirmasi bahwa pembayaran telah diterima?')">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
                @endif

                <!-- Order Status Update -->
                <div class="space-y-3">
                    <h3 class="font-medium text-gray-900">Update Status Pesanan</h3>
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pesanan</label>
                            <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                            <select name="payment_status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                            <textarea name="notes" rows="3" 
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Tambahan catatan untuk customer...">{{ $order->notes }}</textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition font-semibold">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Timeline Pesanan</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Pesanan Dibuat</p>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($order->payment_status === 'paid')
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Pembayaran Diterima</p>
                            <p class="text-sm text-gray-600">{{ $order->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Sedang Diproses</p>
                            <p class="text-sm text-gray-600">{{ $order->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if(in_array($order->status, ['shipped', 'delivered']))
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707L16 7.586A1 1 0 0015.414 7H14z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Sedang Dikirim</p>
                            <p class="text-sm text-gray-600">{{ $order->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->status === 'delivered')
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Pesanan Selesai</p>
                            <p class="text-sm text-gray-600">{{ $order->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 