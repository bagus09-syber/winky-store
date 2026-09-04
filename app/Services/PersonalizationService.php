<?php

namespace App\Services;

use App\Models\Signal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PersonalizationService
{
    const CACHE_TTL = 3600; // 1 hour

    public function trackView(int $userId, int $productId, string $category = null, string $brand = null): void
    {
        DB::transaction(function () use ($userId, $productId, $category, $brand) {
            // Record the view signal
            Signal::create([
                'user_id' => $userId,
                'type' => 'product_view',
                'related_id' => $productId,
                'metadata' => [
                    'category' => $category,
                    'brand' => $brand,
                ],
            ]);

            // Update cache-based recommendations
            $cacheKey = "winky:recommendations:{$userId}";
            $existing = Cache::get($cacheKey, []);
            
            // Add to front, limit to 20
            array_unshift($existing, ['product_id' => $productId, 'created_at' => now()->toString()]);
            if (count($existing) > 20) {
                array_pop($existing);
            }
            
            Cache::put($cacheKey, $existing, self::CACHE_TTL);
        });
    }

    public function trackWishlist(int $userId, int $productId): void
    {
        DB::transaction(function () use ($userId, $productId) {
            Signal::create([
                'user_id' => $userId,
                'type' => 'wishlist_add',
                'related_id' => $productId,
            ]);
        });
    }

    public function trackCartAdd(int $userId, int $productId): void
    {
        DB::transaction(function () use ($userId, $productId) {
            Signal::create([
                'user_id' => $userId,
                'type' => 'cart_add',
                'related_id' => $productId,
            ]);
        });
    }

    public function trackPurchase(int $userId, int $orderId, array $productIds): void
    {
        DB::transaction(function () use ($userId, $orderId, $productIds) {
            foreach ($productIds as $productId) {
                Signal::create([
                    'user_id' => $userId,
                    'type' => 'purchase',
                    'related_id' => $productId,
                ]);
            }
        });
    }

    public function getRecommendations(int $userId, int $limit = 10): array
    {
        return DB::transaction(function () use ($userId, $limit) {
            // Get user's signals
            $signals = Signal::where('user_id', $userId)
                ->latest('created_at')
                ->take(50)
                ->get();

            if ($signals->isEmpty()) {
                // Fall back to trending products
                return $this->getTrendingProducts($limit);
            }

            // Extract product IDs from signals
            $productIds = $signals->pluck('related_id')->unique()->filter();

            if ($productIds->isEmpty()) {
                return $this->getTrendingProducts($limit);
            }

            // Get products not recently viewed
            $recentProductIds = Cache::get("winky:recent:{$userId}", []);
            
            $availableProductIds = $productIds->diff($recentProductIds);

            if ($availableProductIds->isEmpty()) {
                return $this->getTrendingProducts($limit);
            }

            // Query products
            $products = Product::whereIn('id', $availableProductIds)
                ->where('is_active', true)
                ->take($limit)
                ->get()
                ->keyBy('id');

            return $products->values()->toArray();
        });
    }

    private function getTrendingProducts(int $limit): array
    {
        // Get trending based on signal counts across all users
        $productIds = Signal::where('type', 'product_view')
            ->where('created_at', '>=', now()->subDays(30))
            ->pluck('related_id')
            ->countBy();

        arsort($productIds);

        $topIds = array_keys(array_slice($productIds, 0, $limit, true));

        return Product::whereIn('id', $topIds)
            ->where('is_active', true)
            ->get()
            ->toArray();
    }

    public function getPersonalizedHomepage(int $userId): array
    {
        return [
            'continue_shopping' => $this->getContinueShopping($userId),
            'because_you_viewed' => $this->getBecauseYouViewed($userId),
            'recommended_for_you' => $this->getRecommendations($userId, 8),
            'trending_near_you' => $this->getTrendingProducts(6),
        ];
    }

    private function getContinueShopping(int $userId): array
    {
        // Get recently viewed products
        $cacheKey = "winky:recent:{$userId}";
        $recent = Cache::get($cacheKey, []);

        $productIds = array_map(fn($p) => $p['product_id'], $recent);

        if (empty($productIds)) {
            return Product::where('is_active', true)
                ->inRandomOrder()
                ->take(4)
                ->get()
                ->toArray();
        }

        return Product::whereIn('id', $productIds)
            ->where('is_active', true)
            ->take(4)
            ->get()
            ->toArray();
    }

    private function getBecauseYouViewed(int $userId): array
    {
        // Get products similar to what user viewed
        $signals = Signal::where('user_id', $userId)
            ->where('type', 'product_view')
            ->latest('created_at')
            ->take(5)
            ->get();

        if ($signals->isEmpty()) {
            return Product::where('is_active', true)
                ->inRandomOrder()
                ->take(4)
                ->get()
                ->toArray();
        }

        $lastViewed = $signals->last()->related_id;

        // Get products from same category
        $product = Product::find($lastViewed);
        if ($product && $product->category) {
            $sameCategory = Product::where('category_id', $product->category_id)
                ->where('is_active', true)
                ->where('id', '!=', $lastViewed)
                ->take(4)
                ->get();
            
            if ($sameCategory->isNotEmpty()) {
                return $sameCategory->toArray();
            }
        }

        return Product::where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get()
            ->toArray();
    }
}