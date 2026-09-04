<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CompareController extends Controller
{
    public function index()
    {
        $compareIds = Session::get('compare_products', []);
        $products = Product::whereIn('id', $compareIds)
            ->with(['category', 'brand', 'reviews'])
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
                'category' => $p->category->name ?? '-',
                'stock' => $p->stock,
                'average_rating' => $p->average_rating,
                'reviews_count' => $p->reviews_count,
                'weight' => $p->weight,
                'description' => $p->short_description ?? $p->description ?? '-',
            ])
            ->toArray();

        return view('compare.index', compact('products'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $compareIds = Session::get('compare_products', []);

        if (in_array($validated['product_id'], $compareIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sudah ada di daftar perbandingan.',
            ]);
        }

        if (count($compareIds) >= 4) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimal 4 produk untuk perbandingan.',
            ]);
        }

        $compareIds[] = $validated['product_id'];
        Session::put('compare_products', $compareIds);

        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan ke perbandingan.',
            'count' => count($compareIds),
        ]);
    }

    public function remove(Request $request, int $productId)
    {
        $compareIds = Session::get('compare_products', []);
        $compareIds = array_filter($compareIds, fn($id) => $id !== $productId);
        Session::put('compare_products', array_values($compareIds));

        return response()->json([
            'success' => true,
            'message' => 'Produk dihapus dari perbandingan.',
            'count' => count($compareIds),
        ]);
    }

    public function clear()
    {
        Session::forget('compare_products');

        return response()->json([
            'success' => true,
            'message' => 'Semua produk dihapus dari perbandingan.',
        ]);
    }

    public function getCount()
    {
        $count = count(Session::get('compare_products', []));

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }
}
