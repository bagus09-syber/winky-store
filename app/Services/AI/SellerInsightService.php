<?php

namespace App\Services\AI;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class SellerInsightService
{
    protected Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function getDashboardInsights(): array
    {
        return [
            'revenue' => $this->getRevenueData(),
            'products' => $this->getProductInsights(),
            'orders' => $this->getOrderInsights(),
            'customers' => $this->getCustomerInsights(),
            'ai_insights' => $this->generateInsights(),
        ];
    }

    public function getRevenueData(): array
    {
        $today = Order::where('store_id', $this->store->id)
            ->whereDate('created_at', today())
            ->where('seller_status', 'completed')
            ->sum('seller_earning');

        $yesterday = Order::where('store_id', $this->store->id)
            ->whereDate('created_at', today()->subDay())
            ->where('seller_status', 'completed')
            ->sum('seller_earning');

        $thisWeek = Order::where('store_id', $this->store->id)
            ->where('created_at', '>=', now()->startOfWeek())
            ->where('seller_status', 'completed')
            ->sum('seller_earning');

        $lastWeek = Order::where('store_id', $this->store->id)
            ->whereBetween('created_at', [now()->startOfWeek()->subWeek(), now()->startOfWeek()->subDay()])
            ->where('seller_status', 'completed')
            ->sum('seller_earning');

        $thisMonth = Order::where('store_id', $this->store->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('seller_status', 'completed')
            ->sum('seller_earning');

        $lastMonth = Order::where('store_id', $this->store->id)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->where('seller_status', 'completed')
            ->sum('seller_earning');

        $growth = $lastWeek > 0 ? (($thisWeek - $lastWeek) / $lastWeek * 100) : 0;

        return [
            'today' => (float) $today,
            'yesterday' => (float) $yesterday,
            'this_week' => (float) $thisWeek,
            'last_week' => (float) $lastWeek,
            'this_month' => (float) $thisMonth,
            'last_month' => (float) $lastMonth,
            'growth' => round($growth, 1),
        ];
    }

    public function getProductInsights(): array
    {
        $totalProducts = $this->store->products()->count();
        $activeProducts = $this->store->products()->where('is_active', true)->count();
        $lowStock = $this->store->products()->where('is_active', true)->where('stock', '<=', 5)->count();
        $outOfStock = $this->store->products()->where('stock', 0)->count();

        $bestSelling = OrderItem::where('store_id', $this->store->id)
            ->select('product_id', 'product_name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get()
            ->toArray();

        $slowMoving = Product::where('store_id', $this->store->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where(function ($q) {
                $q->whereRaw('id NOT IN (SELECT product_id FROM order_items WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY))');
            })
            ->take(5)
            ->get(['id', 'name', 'slug', 'stock', 'price'])
            ->toArray();

        return [
            'total' => $totalProducts,
            'active' => $activeProducts,
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'best_selling' => $bestSelling,
            'slow_moving' => $slowMoving,
        ];
    }

    public function getOrderInsights(): array
    {
        $pendingOrders = Order::where('store_id', $this->store->id)
            ->whereIn('seller_status', ['pending', 'processing'])
            ->count();

        $completedOrders = Order::where('store_id', $this->store->id)
            ->where('seller_status', 'completed')
            ->count();

        $cancelledOrders = Order::where('store_id', $this->store->id)
            ->where('seller_status', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->count();

        $returnRequests = \App\Models\ReturnRequest::whereHas('order', fn($q) => $q->where('store_id', $this->store->id))
            ->where('status', 'requested')
            ->count();

        $avgOrderValue = Order::where('store_id', $this->store->id)
            ->where('seller_status', 'completed')
            ->avg('seller_subtotal');

        $conversionRate = $this->calculateConversionRate();

        return [
            'pending' => $pendingOrders,
            'completed' => $completedOrders,
            'cancelled_month' => $cancelledOrders,
            'returns_pending' => $returnRequests,
            'avg_order_value' => round((float) $avgOrderValue, 2),
            'conversion_rate' => $conversionRate,
        ];
    }

    public function getCustomerInsights(): array
    {
        $totalCustomers = Order::where('store_id', $this->store->id)
            ->distinct('user_id')
            ->count('user_id');

        $repeatCustomers = Order::where('store_id', $this->store->id)
            ->select('user_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('user_id')
            ->having('order_count', '>', 1)
            ->count();

        return [
            'total' => $totalCustomers,
            'repeat' => $repeatCustomers,
            'repeat_rate' => $totalCustomers > 0 ? round($repeatCustomers / $totalCustomers * 100, 1) : 0,
        ];
    }

    public function generateInsights(): array
    {
        $insights = [];
        $revenue = $this->getRevenueData();
        $products = $this->getProductInsights();
        $orders = $this->getOrderInsights();

        if ($revenue['growth'] > 20) {
            $insights[] = [
                'type' => 'positive',
                'icon' => 'trending-up',
                'title' => 'Pendapatan Naik Signifikan!',
                'message' => 'Pendapatan minggu ini naik ' . number_format($revenue['growth'], 1) . '% dari minggu lalu.',
                'action' => 'Pertahankan strategi penjualan saat ini.',
            ];
        } elseif ($revenue['growth'] < -10) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'trending-down',
                'title' => 'Pendapatan Menurun',
                'message' => 'Pendapatan minggu ini turun ' . number_format(abs($revenue['growth']), 1) . '% dari minggu lalu.',
                'action' => 'Periksa produk slow moving dan pertimbangkan promosi.',
            ];
        }

        if ($products['low_stock'] > 0) {
            $insights[] = [
                'type' => 'danger',
                'icon' => 'alert-triangle',
                'title' => "{$products['low_stock']} Produk Stok Menipis",
                'message' => 'Produk dengan stok rendah perlu segera direstok.',
                'action' => 'Restok produk sebelum kehabisan.',
            ];
        }

        if ($orders['pending'] > 5) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'clock',
                'title' => "{$orders['pending']} Pesanan Pending",
                'message' => 'Pesanan yang belum diproses cukup banyak.',
                'action' => 'Proses pesanan segera untuk meningkatkan kepuasan pelanggan.',
            ];
        }

        if ($products['slow_moving']) {
            $count = count($products['slow_moving']);
            $insights[] = [
                'type' => 'info',
                'icon' => 'package',
                'title' => "{$count} Produk Slow Moving",
                'message' => 'Produk ini belum terjual dalam 30 hari terakhir.',
                'action' => 'Pertimbangkan diskon atau bundle untuk produk ini.',
            ];
        }

        if ($orders['conversion_rate'] < 2) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'target',
                'title' => 'Conversion Rate Rendah',
                'message' => 'Tingkat konversi toko Anda ' . number_format($orders['conversion_rate'], 1) . '%.',
                'action' => 'Optimasi foto produk, deskripsi, dan harga.',
            ];
        }

        if ($orders['returns_pending'] > 0) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'rotate-ccw',
                'title' => "{$orders['returns_pending']} Retur Pending",
                'message' => 'Ada permintaan retur yang perlu diproses.',
                'action' => 'Proses retur untuk menjaga kepuasan pelanggan.',
            ];
        }

        return $insights;
    }

    private function calculateConversionRate(): float
    {
        $totalViews = $this->store->products()->where('is_active', true)->sum('stock');
        $totalOrders = Order::where('store_id', $this->store->id)
            ->where('seller_status', 'completed')
            ->count();

        if ($totalViews <= 0) return 0;
        return round($totalOrders / max($totalViews, 1) * 100, 1);
    }
}
