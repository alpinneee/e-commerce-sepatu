<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Payment Proof Notifications
        $paymentProofNotifications = Order::where('payment_status', 'pending')
            ->whereNotNull('payment_proof')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'type' => 'payment_proof',
                    'title' => 'New Payment Proof',
                    'message' => "Order #{$order->order_number} uploaded payment proof",
                    'data' => $order,
                    'created_at' => $order->updated_at,
                    'read' => false
                ];
            });

        // New Order Notifications
        $newOrderNotifications = Order::where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'type' => 'new_order',
                    'title' => 'New Order',
                    'message' => "New order #{$order->order_number} from {$order->user->name}",
                    'data' => $order,
                    'created_at' => $order->created_at,
                    'read' => false
                ];
            });

        // Low Stock Notifications (if products table exists)
        $lowStockNotifications = collect();
        try {
            $lowStockProducts = \App\Models\Product::where('stock', '<=', 5)
                ->where('stock', '>', 0)
                ->limit(10)
                ->get()
                ->map(function($product) {
                    return [
                        'id' => $product->id,
                        'type' => 'low_stock',
                        'title' => 'Low Stock Alert',
                        'message' => "{$product->name} has only {$product->stock} items left",
                        'data' => $product,
                        'created_at' => now(),
                        'read' => false
                    ];
                });
            $lowStockNotifications = $lowStockProducts;
        } catch (\Exception $e) {
            // Handle if products table doesn't exist
        }

        // New User Registrations
        $newUserNotifications = User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin');
            })
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'type' => 'new_user',
                    'title' => 'New User Registration',
                    'message' => "{$user->name} just registered",
                    'data' => $user,
                    'created_at' => $user->created_at,
                    'read' => false
                ];
            });

        // Combine all notifications
        $allNotifications = collect()
            ->merge($paymentProofNotifications)
            ->merge($newOrderNotifications)
            ->merge($lowStockNotifications)
            ->merge($newUserNotifications)
            ->sortByDesc('created_at')
            ->take(50);

        // Statistics
        $stats = [
            'total' => $allNotifications->count(),
            'unread' => $allNotifications->where('read', false)->count(),
            'payment_proofs' => $paymentProofNotifications->count(),
            'new_orders' => $newOrderNotifications->count(),
            'low_stock' => $lowStockNotifications->count(),
            'new_users' => $newUserNotifications->count()
        ];

        return view('admin.notifications.index', compact('allNotifications', 'stats'));
    }
}