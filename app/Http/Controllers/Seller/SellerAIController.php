<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\AI\AIService;
use App\Services\AI\SellerInsightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerAIController extends Controller
{
    protected AIService $ai;

    public function __construct(AIService $ai)
    {
        $this->ai = $ai;
    }

    public function generateTitle(Request $request)
    {
        $validated = $request->validate([
            'keywords' => 'required|string|max:200',
        ]);

        $keywords = array_filter(explode(' ', $validated['keywords']));
        $title = $this->ai->generateProductContent($keywords, 'title');

        return response()->json([
            'success' => true,
            'title' => $title,
        ]);
    }

    public function generateDescription(Request $request)
    {
        $validated = $request->validate([
            'keywords' => 'required|string|max:200',
            'type' => 'nullable|in:description,features,benefits,specifications',
        ]);

        $keywords = array_filter(explode(' ', $validated['keywords']));
        $description = $this->ai->generateProductContent($keywords, $validated['type'] ?? 'description');

        return response()->json([
            'success' => true,
            'description' => $description,
        ]);
    }

    public function generateSEO(Request $request)
    {
        $validated = $request->validate([
            'keywords' => 'required|string|max:200',
        ]);

        $keywords = array_filter(explode(' ', $validated['keywords']));

        $seoTitle = $this->ai->generateProductContent($keywords, 'seo_title');
        $metaDescription = $this->ai->generateProductContent($keywords, 'meta_description');

        return response()->json([
            'success' => true,
            'seo_title' => $seoTitle,
            'meta_description' => $metaDescription,
            'keywords' => $validated['keywords'],
        ]);
    }

    public function analyzeProduct(Request $request, int $productId)
    {
        $store = Auth::user()->store;

        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan'], 404);
        }

        $product = Product::where('id', $productId)
            ->where('store_id', $store->id)
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $analysis = $this->ai->analyzeProduct([
            'name' => $product->name,
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'stock' => $product->stock,
            'description' => $product->description,
            'short_description' => $product->short_description,
            'average_rating' => $product->average_rating,
            'reviews_count' => $product->reviews_count,
            'is_featured' => $product->is_featured,
        ]);

        return response()->json([
            'success' => true,
            'analysis' => $analysis,
        ]);
    }

    public function insights()
    {
        $store = Auth::user()->store;

        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan'], 404);
        }

        $insightService = new SellerInsightService($store);
        $insights = $insightService->getDashboardInsights();

        return response()->json([
            'success' => true,
            'insights' => $insights,
        ]);
    }
}
