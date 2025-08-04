<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        // Current month revenue
        $currentMonthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Last month revenue
        $lastMonthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');

        // Total revenue
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');

        // Pending payments
        $pendingPayments = Order::where('payment_status', 'pending')->sum('total_amount');

        // Monthly revenue for last 6 months
        $monthlyRevenue = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Order::where('payment_status', 'paid')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_amount');
            $monthlyRevenue->put($date->format('Y-m'), [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ]);
        }

        // Payment method breakdown
        $paymentMethods = Order::where('payment_status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->orderBy('total', 'desc')
            ->get();

        // Recent transactions
        $recentTransactions = Order::where('payment_status', 'paid')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        // Platform fees (example calculation - 2.5% of revenue)
        $platformFees = $totalRevenue * 0.025;

        return view('admin.billing.index', compact(
            'currentMonthRevenue',
            'lastMonthRevenue', 
            'totalRevenue',
            'pendingPayments',
            'monthlyRevenue',
            'paymentMethods',
            'recentTransactions',
            'platformFees'
        ));
    }
}