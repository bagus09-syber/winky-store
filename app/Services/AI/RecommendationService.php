<?php

namespace App\Services\AI;

use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RecommendationService
{
    public function getRecentlyViewed(int $limit = 10): array
    {
        $key = $this->getSessionKey();

        try {
            $viewed = Cache::get("recently_viewed:{$key}", []);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache error in getRecentlyViewed", ['error' => $e->getMessage()]);
            $viewed = [];
        }

        if (empty($viewed)) return [];

        $products = Product::whereIn('id', array_slice($viewed, 0, $limit))
            ->where('is_active', true)
            ->with(['category', 'brand'])
            ->get()
            ->keyBy('id');

        $result = [];
        foreach (array_slice($viewed, 0, $limit) as $pid) {
            if (isset($products[$pid])) {
                $p = $products[$pid];
                $result[] = [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->price,
                    'sale_price' => $p->sale_price,
                    'effective_price' => $p->getEffectivePrice(),
                    'image' => $p->image,
                    'brand' => $p->brand->name ?? '-',
                ];
            }
        }

        return $result;
    }

    public function trackProductView(int $productId): void
    {
        $key = $this->getSessionKey();
        $cacheKey = "recently_viewed:{$key}";

        try {
            $viewed = Cache::get($cacheKey, []);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache error in trackProductView get", ['error' => $e->getMessage()]);
            $viewed = [];
        }

        $viewed = array_filter($viewed, fn($id) => $id !== $productId);
        array_unshift($viewed, $productId);
        $viewed = array_slice($viewed, 0, 50);

        try {
            Cache::put($cacheKey, array_values($viewed), 86400);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache put error in trackProductView put", ['error' => $e->getMessage()]);
        }
    }

    public function getSimilarProducts(Product $product, int $limit = 6): array
    {
        if (!$product->id) return [];

        $cacheKey = "similar:{$product->id}";
        try {
            $cached = Cache::get($cacheKey);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache error in getSimilarProducts", ['error' => $e->getMessage()]);
            $cached = null;
        }
        if ($cached) return $cached;

        $products = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                  ->orWhere('brand_id', $product->brand_id);
            })
            ->with(['category', 'brand'])
            ->get()
            ->map(function ($p) use ($product) {
                $similarity = 0;
                if ($p->category_id === $product->category_id) $similarity += 50;
                if ($p->brand_id === $product->brand_id) $similarity += 30;
                $priceDiff = abs($p->price - $product->price) / max($product->price, 1);
                $similarity += max(0, 20 - ($priceDiff * 20));
                $p->similarity = $similarity;
                return $p;
            })
            ->sortByDesc('similarity')
            ->take($limit)
            ->values()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => $p->price,
                'sale_price' => $p->sale_price,
                'effective_price' => $p->getEffectivePrice(),
                'image' => $p->image,
                'brand' => $p->brand->name ?? '-',
                'average_rating' => $p->average_rating,
            ])
            ->toArray();

        try {
            Cache::put($cacheKey, $products, 600);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache put error in getSimilarProducts", ['error' => $e->getMessage()]);
        }
        return $products;
    }

    public function getFrequentlyBoughtTogether(Product $product, int $limit = 4): array
    {
        if (!$product->id) return [];

        $cacheKey = "fbt:{$product->id}";
        try {
            $cached = Cache::get($cacheKey);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache error in getFrequentlyBoughtTogether", ['error' => $e->getMessage()]);
            $cached = null;
        }
        if ($cached) return $cached;

        $orderIds = OrderItem::where('product_id', $product->id)
            ->pluck('order_id')
            ->unique()
            ->take(100);

        if ($orderIds->isEmpty()) return [];

        try {
            $products = OrderItem::whereIn('order_id', $orderIds)
                ->where('product_id', '!=', $product->id)
                ->select('product_id', DB::raw('COUNT(*) as frequency'))
                ->groupBy('product_id')
                ->orderByDesc('frequency')
                ->take($limit)
                ->pluck('product_id')
                ->toArray();
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: DB error in getFrequentlyBoughtTogether", ['error' => $e->getMessage()]);
            return [];
        }

        if (empty($products)) return [];

        try {
            $result = Product::whereIn('id', $products)
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->with(['category', 'brand'])
                ->get()
                ->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->price,
                    'sale_price' => $p->sale_price,
                    'effective_price' => $p->getEffectivePrice(),
                    'image' => $p->image,
                ])
                ->toArray();
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: DB error in getFrequentlyBoughtTogether result", ['error' => $e->getMessage()]);
            return [];
        }

        try {
            Cache::put($cacheKey, $result, 600);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache put error in getFrequentlyBoughtTogether", ['error' => $e->getMessage()]);
        }
        return $result;
    }

