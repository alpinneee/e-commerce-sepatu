@extends('layouts.customer')

@section('title', 'Payment Instructions')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-md p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Instruksi Pembayaran</h1>
            <p class="text-gray-600">Order #{{ $order->order_number }}</p>
            <p class="text-lg font-semibold text-blue-600 mt-2">
                Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </p>
        </div>

        <!-- Payment Instructions Based on Method -->
        @if($order->payment_method === 'qris')
            <!-- QRIS Payment -->
            <div class="text-center mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Pembayaran QRIS</h2>
                
                <!-- QR Code Display -->
                <div class="bg-gray-50 rounded-lg p-8 mb-6 inline-block">
                    <div class="w-64 h-64 mx-auto bg-white rounded-lg shadow-md flex items-center justify-center">
                        <!-- Generate QR Code - you can use a QR code library or API -->
                        <div id="qrcode" class="w-56 h-56"></div>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">Scan QR Code dengan aplikasi pembayaran Anda</p>
                </div>
                
                <div class="text-left bg-blue-50 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-blue-900 mb-3">Cara Pembayaran:</h3>
                    <ol class="list-decimal list-inside space-y-2 text-blue-800">
                        <li>Buka aplikasi mobile banking atau e-wallet Anda</li>
                        <li>Pilih menu "Scan QR" atau "QRIS"</li>
                        <li>Scan QR Code di atas</li>
                        <li>Pastikan nominal sesuai: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></li>
                        <li>Lakukan pembayaran</li>
                        <li>Screenshot bukti pembayaran</li>
                        <li>Upload bukti pembayaran di bawah ini</li>
                    </ol>
                </div>
            </div>

        @elseif(in_array($order->payment_method, ['gopay', 'ovo', 'dana', 'shopeepay']))
            <!-- E-Wallet Payment -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Pembayaran {{ $order->payment_method_label }}</h2>
                
                <div class="bg-orange-50 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-orange-900 mb-3">Cara Pembayaran:</h3>
                    <ol class="list-decimal list-inside space-y-2 text-orange-800">
                        <li>Buka aplikasi {{ $order->payment_method_label }}</li>
                        <li>Pilih menu "Transfer" atau "Kirim Uang"</li>
                        <li>Masukkan nomor tujuan: <strong>081234567890</strong></li>
                        <li>Masukkan nominal: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></li>
                        <li>Masukkan pesan: <strong>{{ $order->order_number }}</strong></li>
                        <li>Lakukan pembayaran</li>
                        <li>Screenshot bukti pembayaran</li>
                        <li>Upload bukti pembayaran di bawah ini</li>
                    </ol>
                </div>
            </div>

        @elseif(in_array($order->payment_method, ['bca', 'mandiri', 'bri', 'bni']))
            <!-- Bank Transfer -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Transfer {{ $order->payment_method_label }}</h2>
                
                @php
                    $bankAccounts = [
                        'bca' => ['number' => '1234567890', 'name' => 'PT Toko Sepatu Indonesia'],
                        'mandiri' => ['number' => '1350012345678', 'name' => 'PT Toko Sepatu Indonesia'],
                        'bri' => ['number' => '012345678901234', 'name' => 'PT Toko Sepatu Indonesia'],
                        'bni' => ['number' => '1234567890', 'name' => 'PT Toko Sepatu Indonesia'],
                    ];
                    $account = $bankAccounts[$order->payment_method];
                @endphp
                
                <div class="bg-green-50 rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <h4 class="font-semibold text-green-900">Nomor Rekening:</h4>
                            <p class="text-lg font-mono text-green-800">{{ $account['number'] }}</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-green-900">Atas Nama:</h4>
                            <p class="text-lg text-green-800">{{ $account['name'] }}</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-green-200 pt-4">
                        <h4 class="font-semibold text-green-900 mb-2">Nominal Transfer:</h4>
                        <p class="text-2xl font-bold text-green-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <p class="text-sm text-green-700 mt-1">*Harap transfer sesuai nominal exact untuk mempermudah verifikasi</p>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Cara Transfer:</h3>
                    <ol class="list-decimal list-inside space-y-2 text-gray-800">
                        <li>Login ke mobile banking atau internet banking {{ $order->payment_method_label }}</li>
                        <li>Pilih menu "Transfer"</li>
                        <li>Masukkan nomor rekening: <strong>{{ $account['number'] }}</strong></li>
                        <li>Masukkan nominal: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></li>
                        <li>Masukkan berita transfer: <strong>{{ $order->order_number }}</strong></li>
                        <li>Lakukan transfer</li>
                        <li>Screenshot bukti transfer</li>
                        <li>Upload bukti pembayaran di bawah ini</li>
                    </ol>
                </div>
            </div>

        @elseif($order->payment_method === 'cod')
            <!-- COD Payment -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Cash on Delivery (COD)</h2>
                
                <div class="bg-purple-50 rounded-lg p-6 mb-6">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-purple-900 mb-2">Informasi COD:</h3>
                            <ul class="space-y-2 text-purple-800">
                                <li>• Pembayaran dilakukan saat barang diterima</li>
                                <li>• Total yang harus dibayar: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></li>
                                <li>• Biaya COD: <strong>Rp {{ number_format($order->cod_fee, 0, ',', '.') }}</strong></li>
                                <li>• Siapkan uang pas untuk memudahkan transaksi</li>
                                <li>• Periksa kondisi barang sebelum pembayaran</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800">
                        <strong>Catatan:</strong> Pesanan Anda akan diproses dan dikirim. Tidak perlu upload bukti pembayaran untuk metode COD.
                    </p>
                </div>
            </div>
        @endif

        <!-- Upload Payment Proof (not for COD) -->
        @if($order->payment_method !== 'cod')
            <div class="border-t border-gray-200 pt-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">Upload Bukti Pembayaran</h3>
                
                @if($order->payment_proof)
                    <!-- Already uploaded -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-green-900">Bukti pembayaran sudah diupload</p>
                                    <p class="text-sm text-green-700">{{ $order->payment_proof_uploaded_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Lihat Bukti
                            </a>
                        </div>
                    </div>
                    
                    <!-- Option to replace -->
                    <details class="mb-6">
                        <summary class="cursor-pointer text-blue-600 hover:text-blue-800 font-medium">
                            Ganti bukti pembayaran
                        </summary>
                        <div class="mt-4">
                @endif
                
                <form action="{{ route('orders.upload-payment-proof', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih file bukti pembayaran
                        </label>
                        <input type="file" id="payment_proof" name="payment_proof" 
                               accept="image/*,.pdf" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        <p class="text-xs text-gray-500 mt-1">
                            Format: JPG, PNG, PDF. Maksimal 5MB.
                        </p>
                    </div>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Catatan tambahan tentang pembayaran..."></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        Upload Bukti Pembayaran
                    </button>
                </form>
                
                @if($order->payment_proof)
                        </div>
                    </details>
                @endif
            </div>
        @endif

        <!-- Back to Orders -->
        <div class="border-t border-gray-200 pt-6 mt-8 text-center">
            <a href="{{ route('profile.orders') }}" 
               class="text-blue-600 hover:text-blue-800 font-medium">
                ← Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>
</div>

<!-- QR Code Library for QRIS -->
@if($order->payment_method === 'qris')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate QR Code for QRIS payment
    const qrData = `00020101021126800014ID.CO.QRIS.WWW0215ID{{ date('YmdHis') }}00{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}0303UMI51440014ID.CO.BNI.WWW02150000{{ str_pad($order->total_amount, 12, '0', STR_PAD_LEFT) }}030{{ $order->total_amount }}5204581253033605802ID5909Toko Sepatu6007Jakarta61051234062070703A01630466{{ strtoupper(substr(md5($order->order_number), 0, 4)) }}`;
    
    QRCode.toCanvas(document.getElementById('qrcode'), qrData, {
        width: 224,
        height: 224,
        margin: 2,
        color: {
            dark: '#000000',
            light: '#FFFFFF'
        }
    }, function (error) {
        if (error) {
            console.error('QR Code generation failed:', error);
            document.getElementById('qrcode').innerHTML = '<div class="text-gray-500 text-center p-8"><p>QR Code tidak dapat dimuat</p><p class="text-sm mt-2">Silakan gunakan metode pembayaran lain</p></div>';
        }
    });
});
</script>
@endif
@endsection 