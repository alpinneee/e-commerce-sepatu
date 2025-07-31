@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-600">Manage system notifications and alerts</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ $stats['unread'] }} Unread
            </span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-red-100 text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Payment Proofs</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['payment_proofs'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">New Orders</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['new_orders'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Low Stock</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['low_stock'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-green-100 text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">New Users</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['new_users'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Notifications</h3>
        </div>
        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
            @forelse($allNotifications as $notification)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start space-x-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            @if($notification['type'] === 'payment_proof')
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                            @elseif($notification['type'] === 'new_order')
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                            @elseif($notification['type'] === 'low_stock')
                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification['title'] }}
                                </p>
                                <div class="flex items-center space-x-2">
                                    @if(!$notification['read'])
                                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                    @endif
                                    <p class="text-xs text-gray-500">
                                        {{ $notification['created_at']->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $notification['message'] }}
                            </p>

                            <!-- Action Buttons -->
                            <div class="mt-3 flex items-center space-x-3">
                                @if($notification['type'] === 'payment_proof')
                                    <a href="{{ route('admin.orders.show', $notification['data']->id) }}" 
                                       class="text-xs bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                                        Verify Payment
                                    </a>
                                @elseif($notification['type'] === 'new_order')
                                    <a href="{{ route('admin.orders.show', $notification['data']->id) }}" 
                                       class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                                        View Order
                                    </a>
                                @elseif($notification['type'] === 'low_stock')
                                    <a href="{{ route('admin.products.edit', $notification['data']->id) }}" 
                                       class="text-xs bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700 transition">
                                        Update Stock
                                    </a>
                                @elseif($notification['type'] === 'new_user')
                                    <a href="{{ route('admin.users.index') }}" 
                                       class="text-xs bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">
                                        View Users
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications</h3>
                    <p class="text-gray-500">You're all caught up! No new notifications at this time.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection