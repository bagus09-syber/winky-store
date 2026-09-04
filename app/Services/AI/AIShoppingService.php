<?php

namespace App\Services\AI;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class AIShoppingService
{
    protected AIService $ai;

    public function __construct(AIService $ai)
    {
        $this->ai = $ai;
    }

    public function searchProductsFromIntent(string $query): array
    {
        $intent = $this->ai->parseSearchIntent($query);

        $results = [
            'intent' => $intent,
            'products' => $intent['products'] ?? [],
            'suggested_categories' => $this->getSuggestedCategories($intent),
            'suggested_brands' => $this->getSuggestedBrands($intent),
            'query' => $query,
        ];

        if (empty($results['products'])) {
            $results['message'] = 'Tidak ditemukan produk yang cocok. Coba kata kunci lain.';
        } else {
            $count = count($results['products']);
            $results['message'] = "Ditemukan {$count} produk untuk pencarian ini.";
        }

        return $results;
    }

    public function smartSearch(string $query, int $limit = 20): array
    {
        $queryBuilder = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->with(['category', 'brand', 'store']);

        $words = preg_split('/\s+/', mb_strtolower($query));
        $words = array_filter($words, fn($w) => strlen($w) > 1);

        if (!empty($words)) {
            $queryBuilder->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere('name', 'like', "%{$word}%")
                      ->orWhere('short_description', 'like', "%{$word}%")
                      ->orWhere('sku', 'like', "%{$word}%")
                      ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$word}%"))
                      ->orWhereHas('brand', fn($bq) => $bq->where('name', 'like', "%{$word}%"));
                }
            });
        }

        $products = $queryBuilder->take($limit)->get();

        $intent = $this->ai->parseSearchIntent($query);

        if (!empty($intent['min_price'])) {
            $products = $products->filter(fn($p) => $p->getEffectivePrice() >= $intent['min_price']);
        }
        if (!empty($intent['max_price'])) {
            $products = $products->filter(fn($p) => $p->getEffectivePrice() <= $intent['max_price']);
        }

        return [
            'query' => $query,
            'intent' => $intent,
            'products' => $products->values()->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => $p->price,
                'sale_price' => $p->sale_price,
                'effective_price' => $p->getEffectivePrice(),
                'image' => $p->image,
                'brand' => $p->brand->name ?? '-',
                'category' => $p->category->name ?? '-',
                'average_rating' => $p->average_rating,
                'reviews_count' => $p->reviews_count,
                'store' => $p->store->name ?? '-',
            ])->toArray(),
        ];
    }

    public function getSuggestions(string $query): array
    {
        if (strlen($query) < 2) return [];

        $products = Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->take(5)
            ->pluck('name', 'id')
            ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
            ->values()
            ->toArray();

        $categories = Category::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->take(3)
            ->pluck('name', 'slug')
            ->map(fn($name, $slug) => ['slug' => $slug, 'name' => $name, 'type' => 'category'])
            ->values()
            ->toArray();

        $brands = Brand::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->take(3)
            ->pluck('name', 'slug')
            ->map(fn($name, $slug) => ['slug' => $slug, 'name' => $name, 'type' => 'brand'])
            ->values()
            ->toArray();

        return array_merge($products, $categories, $brands);
    }

    private function getSuggestedCategories(array $intent): array
    {
        if (!empty($intent['category'])) {
            return Category::where('id', $intent['category'])->get()
                ->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'slug' => $c->slug])
                ->toArray();
        }

        if (!empty($intent['keywords'])) {
            return Category::where('is_active', true)
                ->where(function ($q) use ($intent) {
                    foreach ($intent['keywords'] as $kw) {
                        $q->orWhere('name', 'like', "%{$kw}%");
                    }
                })
                ->take(5)
                ->get()
                ->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'slug' => $c->slug])
                ->toArray();
        }

        return [];
    }

    private function getSuggestedBrands(array $intent): array
    {
        if (!empty($intent['brand'])) {
            return Brand::where('id', $intent['brand'])->get()
                ->map(fn($b) => ['id' => $b->id, 'name' => $b->name, 'slug' => $b->slug])
                ->toArray();
        }

        if (!empty($intent['keywords'])) {
            return Brand::where('is_active', true)
                ->where(function ($q) use ($intent) {
                    foreach ($intent['keywords'] as $kw) {
                        $q->orWhere('name', 'like', "%{$kw}%");
                    }
                })
                ->take(5)
                ->get()
                ->map(fn($b) => ['id' => $b->id, 'name' => $b->name, 'slug' => $b->slug])
                ->toArray();
        }

        return [];
    }
}
