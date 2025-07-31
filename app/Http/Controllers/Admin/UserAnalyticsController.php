<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserAnalyticsController extends Controller
{
    public function index()
    {
        // User Statistics (exclude admin users)
        $totalUsers = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'admin');
        })->count();
        
        $newUsersThisMonth = User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin');
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $activeUsers = User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin');
            })
            ->whereHas('orders', function($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            })->count();

        // User Registration by Month (last 6 months)
        $userRegistrations = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = User::whereDoesntHave('roles', function($query) {
                    $query->where('name', 'admin');
                })
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $userRegistrations->put($date->format('Y-m'), $count);
        }

        // Top Customers by Orders
        $topCustomers = User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin');
            })
            ->withCount(['orders' => function($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->withSum(['orders' => function($query) {
                $query->where('status', '!=', 'cancelled');
            }], 'total_amount')
            ->having('orders_count', '>', 0)
            ->orderBy('orders_sum_total_amount', 'desc')
            ->limit(10)
            ->get();

        // User Activity by Day (last 7 days)
        $userActivity = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $activeCount = User::whereDoesntHave('roles', function($query) {
                    $query->where('name', 'admin');
                })
                ->whereHas('orders', function($query) use ($date) {
                    $query->whereDate('created_at', $date->toDateString());
                })
                ->count();
            $userActivity->put($date->format('Y-m-d'), [
                'date' => $date->format('M d'),
                'count' => $activeCount
            ]);
        }

        // User Demographics (by registration month)
        $userDemographics = User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin');
            })
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->pluck('count', 'month');

        return view('admin.analytics.users', compact(
            'totalUsers',
            'newUsersThisMonth', 
            'activeUsers',
            'userRegistrations',
            'topCustomers',
            'userActivity',
            'userDemographics'
        ));
    }
}