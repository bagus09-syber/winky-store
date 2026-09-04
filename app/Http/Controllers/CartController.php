<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->getOrCreateCart()->load('items.product', 'items.variant');
        return view('cart.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = null;
        $price = $product->getEffectivePrice();
        $stock = $product->stock;

        if ($request->product_variant_id) {
            $variant = ProductVariant::findOrFail($request->product_variant_id);
            $price = $variant->price;
            $stock = $variant->stock;
        }

        if ($request->quantity > $stock) {
            return back()->withErrors(['quantity' => 'Stok tidak mencukupi.']);
        }

        $cart = Auth::user()->getOrCreateCart();

        $existingItem = $cart->items()
            ->where('product_id', $request->product_id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $request->quantity;
            if ($newQuantity > $stock) {
                return back()->withErrors(['quantity' => 'Jumlah melebihi stok yang tersedia.']);
            }
            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity,
                'price' => $price,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $stock = $cartItem->variant ? $cartItem->variant->stock : $cartItem->product->stock;

        if ($request->quantity > $stock) {
            return back()->withErrors(['quantity' => 'Stok tidak mencukupi.']);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function validateVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('code', strtoupper($request->code))->first();

        if (!$voucher || !$voucher->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode voucher tidak valid atau sudah kadaluarsa.',
            ]);
        }

        if ($request->subtotal < $voucher->minimum_order) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($voucher->minimum_order, 0, ',', '.') . ' untuk menggunakan voucher ini.',
            ]);
        }

        $discount = $voucher->calculateDiscount($request->subtotal);

        return response()->json([
            'valid' => true,
            'code' => $voucher->code,
            'type' => $voucher->type,
            'value' => $voucher->value,
            'discount' => $discount,
            'message' => 'Voucher berhasil diterapkan!',
        ]);
    }
}
