@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>
    <div class="bg-white rounded shadow p-6 space-y-6">
        <!-- Ringkasan Pesanan (dummy) -->
        <div>
            <h2 class="text-lg font-semibold mb-2">Ringkasan Pesanan</h2>
            <ul class="text-gray-700 mb-2">
                <li>Produk 1 x 1 - Rp 100.000</li>
                <li>Produk 2 x 2 - Rp 200.000</li>
            </ul>
            <div class="font-bold">Total: Rp 300.000</div>
        </div>
        <!-- Form Alamat Pengiriman (dummy) -->
        <div>
            <h2 class="text-lg font-semibold mb-2">Alamat Pengiriman</h2>
            <form>
                <div class="mb-2">
                    <label class="block font-medium mb-1">Nama Penerima</label>
                    <input type="text" class="w-full border rounded px-3 py-2" placeholder="Nama Penerima">
                </div>
                <div class="mb-2">
                    <label class="block font-medium mb-1">Alamat Lengkap</label>
                    <textarea class="w-full border rounded px-3 py-2" rows="2" placeholder="Alamat lengkap"></textarea>
                </div>
                <div class="mb-2">
                    <label class="block font-medium mb-1">No. Telepon</label>
                    <input type="text" class="w-full border rounded px-3 py-2" placeholder="08xxxxxxxxxx">
                </div>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 mt-2">Proses Checkout</button>
            </form>
        </div>
    </div>
</div>
@endsection 