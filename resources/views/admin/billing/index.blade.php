@extends('layouts.admin')

@section('title', 'Billing')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Billing & Revenue</h1>
            <p class="text-gray-600">Financial overview and payment analytics</p>
        </div>
    </div>

    <!-- Revenue Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($currentMonthRevenue) }}</p>
                    @php
                        $growth = $lastMonthRevenue > 0 ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
                    @endphp
                    <p class="text-xs {{ $growth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 1) }}% from last month
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Payments</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($pendingPayments) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Platform Fees</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($platformFees) }}</p>
                    <p class="text-xs text-gray-500">2.5% of total revenue</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Revenue Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Monthly Revenue (6 Months)</h2>
            <div class="flex items-end gap-2 h-40">
                @foreach($monthlyRevenue as $data)
                    <div class="flex flex-col items-center justify-end h-full flex-1">
                        <div class="bg-green-500 w-full rounded-t flex items-end justify-center text-white text-xs font-bold" 
                             style="height: {{ max(10, $data['revenue'] > 0 ? ($data['revenue'] / max(1, $monthlyRevenue->max('revenue'))) * 80 : 0) }}%">
                            @if($data['revenue'] > 0)
                                <span class="block w-full text-center py-1">{{ number_format($data['revenue']/1000000, 1) }}M</span>
                            @endif
                        </div>
                        <span class="text-xs mt-1">{{ $data['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Payment Methods</h2>
            <div class="space-y-3">
                @foreach($paymentMethods as $method)
                    @php
                        $percentage = $totalRevenue > 0 ? ($method->total / $totalRevenue) * 100 : 0;
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
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-blue-500 rounded"></div>
                            <span class="text-sm font-medium">{{ $labels[$method->payment_method] ?? ucfirst($method->payment_method) }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium">Rp {{ number_format($method->total) }}</div>
                            <div class="text-xs text-gray-500">{{ number_format($percentage, 1) }}% ({{ $method->count }} orders)</div>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentTransactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $transaction->order_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->user->name ?? 'Guest' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                                {{ $labels[$transaction->payment_method] ?? ucfirst($transaction->payment_method) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($transaction->total_amount) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->updated_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Paid
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No transactions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection