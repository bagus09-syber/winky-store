<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\Category;
use App\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FlashSaleController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function index()
    {
        $activePromotions = $this->promotionService->getActivePromotions();
        $flashSales = Promotion::where('type', 'FLASH_SALE')
            ->where('status', 'active')
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->with(['promotionProducts.product'])
            ->orderBy('priority', 'desc')
            ->get();

        $timeRemaining = now()->between(
            fn() => now()->subHours(1),
            fn() => now()->addDays(7)
        );

        return view('flash-sale', compact('flashSales', 'activePromotions', 'timeRemaining'));
    }

    public function detail(int $promoId)
    {
        $promo = Promotion::with(['promotionProducts.product'])->findOrFail($promoId);
        $isActive = $this->promotionService->isPromotionActive($promo);

        return view('flash-sale-detail', compact('promo', 'isActive'));
    }

    public function checkDiscount(Request $request, Product $product)
    {
        $price = $this->promotionService->calculateDiscount($product, []);

        return ['original_price' => $product->price, 'discounted_price' => max(0, $product->price - $price)];
    }
}