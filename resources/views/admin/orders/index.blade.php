@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Orders Management</h1>
            <p class="text-gray-600">Kelola pesanan pelanggan</p>
        </div>
        <a href="{{ route('admin.orders.export', request()->all()) }}" class="inline-block px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition font-semibold">Export CSV</a>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Filter & Search -->
    <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center bg-white p-4 rounded shadow">
        <select name="status" class="border rounded px-3 py-2 focus:ring focus:ring-blue-200">
            <option value="all">Semua Status</option>
            @foreach($orderStatuses as $key => $label)
                <option value="{{ $key }}" {{ request('status', 'all') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="payment_status" class="border rounded px-3 py-2 focus:ring focus:ring-blue-200">
            <option value="all">Semua Pembayaran</option>
            @foreach($paymentStatuses as $key => $label)
                <option value="{{ $key }}" {{ request('payment_status', 'all') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded px-3 py-2 focus:ring focus:ring-blue-200" placeholder="Dari tanggal">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded px-3 py-2 focus:ring focus:ring-blue-200" placeholder="Sampai tanggal">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari order/nama/email..." class="border rounded px-3 py-2 focus:ring focus:ring-blue-200" autocomplete="off">
        <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 transition">Filter</button>
    </form>

    <!-- Tabel Orders -->
    <div class="overflow-x-auto rounded shadow">
        <table class="min-w-full bg-white rounded-lg">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 border-b text-left">#</th>
                    <th class="px-4 py-3 border-b text-left">Order</th>
                    <th class="px-4 py-3 border-b text-left">Customer</th>
                    <th class="px-4 py-3 border-b text-left">Total</th>
                    <th class="px-4 py-3 border-b text-left">Status</th>
                    <th class="px-4 py-3 border-b text-left">Pembayaran</th>
                    <th class="px-4 py-3 border-b text-left">Tanggal</th>
                    <th class="px-4 py-3 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="hover:bg-blue-50 transition">
                    <td class="px-4 py-2 border-b align-middle">{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                    <td class="px-4 py-2 border-b align-middle font-semibold text-gray-900">#{{ $order->order_number }}</td>
                    <td class="px-4 py-2 border-b align-middle">{{ $order->user->name ?? '-' }}<br><span class="text-xs text-gray-500">{{ $order->user->email ?? '-' }}</span></td>
                    <td class="px-4 py-2 border-b align-middle">Rp {{ number_format($order->total_amount) }}</td>
                    <td class="px-4 py-2 border-b align-middle">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border-b align-middle">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($order->payment_status == 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                            @elseif($order->payment_status == 'refunded') bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border-b align-middle">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-2 border-b align-middle text-center">
                        <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs font-semibold">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-8">Tidak ada pesanan ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection 