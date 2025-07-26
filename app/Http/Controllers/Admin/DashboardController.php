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
        // Get counts
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // Get top selling products
        $topProducts = DB::table('order_items')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                return [
                    'product' => $product,
                    'total_quantity' => $item->total_quantity
                ];
            });
        
        // Get sales data for the last 7 days
        $salesData = $this->getSalesData();
        
        return view('admin.dashboard', compact(
            'totalOrders', 
            'totalProducts', 
            'totalUsers', 
            'totalRevenue',
            'recentOrders',
            'topProducts',
            'salesData'
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
                DB::raw('SUM(total_amount) as total')
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
                'formatted_date' => $date->format('d M')
            ];
        }
        
        return $result;
    }
}
