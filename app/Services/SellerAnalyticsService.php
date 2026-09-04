<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ReturnRequest;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class SellerAnalyticsService
{
    protected Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function getTodayRevenue(): float
    {
        return (float) Order::where('store_id', $this->store->id)
            ->whereDate('created_at', today())
            ->where('seller_status', 'completed')
            ->sum('seller_earning');
    }

    public function getWeeklyRevenue(): float
    {
        return (float) Order::where('store_id', $this->store->id)
            ->where('created_at', '>=', now()->subWeek())
            ->where('seller_status', 'completed')
            ->sum('seller_earning');
    }

    public function getMonthlyRevenue(): float
    {
        return (float) Order::where('store_id', $this->store->id)
            ->where('created_at', '>=', now()->subMonth())
            ->where('seller_status', 'completed')
            ->sum('seller_earning');
    }

    public function getTotalOrders(): int
    {
        return Order::where('store_id', $this->store->id)->count();
    }

    public function getConversionRate(): float
    {
        $totalOrders = $this->getTotalOrders();
        $completedOrders = Order::where('store_id', $this->store->id)
            ->where('seller_status', 'completed')
            ->count();

        return $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
    }

    public function getBestSellingProducts(int $limit = 5): \Illuminate\Support\Collection
    {
        return Product::where('store_id', $this->store->id)
            ->withCount(['orderItems as total_sold' => function ($q) {
                $q->select(DB::raw('sum(quantity)'));
            }])
            ->orderByDesc('total_sold')
            ->take($limit)
            ->get();
    }

    public function getLowStockProducts(): \Illuminate\Support\Collection
    {
        $threshold = \App\Models\MarketplaceSetting::getLowStockThreshold();

        return Product::where('store_id', $this->store->id)
            ->where('is_active', true)
            ->where('stock', '<=', $threshold)
            ->orderBy('stock')
            ->get();
    }

    public function getReturnsCount(): int
    {
        return ReturnRequest::whereHas('order', fn($q) => $q->where('store_id', $this->store->id))
            ->count();
    }

    public function getCancelledOrdersCount(): int
    {
        return Order::where('store_id', $this->store->id)
            ->where('seller_status', 'cancelled')
            ->count();
    }

    public function getRevenueByDay(int $days = 30): \Illuminate\Support\Collection
    {
        return Order::where('store_id', $this->store->id)
            ->where('seller_status', 'completed')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, sum(seller_earning) as revenue, count(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getDashboardData(): array
    {
        return [
            'today_revenue' => $this->getTodayRevenue(),
            'weekly_revenue' => $this->getWeeklyRevenue(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'total_orders' => $this->getTotalOrders(),
            'conversion_rate' => $this->getConversionRate(),
            'best_selling' => $this->getBestSellingProducts(),
            'low_stock' => $this->getLowStockProducts(),
            'returns_count' => $this->getReturnsCount(),
            'cancelled_count' => $this->getCancelledOrdersCount(),
        ];
    }
}
