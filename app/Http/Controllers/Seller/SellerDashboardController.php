<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceSetting;
use App\Models\Order;
use App\Models\Product;
use App\Services\SellerAnalyticsService;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        $analytics = new SellerAnalyticsService($store);

        $totalProducts = $store->products()->count();
        $activeProducts = $store->products()->where('is_active', true)->count();
        $lowStockThreshold = MarketplaceSetting::getLowStockThreshold();
        $lowStockProducts = $store->products()->where('is_active', true)
            ->where('stock', '<=', $lowStockThreshold)->count();

        $totalOrders = Order::where('store_id', $store->id)->count();
        $pendingOrders = Order::where('store_id', $store->id)
            ->whereIn('seller_status', ['pending', 'processing'])->count();
        $completedOrders = Order::where('store_id', $store->id)
            ->where('seller_status', 'completed')->count();

        $wallet = $store->wallet;

        $recentOrders = Order::where('store_id', $store->id)
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayRevenue = Order::where('store_id', $store->id)
                ->whereDate('created_at', $date)
                ->where('seller_status', 'completed')
                ->sum('seller_earning');
            $dayOrders = Order::where('store_id', $store->id)
                ->whereDate('created_at', $date)
                ->count();
            $last7Days->push([
                'date' => now()->subDays($i)->format('d M'),
                'revenue' => (float) $dayRevenue,
                'orders' => $dayOrders,
            ]);
        }

        $dashboardData = $analytics->getDashboardData();

        return view('seller.dashboard', compact(
            'store', 'totalProducts', 'activeProducts', 'lowStockProducts',
            'totalOrders', 'pendingOrders', 'completedOrders',
            'wallet', 'recentOrders', 'last7Days', 'dashboardData'
        ));
    }
}
