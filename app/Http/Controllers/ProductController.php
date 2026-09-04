<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Services\AI\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants'])
            ->where('is_active', true);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhereHas('brand', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('category', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter (supports parent + child slugs)
        if ($category = $request->input('category')) {
            $cat = Category::where('slug', $category)->first();
            if ($cat) {
                if (is_null($cat->parent_id)) {
                    $childIds = $cat->children()->pluck('id')->toArray();
                    $query->where(function ($q) use ($cat, $childIds) {
                        $q->where('category_id', $cat->id)
                          ->orWhereIn('category_id', $childIds);
                    });
                } else {
                    $query->where('category_id', $cat->id);
                }
            }
        }

        // Brand filter (multi-select via comma-separated slugs)
        if ($brand = $request->input('brand')) {
            $brandSlugs = array_filter(explode(',', $brand));
            if (count($brandSlugs) === 1) {
                $query->whereHas('brand', function ($bq) use ($brandSlugs) {
                    $bq->where('slug', $brandSlugs[0]);
                });
            } elseif (count($brandSlugs) > 1) {
                $query->whereHas('brand', function ($bq) use ($brandSlugs) {
                    $bq->whereIn('slug', $brandSlugs);
                });
            }
        }

        // Price range
        if ($minPrice = $request->input('min_price')) {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$minPrice]);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$maxPrice]);
        }

        // Discount filter
        if ($minDiscount = $request->input('min_discount')) {
            $query->whereNotNull('sale_price')
                  ->where('sale_price', '>', 0)
                  ->whereColumn('sale_price', '<', 'price')
                  ->whereRaw('ROUND((1 - sale_price / price) * 100) >= ?', [$minDiscount]);
        }

        // Availability filter
        if ($request->input('in_stock') === '1') {
            $query->where('stock', '>', 0);
        } elseif ($request->input('in_stock') === '0') {
            $query->where('stock', '<=', 0);
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'name_az':
                $query->orderBy('name', 'ASC');
                break;
            case 'best_selling':
                $query->orderBy('is_featured', 'DESC')->latest();
                break;
            case 'biggest_discount':
                $query->orderByRaw('COALESCE(sale_price, price) - price ASC');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'DESC');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Sidebar data
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->where('is_active', true)->withCount('products');
            }])
            ->get()
            ->map(function ($cat) {
                $cat->total_products_count = $cat->products_count + $cat->children->sum('products_count');
                return $cat;
            });

        $brands = Brand::where('is_active', true)->withCount('products')->get();

        // Active filter count
        $activeFilterCount = 0;
        if ($request->category) $activeFilterCount++;
        if ($request->brand) $activeFilterCount++;
        if ($request->min_price || $request->max_price) $activeFilterCount++;
        if ($request->min_discount) $activeFilterCount++;
        if ($request->in_stock !== null) $activeFilterCount++;

        return view('products.index', compact('products', 'categories', 'brands', 'activeFilterCount'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'variants', 'images']);

        $recommendationService = app(RecommendationService::class);
        $recommendationService->trackProductView($product->id);

        $similarProducts = $recommendationService->getSimilarProducts($product, 4);
        $frequentlyBought = $recommendationService->getFrequentlyBoughtTogether($product, 4);

        $recentlyViewed = $recommendationService->getRecentlyViewed(6);
        $recentlyViewed = array_filter($recentlyViewed, fn($p) => $p['id'] !== $product->id);

        return view('products.show', compact('product', 'similarProducts', 'frequentlyBought', 'recentlyViewed'));
    }

    public function quickView(Request $request): JsonResponse
    {
        $product = Product::with(['category', 'brand', 'variants'])
            ->where('is_active', true)
            ->where('slug', $request->slug)
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'brand' => $product->brand->name ?? '',
            'category' => $product->category->name ?? '',
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'effective_price' => $product->getEffectivePrice(),
            'discount_percent' => $product->getDiscountPercent(),
            'stock' => $product->stock,
            'image' => $product->image ? asset('storage/' . $product->image) : asset('images/products/no-image.png'),
            'short_description' => $product->short_description ?? '',
            'description' => $product->description ?? '',
            'is_featured' => $product->is_featured,
            'variants' => $product->variants->map(function ($v) {
                return [
                    'id' => $v->id,
                    'name' => $v->name,
                    'price' => $v->price,
                    'stock' => $v->stock,
                ];
            }),
        ]);
    }

    public function searchApi(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        if (strlen($query) < 2) {
            return response()->json(['products' => [], 'categories' => [], 'brands' => []]);
        }

        // Products
        $products = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%")
                  ->orWhereHas('brand', fn($bq) => $bq->where('name', 'like', "%{$query}%"))
                  ->orWhereHas('category', fn($bq) => $bq->where('name', 'like', "%{$query}%"));
            })
            ->take(6)
            ->get()
            ->map(function ($p) {
                return [
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'brand' => $p->brand->name ?? '',
                    'category' => $p->category->name ?? '',
                    'price' => $p->price,
                    'effective_price' => $p->getEffectivePrice(),
                    'discount_percent' => $p->getDiscountPercent(),
                    'image' => $p->image ? asset('storage/' . $p->image) : asset('images/products/no-image.png'),
                ];
            });

        // Categories
        $categories = Category::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->take(4)
            ->get()
            ->map(fn($c) => ['name' => $c->name, 'slug' => $c->slug, 'icon' => $c->icon]);

        // Brands
        $brands = Brand::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->take(4)
            ->get()
            ->map(fn($b) => ['name' => $b->name, 'slug' => $b->slug]);

        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }
}
