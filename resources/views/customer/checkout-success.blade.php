@extends('layouts.customer')

@section('title', 'Checkout Berhasil')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-md p-8 animate-fade-in">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pesanan Berhasil!</h1>
            <p class="text-gray-600">Terima kasih telah berbelanja di Toko Sepatu</p>
        </div>

        <!-- Order Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Order Information -->
            <div class="animate-slide-up animation-delay-2000">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Informasi Pesanan</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Pesanan</span>
                        <span class="font-medium">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Pesanan</span>
                        <span class="font-medium">{{ $order->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="font-medium">{{ $order->payment_method_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status Pembayaran</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ $order->payment_status_label }}
                        </span>
                    </div>
                    @if($order->shipping_expedition_name)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ekspedisi</span>
                        <span class="font-medium">{{ $order->shipping_expedition_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estimasi</span>
                        <span class="font-medium">{{ $order->shipping_estimation }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="animate-slide-up animation-delay-4000">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Alamat Pengiriman</h2>
                @php
                    $shippingAddress = $order->shipping_address_object;
                @endphp
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="space-y-1">
                        <p class="font-medium">{{ $shippingAddress->name }}</p>
                        <p class="text-gray-600">{{ $shippingAddress->phone }}</p>
                        <p class="text-gray-600">{{ $shippingAddress->address }}</p>
                        <p class="text-gray-600">{{ $shippingAddress->city }}, {{ $shippingAddress->province }} {{ $shippingAddress->postal_code }}</p>
                    </div>
                </div>
                
                @if($order->notes)
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Catatan</h3>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-8 animate-slide-up animation-delay-4000">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Detail Pesanan</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <img src="{{ $item->product->images->first()?->image_url ?? '/images/placeholder.jpg' }}" 
                         alt="{{ $item->product->name }}" 
                         class="w-16 h-16 object-cover rounded">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
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
        <div class="bg-gray-50 rounded-lg p-6 mb-8 animate-slide-up animation-delay-4000">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Ringkasan Pembayaran</h2>
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

        <!-- Next Steps -->
        <div class="border-t pt-8 animate-slide-up animation-delay-4000">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Langkah Selanjutnya</h2>
            
            @if(in_array($order->payment_method, ['qris', 'gopay', 'ovo', 'dana', 'shopeepay']))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm10 0h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                    </svg>
                    <div>
                        <h3 class="font-medium text-blue-900 mb-1">{{ $order->payment_method_label }}</h3>
                        @if($order->payment_method === 'qris')
                        <p class="text-sm text-blue-700 mb-2">
                            Silakan scan QR code yang akan dikirimkan melalui email untuk menyelesaikan pembayaran sejumlah 
                            <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>.
                        </p>
                        @else
                        <p class="text-sm text-blue-700 mb-2">
                            Silakan selesaikan pembayaran melalui aplikasi {{ $order->payment_method_label }} sejumlah 
                            <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>.
                        </p>
                        @endif
                        <p class="text-xs text-blue-600">
                            Pesanan akan diproses setelah pembayaran diterima.
                        </p>
                    </div>
                </div>
            </div>
            @elseif(in_array($order->payment_method, ['bca', 'mandiri', 'bri', 'bni']))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 6h20l-2 12H4L2 6zm2 2l1.5 8h13L20 8H4zm4 3h8v2H8v-2z"/>
                    </svg>
                    <div>
                        <h3 class="font-medium text-blue-900 mb-1">Transfer {{ $order->payment_method_label }}</h3>
                        <p class="text-sm text-blue-700 mb-2">
                            Silakan transfer sejumlah <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> 
                            ke rekening {{ $order->payment_method_label }} yang akan dikirimkan melalui email dalam waktu 24 jam.
                        </p>
                        <p class="text-xs text-blue-600">
                            Pesanan akan diproses setelah pembayaran diterima.
                        </p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="font-medium text-green-900 mb-1">Cash on Delivery (COD)</h3>
                        <p class="text-sm text-green-700 mb-2">
                            Pesanan Anda akan diproses dan dikirim. Pembayaran sejumlah 
                            <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> 
                            dilakukan saat barang diterima.
                        </p>
                        @if($order->cod_fee > 0)
                        <p class="text-xs text-orange-600">
                            ⚠️ Termasuk biaya COD sebesar Rp {{ number_format($order->cod_fee, 0, ',', '.') }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="space-y-2 text-sm text-gray-600">
                <p>• Anda akan menerima email konfirmasi pesanan</p>
                <p>• Status pesanan dapat dilihat di halaman "Pesanan Saya"</p>
                <p>• Customer service akan menghubungi Anda jika ada pertanyaan</p>
                @if($order->shipping_expedition_name)
                <p>• Barang akan dikirim melalui {{ $order->shipping_expedition_name }} dengan estimasi {{ $order->shipping_estimation }}</p>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-8 animate-slide-up animation-delay-4000">
            @if($order->payment_method !== 'cod')
            <a href="{{ route('orders.payment-instructions', $order) }}" 
               class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg font-medium text-center hover:bg-green-700 transition duration-200">
                📱 Lihat Cara Pembayaran
            </a>
            @endif
            
            <a href="{{ route('home') }}" 
               class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-medium text-center hover:bg-blue-700 transition duration-200">
                Lanjutkan Belanja
            </a>
            
            @auth
            <a href="{{ route('profile.orders') }}" 
               class="flex-1 bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-medium text-center hover:bg-gray-200 transition duration-200">
                Lihat Pesanan Saya
            </a>
            @endif
        </div>
    </div>
</div>
@endsection 