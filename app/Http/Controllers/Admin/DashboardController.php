<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get basic counts
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        
        // Get order statistics
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        
        // Get payment statistics
        $pendingPayments = Order::where('payment_status', 'pending')->count();
        $paidPayments = Order::where('payment_status', 'paid')->count();
        $failedPayments = Order::where('payment_status', 'failed')->count();
        
        // Get today's statistics
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::where('payment_status', 'paid')
                            ->whereDate('created_at', today())
                            ->sum('total_amount');
        
        // Get this month's statistics
        $monthlyOrders = Order::whereMonth('created_at', now()->month)
                             ->whereYear('created_at', now()->year)
                             ->count();
        $monthlyRevenue = Order::where('payment_status', 'paid')
                               ->whereMonth('created_at', now()->month)
                               ->whereYear('created_at', now()->year)
                               ->sum('total_amount');
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // Get orders requiring attention (pending payments with proof uploaded)
        $ordersNeedingAttention = Order::where('payment_status', 'pending')
            ->whereNotNull('payment_proof')
            ->with('user')
            ->latest()
            ->take(10)
            ->get();
            
        // Get all pending payments
        $allPendingPayments = Order::where('payment_status', 'pending')
            ->with('user')
            ->latest()
            ->take(10)
            ->get();
        
        // Get top selling products
        $topProducts = DB::table('order_items')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $product = Product::with('images')->find($item->product_id);
                return [
                    'product' => $product,
                    'total_quantity' => $item->total_quantity
                ];
            });
        
        // Get sales data for the last 7 days
        $salesData = $this->getSalesData();
        
        // Get payment method statistics
        $paymentMethodStats = Order::select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->where('payment_status', 'paid')
            ->groupBy('payment_method')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalOrders', 
            'totalProducts', 
            'totalUsers', 
            'totalRevenue',
            'pendingOrders',
            'processingOrders', 
            'shippedOrders',
            'deliveredOrders',
            'pendingPayments',
            'paidPayments',
            'failedPayments',
            'todayOrders',
            'todayRevenue',
            'monthlyOrders',
            'monthlyRevenue',
            'recentOrders',
            'ordersNeedingAttention',
            'allPendingPayments',
            'topProducts',
            'salesData',
            'paymentMethodStats'
        ));
    }
    
    /**
     * Get sales data for the last 7 days.
     */
    private function getSalesData()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        $salesData = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');
        
        $result = [];
        
        // Fill in all dates, including those with no sales
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dateString = $date->toDateString();
            $result[$dateString] = [
                'date' => $dateString,
                'total' => $salesData->get($dateString) ? $salesData->get($dateString)->total : 0,
                'count' => $salesData->get($dateString) ? $salesData->get($dateString)->count : 0,
                'formatted_date' => $date->format('d M')
            ];
        }
        
        return $result;
    }
}
