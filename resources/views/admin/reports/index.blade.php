@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
            <p class="text-gray-600">Laporan dan analisis data</p>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6 flex items-center gap-4">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 flex items-center gap-4">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue) }}</p>
            </div>
        </div>
    </div>

    <!-- Orders by Status -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Orders by Status</h2>
        <div class="flex flex-wrap gap-4">
            @foreach(['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'] as $status=>$color)
                <div class="flex-1 min-w-[120px]">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-{{ $color }}-500"></span>
                        <span class="capitalize">{{ $status }}</span>
                        <span class="ml-auto font-bold">{{ $ordersByStatus[$status] ?? 0 }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Revenue by Month -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Revenue by Month</h2>
        <div class="flex items-end gap-2 h-40">
            @foreach($revenueByMonth as $month => $total)
                <div class="flex flex-col items-center justify-end h-full">
                    <div class="bg-blue-500 w-8 rounded-t h-{{ max(2, $total / max(1, max($revenueByMonth)) * 32) }} flex items-end justify-center text-white text-xs font-bold">
                        <span class="block w-full text-center">{{ $total > 0 ? number_format($total/1000).'K' : '0' }}</span>
                    </div>
                    <span class="text-xs mt-1">{{ \Carbon\Carbon::parse($month.'-01')->format('M y') }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 