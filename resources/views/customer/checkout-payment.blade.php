@extends('layouts.customer')

@section('title', 'Payment')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-md p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran</h1>
            <p class="text-gray-600">Order #{{ $order->order_number }}</p>
            <p class="text-lg font-semibold text-blue-600 mt-2">
                Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </p>
        </div>

        <!-- Order Summary -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h2>
            
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $item->product->images->first()?->image_url ?? '/images/placeholder.jpg' }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-12 h-12 object-cover rounded">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                        </div>
                    </div>
                    <p class="font-medium">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                </div>
                @endforeach
                
                <hr class="my-4">
                
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between text-sm text-green-600">
                        <span>Diskon</span>
                        <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between text-sm">
                        <span>Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($order->cod_fee > 0)
                    <div class="flex justify-between text-sm text-orange-600">
                        <span>Biaya COD</span>
                        <span>Rp {{ number_format($order->cod_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <hr>
                    
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h3 class="font-semibold text-blue-900 mb-3">Metode Pembayaran Tersedia:</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-blue-800">
                <div class="flex items-center space-x-2">
                    <span>💳</span>
                    <span>Kartu Kredit</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span>🏦</span>
                    <span>Bank Transfer</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span>📱</span>
                    <span>GoPay</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span>💰</span>
                    <span>ShopeePay</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span>🏪</span>
                    <span>Indomaret</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span>🔄</span>
                    <span>QRIS</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span>💎</span>
                    <span>Akulaku</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span>⚡</span>
                    <span>Dan lainnya</span>
                </div>
            </div>
        </div>

        <!-- Payment Button -->
        <div class="text-center">
            <button id="pay-button" 
                    class="w-full bg-blue-600 text-white py-4 px-8 rounded-lg font-semibold text-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 shadow-lg">
                🚀 Bayar Sekarang
            </button>
            
            <p class="text-sm text-gray-600 mt-4">
                Anda akan diarahkan ke halaman pembayaran yang aman dari Midtrans
            </p>
        </div>

        <!-- Security Info -->
        <div class="mt-8 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <div>
                    <h4 class="font-medium text-green-900">Pembayaran Aman & Terpercaya</h4>
                    <p class="text-sm text-green-700 mt-1">
                        Transaksi Anda dilindungi dengan enkripsi SSL 256-bit dan diproses oleh Midtrans, 
                        payment gateway terpercaya di Indonesia.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function () {
        // Disable button to prevent double click
        payButton.disabled = true;
        payButton.innerHTML = '⏳ Memproses...';
        
        // Trigger snap popup
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                console.log('Payment success:', result);
                // Redirect to success page
                window.location.href = "{{ route('midtrans.finish') }}?order_id={{ $order->order_number }}";
            },
            onPending: function(result) {
                console.log('Payment pending:', result);
                // Show pending message and redirect
                alert('Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');
                window.location.href = "{{ route('midtrans.finish') }}?order_id={{ $order->order_number }}";
            },
            onError: function(result) {
                console.log('Payment error:', result);
                // Re-enable button
                payButton.disabled = false;
                payButton.innerHTML = '🚀 Bayar Sekarang';
                
                alert('Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function() {
                console.log('Payment popup closed');
                // Re-enable button
                payButton.disabled = false;
                payButton.innerHTML = '🚀 Bayar Sekarang';
            }
        });
    });
});
</script>

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    alert('{{ session('error') }}');
});
</script>
@endif
@endsection 