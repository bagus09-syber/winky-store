<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceSetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalRevenue = (float) Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])
            ->sum('total');

        $totalCommission = (float) Order::where('seller_status', 'completed')
            ->sum('commission_amount');

        $gmv = $totalRevenue;

        $stats = [
            'gmv' => $gmv,
            'total_revenue' => $totalRevenue,
            'total_commission' => $totalCommission,
            'total_products' => Product::count(),
            'total_customers' => User::where('is_admin', false)->count(),
            'total_orders' => Order::count(),
            'active_sellers' => Store::where('status', 'active')->count(),
            'pending_payments' => Order::where('status', 'awaiting_payment')->count(),
            'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
            'pending_returns' => \App\Models\ReturnRequest::where('status', 'requested')->count(),
        ];

        $recentOrders = Order::with('user', 'store')
            ->latest()
            ->take(10)
            ->get();

        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        $revenueByDay = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, sum(total) as revenue, count(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $sellersGrowth = Store::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'ordersByStatus', 'revenueByDay', 'sellersGrowth'));
    }
}