public function getTrendingProducts(int $limit = 10): array
    {
        $cacheKey = 'ai:trending_products';
        try {
            $cached = Cache::get($cacheKey);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache error in getTrendingProducts", ['error' => $e->getMessage()]);
            $cached = null;
        }
        if ($cached) return $cached;

        try {
            $products = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->with(['category', 'brand'])
                ->select('products.*', DB::raw('
                    (SELECT COUNT(*) FROM order_items WHERE order_items.product_id = products.id) as order_count,
                    (SELECT COUNT(*) FROM wishlists WHERE wishlists.product_id = products.id) as wishlist_count
                '))
                ->orderByRaw('order_count + wishlist_count DESC')
                ->take($limit)
                ->get()
                ->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->price,
                    'sale_price' => $p->sale_price,
                    'effective_price' => $p->getEffectivePrice(),
                    'image' => $p->image,
                    'brand' => $p->brand->name ?? '-',
                    'order_count' => $p->order_count,
                ])
                ->toArray();
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: DB error in getTrendingProducts", ['error' => $e->getMessage()]);
            return [];
        }

        try {
            Cache::put($cacheKey, $products, 300);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache put error in getTrendingProducts", ['error' => $e->getMessage()]);
        }
        return $products;
    }

public function getPopularProducts(int $limit = 10): array
    {
        $cacheKey = 'ai:popular_products';
        try {
            $cached = Cache::get($cacheKey);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache error in getPopularProducts", ['error' => $e->getMessage()]);
            $cached = null;
        }
        if ($cached) return $cached;

        try {
            $products = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->with(['category', 'brand'])
                ->orderByRaw('(SELECT COUNT(*) FROM reviews WHERE reviews.product_id = products.id AND is_approved = 1) DESC')
                ->take($limit)
                ->get()
                ->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->price,
                    'sale_price' => $p->sale_price,
                    'effective_price' => $p->getEffectivePrice(),
                    'image' => $p->image,
                    'brand' => $p->brand->name ?? '-',
                    'average_rating' => $p->average_rating,
                ])
                ->toArray();
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: DB error in getPopularProducts", ['error' => $e->getMessage()]);
            return [];
        }

        try {
            Cache::put($cacheKey, $products, 600);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache put error in getPopularProducts", ['error' => $e->getMessage()]);
        }
        return $products;
    }

    public function getRecommendedForUser(int $userId, int $limit = 10): array
    {
        $cacheKey = "recommended:{$userId}";
        try {
            $cached = Cache::get($cacheKey);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache error in getRecommendedForUser", ['error' => $e->getMessage()]);
            $cached = null;
        }
        if ($cached) return $cached;

        try {
            $purchasedCategoryIds = OrderItem::whereHas('order', fn($q) => $q->where('user_id', $userId))
                ->pluck('product_id')
                ->unique();
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: DB error in getRecommendedForUser purchased", ['error' => $e->getMessage()]);
            $purchasedCategoryIds = collect();
        }

        try {
            $wishlistCategoryIds = Wishlist::where('user_id', $userId)
                ->pluck('product_id')
                ->unique();
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: DB error in getRecommendedForUser wishlist", ['error' => $e->getMessage()]);
            $wishlistCategoryIds = collect();
        }

        $allInteracted = $purchasedCategoryIds->merge($wishlistCategoryIds)->unique();

        if ($allInteracted->isEmpty()) {
            return $this->getTrendingProducts($limit);
        }

        try {
            $categoryScores = DB::table('products')
                ->whereIn('id', $allInteracted)
                ->select('category_id', DB::raw('COUNT(*) as count'))
                ->groupBy('category_id')
                ->pluck('count', 'category_id')
                ->toArray();
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: DB error in getRecommendedForUser category scores", ['error' => $e->getMessage()]);
            return $this->getTrendingProducts($limit);
        }

        arsort($categoryScores);
        $topCategories = array_slice(array_keys($categoryScores), 0, 3);

        try {
            $products = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->whereNotIn('id', $allInteracted->toArray())
                ->whereIn('category_id', $topCategories)
                ->with(['category', 'brand'])
                ->take($limit)
                ->get()
                ->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->price,
                    'sale_price' => $p->sale_price,
                    'effective_price' => $p->getEffectivePrice(),
                    'image' => $p->image,
                    'brand' => $p->brand->name ?? '-',
                    'average_rating' => $p->average_rating,
                ])
                ->toArray();
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: DB error in getRecommendedForUser products", ['error' => $e->getMessage()]);
            return $this->getTrendingProducts($limit);
        }

        try {
            Cache::put($cacheKey, $products, 900);
        } catch (\Exception $e) {
            \Log::warning("RecommendationService: Cache put error in getRecommendedForUser", ['error' => $e->getMessage()]);
        }
        return $products;
    }

    public function getWishlistProducts(int $userId, int $limit = 5): array
    {
        return Wishlist::where('user_id', $userId)
            ->with(['product.category', 'product.brand'])
            ->take($limit)
            ->get()
            ->map(fn($w) => $w->product ? [
                'id' => $w->product->id,
                'name' => $w->product->name,
                'slug' => $w->product->slug,
                'price' => $w->product->price,
                'sale_price' => $w->product->sale_price,
                'effective_price' => $w->product->getEffectivePrice(),
                'image' => $w->product->image,
            ] : null)
            ->filter()
            ->toArray();
    }

    private function getSessionKey(): string
    {
        if (auth()->check()) {
            return 'user:' . auth()->id();
        }
        return 'session:' . Session::getId();
    }
}
