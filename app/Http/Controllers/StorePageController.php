<?php

namespace App\Http\Controllers;

use App\Models\Store;

class StorePageController extends Controller
{
    public function show(Store $store)
    {
        if ($store->status !== 'active') {
            abort(404);
        }

        $store->load('user');
        $totalProducts = $store->products()->where('is_active', true)->count();
        $avgRating = $store->getAvgRatingAttribute();
        $reviewsCount = $store->reviews()->where('is_approved', true)->count();

        return view('store.show', compact('store', 'totalProducts', 'avgRating', 'reviewsCount'));
    }

    public function products(Store $store)
    {
        if ($store->status !== 'active') {
            abort(404);
        }

        $products = $store->products()
            ->with(['category', 'brand'])
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('store.products', compact('store', 'products'));
    }

    public function reviews(Store $store)
    {
        if ($store->status !== 'active') {
            abort(404);
        }

        $reviews = $store->reviews()
            ->with(['user', 'product'])
            ->where('is_approved', true)
            ->latest()
            ->paginate(15);

        $avgRating = $store->getAvgRatingAttribute();
        $reviewsCount = $reviews->total();

        return view('store.reviews', compact('store', 'reviews', 'avgRating', 'reviewsCount'));
    }
}
