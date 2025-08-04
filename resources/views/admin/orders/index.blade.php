@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-900">Orders</h1>
        <a href="{{ route('admin.orders.export', request()->all()) }}" 
           class="px-3 py-1.5 bg-green-600 text-white rounded text-sm hover:bg-green-700 transition">
            Export CSV
        </a>
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

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="bg-white rounded shadow p-3 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="text-xl font-bold text-blue-600">{{ $orders->total() }}</div>
                <div class="ml-auto text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-gray-600">Total Pesanan</div>
        </div>
        
        <div class="bg-white rounded shadow p-3 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="text-xl font-bold text-yellow-600">{{ $orders->where('payment_status', 'pending')->count() }}</div>
                <div class="ml-auto text-yellow-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-gray-600">Pending Payment</div>
        </div>
        
        <div class="bg-white rounded shadow p-3 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="text-xl font-bold text-green-600">{{ $orders->where('payment_status', 'paid')->count() }}</div>
                <div class="ml-auto text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-gray-600">Paid Orders</div>
        </div>
        
        <div class="bg-white rounded shadow p-3 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="text-xl font-bold text-purple-600">{{ $orders->where('status', 'delivered')->count() }}</div>
                <div class="ml-auto text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-gray-600">Completed</div>
        </div>
    </div>

    <!-- Filter & Search -->
    <form method="GET" class="bg-white p-3 rounded shadow">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-2 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status Pesanan</label>
                <select name="status" class="w-full border rounded px-2 py-1 text-sm">
                    <option value="all">Semua Status</option>
                    @foreach($orderStatuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status', 'all') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status Pembayaran</label>
                <select name="payment_status" class="w-full border rounded px-2 py-1 text-sm">
                    <option value="all">Semua Pembayaran</option>
                    @foreach($paymentStatuses as $key => $label)
                        <option value="{{ $key }}" {{ request('payment_status', 'all') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full border rounded px-2 py-1 text-sm">
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full border rounded px-2 py-1 text-sm">
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Order/nama/email..." 
                       class="w-full border rounded px-2 py-1 text-sm">
            </div>
            
            <div>
                <button type="submit" class="w-full px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">
                    Filter
                </button>
            </div>
        </div>
    </form>

    <!-- Tabel Orders -->
    <div class="bg-white rounded shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">#</th>
                        <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">Order</th>
                        <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">Customer</th>
                        <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">Pembayaran</th>
                        <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500">Total</th>
                        <th class="px-3 py-2 border-b text-center text-xs font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user->email ?? json_decode($order->shipping_address)->email }}</div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->payment_method_label }}</div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($order->payment_status == 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                                @elseif($order->payment_status == 'refunded') bg-blue-100 text-blue-800
                                @endif">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->shipping_expedition_name)
                                <div class="text-sm text-gray-900">{{ $order->shipping_expedition_name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->shipping_estimation }}</div>
                            @else
                                <span class="text-xs text-gray-400">Belum dipilih</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount) }}</div>
                            @if($order->cod_fee > 0)
                                <div class="text-xs text-orange-600">+COD: Rp {{ number_format($order->cod_fee) }}</div>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 transition font-semibold">
                                    Detail
                                </a>
                                @if($order->payment_status === 'pending')
                                <form action="{{ route('admin.orders.confirm-payment', $order) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 transition font-semibold"
                                            onclick="return confirm('Konfirmasi pembayaran?')">
                                        Konfirmasi
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"/>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada pesanan ditemukan</p>
                                <p class="text-sm">Coba ubah filter pencarian atau buat pesanan baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="bg-white rounded-lg shadow-md px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan {{ $orders->firstItem() ?? 0 }} sampai {{ $orders->lastItem() ?? 0 }} 
                dari {{ $orders->total() }} hasil
            </div>
            <div>
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 