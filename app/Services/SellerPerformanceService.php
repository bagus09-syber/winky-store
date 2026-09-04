<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\SellerPerformance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SellerPerformanceService
{
    protected Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function calculateAndUpdate(): void
    {
        $today = Carbon::today();
        $thisWeek = $today->startOfWeek();
        $thisMonth = $today->startOfMonth();
        $lastWeek = $today->subWeek()->startOfWeek();
        $lastMonth = $today->subMonth()->startOfMonth();

        // Calculate orders
        $totalOrders = Order::where('store_id', $this->store->id)->count();
        $completedOrders = Order::where('store_id', $this->store->id)
            ->where('seller_status', 'completed')->count();
        $cancelledOrders = Order::where('store_id', $this->store->id)
            ->where('seller_status', 'cancelled')->count();

        // Calculate response rate (orders responded to within 24 hours)
        $responseRate = $this->calculateResponseRate($today);

        // Calculate on-time shipping rate
        $onTimeShippingRate = $this->calculateOnTimeShippingRate($today);

        // Calculate average rating
        $averageRating = Product::where('store_id', $this->store->id)
            ->whereHas('reviews', fn($q) => $q->where('is_approved', true))
            ->avg('reviews.avg_rating') ?? 0;

        // Calculate return rate
        $returnRate = $this->calculateReturnRate($today);

        // Calculate seller score
        $sellerScore = $this->calculateSellerScore([
            'response_rate' => $responseRate,
            'on_time_shipping_rate' => $onTimeShippingRate,
            'average_rating' => $averageRating,
            'return_rate' => $returnRate,
        ]);

        // Determine seller level
        $sellerLevel = SellerPerformance::calculateLevel($sellerScore);

        // Update or create the performance record
        $performance = SellerPerformance::firstOrCreate(
            ['store_id' => $this->store->id],
            [
                'total_orders' => 0,
                'completed_orders' => 0,
                'cancelled_orders' => 0,
                'response_rate' => 0,
                'on_time_shipping_rate' => 0,
                'average_rating' => 0,
                'return_rate' => 0,
                'seller_score' => 0,
                'seller_level' => 'bronze',
            ]
        );

        $performance->update([
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
            'response_rate' => round($responseRate, 2),
            'on_time_shipping_rate' => round($onTimeShippingRate, 2),
            'average_rating' => round($averageRating, 2),
            'return_rate' => round($returnRate, 2),
            'seller_score' => round($sellerScore, 2),
            'seller_level' => $sellerLevel,
            'last_calculated_at' => now(),
        ]);
    }

    protected function calculateResponseRate(Carbon $today): float
    {
        $totalOrders = Order::where('store_id', $this->store->id)->count();
        if ($totalOrders == 0) return 0;

        $respondedOrders = Order::where('store_id', $this->store->id)
            ->whereExists(function ($query) use ($today) {
                $query->selectRaw('1')
                    ->from('support_tickets')
                    ->whereRaw('support_tickets.order_id = orders.id')
                    ->where('support_tickets.created_at', '>=', $today->subDay());
            })
            ->count();

        return ($respondedOrders / $totalOrders) * 100;
    }

    protected function calculateOnTimeShippingRate(Carbon $today): float
    {
        $shippedOrders = Order::where('store_id', $this->store->id)
            ->where('seller_status', 'shipped')
            ->whereNotNull('seller_shipped_at')
            ->get();

        if ($shippedOrders->isEmpty()) return 0;

        $onTime = 0;
        foreach ($shippedOrders as $order) {
            $shippedDate = Carbon::parse($order->seller_shipped_at);
            $estimatedDate = Carbon::parse($order->shipping_estimated_days ? "now + {$order->shipping_estimated_days} days" : "now + 7 days");
            if ($shippedDate->lte($estimatedDate)) {
                $onTime++;
            }
        }

        return ($onTime / $shippedOrders->count()) * 100;
    }

    protected function calculateReturnRate(Carbon $today): float
    {
        $totalOrders = Order::where('store_id', $this->store->id)
            ->where('seller_status', 'completed')
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();

        if ($totalOrders == 0) return 0;

        $returnedOrders = \App\Models\ReturnRequest::whereHas('order', fn($q) => $q->where('store_id', $this->store->id))
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->where('status', 'approved')
            ->count();

        return ($returnedOrders / $totalOrders) * 100;
    }

    protected function calculateSellerScore(array $metrics): float
    {
        $weights = [
            'response_rate' => 0.25,
            'on_time_shipping_rate' => 0.30,
            'average_rating' => 0.25,
            'return_rate' => 0.20,
        ];

        // Normalize: higher is better for all metrics
        $responseScore = $metrics['response_rate'] / 100; // 0-1
        $shippingScore = $metrics['on_time_shipping_rate'] / 100; // 0-1
        $ratingScore = $metrics['average_rating'] / 5; // 0-1 (assuming max rating 5)
        $returnPenalty = 1 - ($metrics['return_rate'] / 100); // Invert: lower return rate = higher score

        $score = (
            $weights['response_rate'] * $responseScore +
            $weights['on_time_shipping_rate'] * $shippingScore +
            $weights['average_rating'] * $ratingScore +
            $weights['return_rate'] * $returnPenalty
        ) * 100; // Scale to 0-100

        return $score;
    }
}