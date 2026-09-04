<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\PromotionProduct;
use App\Models\PromotionCategory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PromotionService
{
    const CACHE_KEY_ACTIVE_PROMOTIONS = 'winky:active_promotions';

    public function getActivePromotions(): array
    {
        $cacheKey = self::CACHE_KEY_ACTIVE_PROMOTIONS;
        $promotions = Cache::remember($cacheKey, now()->startOfDay(), function () {
            return Promotion::where('status', 'active')
                ->where('start_at', '<=', now())
                ->where('end_at', '>=', now())
                ->with(['promotionProducts', 'promotionCategories'])
                ->get()
                ->sortByDesc('priority')
                ->values()
                ->all();
        });

        return $promotions->toArray();
    }

    public function calculateDiscount(Product $product, array $cartItems = []): float
    {
        $activePromotions = $this->getActivePromotions();
        $maxDiscount = 0;

        foreach ($activePromotions as $promo) {
            $discount = match($promo['type']) {
                'FLASH_SALE' => $this->calculateFlashSaleDiscount($promo, $product),
                'PRODUCT_DISCOUNT' => $this->calculateProductDiscount($promo, $product),
                'CATEGORY_DISCOUNT' => $this->calculateCategoryDiscount($promo, $product),
                'BUNDLE' => $this->calculateBundleDiscount($promo, $cartItems),
                'BUY_X_GET_Y' => $this->calculateBuyXGetYDiscount($promo, $cartItems),
                default => 0,
            };

            if ($discount > $maxDiscount) {
                $maxDiscount = $discount;
            }
        }

        // Ensure price never goes negative
        return min($maxDiscount, $product->price);
    }

    private function calculateFlashSaleDiscount(array $promo, Product $product): float
    {
        $promotionProducts = PromotionProduct::where('promotion_id', $promo['id'])
            ->where('product_id', $product->id)
            ->first();

        if ($promotionProducts) {
            return match($promo['discount_type']) {
                'PERCENTAGE' => ($promo['discount_value'] / 100) * $product->price,
                'FIXED' => $promo['discount_value'],
                default => 0,
            };
        }

        return 0;
    }

    private function calculateProductDiscount(array $promo, Product $product): float
    {
        if ($promo['discount_type'] === 'PRODUCT_DISCOUNT') {
            $productMatch = PromotionProduct::where('promotion_id', $promo['id'])
                ->where('product_id', $product->id)
                ->first();

            if ($productMatch) {
                return match($promo['discount_type']) {
                    'PERCENTAGE' => ($promo['discount_value'] / 100) * $product->price,
                    'FIXED' => $promo['discount_value'],
                    default => 0,
                };
            }
        }

        return 0;
    }

    private function calculateCategoryDiscount(array $promo, Product $product): float
    {
        if ($promo['discount_type'] === 'CATEGORY_DISCOUNT') {
            $categoryIds = $product->category->allChildren()->pluck('id');

            $categoryMatch = PromotionCategory::where('promotion_id', $promo['id'])
                ->whereIn('category_id', $categoryIds)
                ->exists();

            if ($categoryMatch) {
                return match($promo['discount_type']) {
                    'PERCENTAGE' => ($promo['discount_value'] / 100) * $product->price,
                    'FIXED' => $promo['discount_value'],
                    default => 0,
                };
            }
        }

        return 0;
    }

    private function calculateBundleDiscount(array $promo, array $cartItems): float
    {
        // Bundle logic - calculate based on items in cart
        $bundleDiscount = 0;

        $promotionProducts = PromotionProduct::where('promotion_id', $promo['id'])
            ->get();

        foreach ($promotionProducts as $pp) {
            foreach ($cartItems as $item) {
                if ($item->product_id === $pp->product_id) {
                    $bundleDiscount += match($promo['discount_type']) {
                        'PERCENTAGE' => ($promo['discount_value'] / 100) * $item->price,
                        'FIXED' => $promo['discount_value'],
                        default => 0,
                    };
                }
            }
        }

        return $bundleDiscount;
    }

    private function calculateBuyXGetYDiscount(array $promo, array $cartItems): float
    {
        // Buy X Get Y logic
        $xCount = 0;
        $yCount = 0;

        foreach ($cartItems as $item) {
            // Count qualifying items
        }

        // Simplified - return fixed discount if conditions met
        return 0;
    }

    public function schedulePromotion(int $promoId, string $status): bool
    {
        return DB::transaction(function () use ($promoId, $status) {
            $promo = Promotion::findOrFail($promoId);
            $promo->status = $status;
            $promo->save();

            // Clear cache if deactivating
            if ($status !== 'active') {
                Cache::forget(self::CACHE_KEY_ACTIVE_PROMOTIONS);
            }

            return true;
        });
    }

    public function validateDates(): bool
    {
        $promotions = Promotion::all();

        foreach ($promotions as $promo) {
            if ($promo->start_at >= $promo->end_at) {
                return false; // Invalid dates
            }
        }

        return true;
    }
}