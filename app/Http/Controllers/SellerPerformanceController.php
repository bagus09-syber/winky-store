<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\SellerPerformanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerPerformanceController extends Controller
{
    protected SellerPerformanceService $performanceService;

    public function __construct(SellerPerformanceService $performanceService)
    {
        $this->performanceService = $performanceService;
    }

    public function index(Store $store)
    {
        // Calculate and update performance
        $this->performanceService->calculateAndUpdate();

        $performance = SellerPerformance::where('store_id', $store->id)->first();

        return response()->json([
            'success' => true,
            'performance' => $performance,
        ]);
    }

    public function dashboard()
    {
        // Get all stores with their performance
        $stores = Store::with(['performance' => function ($query) {
            $query->latest('last_calculated_at')->first();
        }])->get();

        $performanceData = $stores->map(fn($store) => [
            'store_name' => $store->name,
            'seller_level' => $store->performance->seller_level ?? 'bronze',
            'seller_score' => $store->performance->seller_score ?? 0,
            'total_orders' => $store->performance->total_orders ?? 0,
            'completed_orders' => $store->performance->completed_orders ?? 0,
            'cancelled_orders' => $store->performance->cancelled_orders ?? 0,
            'average_rating' => $store->performance->average_rating ?? 0,
            'response_rate' => $store->performance->response_rate ?? 0,
            'on_time_shipping_rate' => $store->performance->on_time_shipping_rate ?? 0,
            'return_rate' => $store->performance->return_rate ?? 0,
        ])->sortByDesc(fn($s) => $s['seller_score']);

        return response()->json([
            'success' => true,
            'performance_data' => $performanceData->values()->toArray(),
        ]);
    }
}