<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SellerTransaction;
use App\Models\Voucher;
use App\Services\InventoryService;
use App\Services\SellerSettlementService;
use App\Services\Shipping\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected ShippingService $shippingService;
    protected InventoryService $inventoryService;
    protected SellerSettlementService $settlementService;

    public function __construct(ShippingService $shippingService, InventoryService $inventoryService, SellerSettlementService $settlementService)
    {
        $this->shippingService = $shippingService;
        $this->inventoryService = $inventoryService;
        $this->settlementService = $settlementService;
    }

    public function index()
    {
        $user = Auth::user();
        $cart = $user->getOrCreateCart()->load('items.product.store', 'items.variant');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Keranjang kamu kosong.');
        }

        $defaultAddress = $user->addresses()->where('is_default', true)->first();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();

        $grouped = $cart->items->groupBy(fn($item) => $item->product->store_id ?? 'winky');

        $storeGroups = [];
        foreach ($grouped as $storeId => $items) {
            $weight = 0;
            foreach ($items as $item) {
                $w = $item->product ? $item->product->weight : 500;
                $weight += $w * $item->quantity;
            }
            $storeName = $items->first()->product->store->name ?? 'WINKY Official';
            $storeSlug = $items->first()->product->store->slug ?? 'winky-official';
            $storeGroups[$storeId] = [
                'name' => $storeName,
                'slug' => $storeSlug,
                'items' => $items,
                'weight' => $weight,
                'subtotal' => $items->sum(fn($item) => ($item->variant ? $item->variant->price : $item->product->getEffectivePrice()) * $item->quantity),
            ];
        }

        $totalWeight = collect($storeGroups)->sum('weight');
        $shippingRates = $this->shippingService->getRatesForOrder($totalWeight);

        return view('checkout.index', compact('cart', 'defaultAddress', 'addresses', 'totalWeight', 'shippingRates', 'storeGroups'));
    }

    public function getShippingRates(Request $request)
    {
        $user = Auth::user();
        $cart = $user->getOrCreateCart()->load('items.product', 'items.variant');

        if ($cart->items->isEmpty()) {
            return response()->json(['rates' => []]);
        }

        $totalWeight = 0;
        foreach ($cart->items as $item) {
            $weight = $item->product ? $item->product->weight : 500;
            $totalWeight += $weight * $item->quantity;
        }

        $rates = $this->shippingService->getRatesForOrder($totalWeight);

        return response()->json([
            'total_weight' => $totalWeight,
            'rates' => $rates,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = $user->getOrCreateCart()->load('items.product.store', 'items.variant');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Keranjang kamu kosong.');
        }

        $validated = $request->validate([
            'address_id' => 'nullable|exists:addresses,id',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_courier' => 'required|string|max:50',
            'shipping_service' => 'required|string|max:50',
            'notes' => 'nullable|string|max:500',
            'voucher_code' => 'nullable|string|max:50',
        ]);

        if ($validated['address_id']) {
            $address = $user->addresses()->where('id', $validated['address_id'])->first();
            if (!$address) {
                return back()->withErrors(['address_id' => 'Alamat tidak valid.']);
            }
        }

        $grouped = $cart->items->groupBy(fn($item) => $item->product->store_id ?? 'winky');

        $allValid = true;
        $errorMessages = [];

        foreach ($cart->items as $item) {
            $product = $item->product;
            if (!$product || !$product->is_active) {
                $allValid = false;
                $errorMessages[] = 'Produk "' . ($item->product->name ?? 'Tidak diketahui') . '" tidak tersedia lagi.';
                continue;
            }
            $availableStock = $product->stock - $product->reserved_stock;
            if ($item->quantity > $availableStock) {
                $allValid = false;
                $errorMessages[] = 'Stok "' . $product->name . '" tidak mencukupi. Stok tersisa: ' . $availableStock;
            }
        }

        if (!$allValid) {
            return back()->withErrors(['cart' => implode(' ', $errorMessages)]);
        }

        $totalSubtotal = 0;
        $totalWeight = 0;
        $allOrderItems = [];

        foreach ($grouped as $storeId => $items) {
            $storeSubtotal = 0;
            $storeWeight = 0;
            $storeItems = [];

            foreach ($items as $item) {
                $product = $item->product;
                $variant = $item->variant;
                $price = $variant ? $variant->price : $product->getEffectivePrice();
                $itemSubtotal = $price * $item->quantity;
                $storeSubtotal += $itemSubtotal;
                $storeWeight += ($product->weight ?? 500) * $item->quantity;

                $storeItems[] = [
                    'store_id' => $storeId,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name' => $product->name,
                    'variant_name' => $variant?->name,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $totalSubtotal += $storeSubtotal;
            $totalWeight += $storeWeight;
            $allOrderItems[$storeId] = $storeItems;
        }

        $shippingCost = $this->shippingService->calculateShippingCost(
            $totalWeight,
            $validated['shipping_courier'],
            $validated['shipping_service']
        );

        if ($shippingCost <= 0) {
            return back()->withErrors(['shipping_courier' => 'Metode pengiriman tidak valid. Silakan pilih ulang.']);
        }

        $estimatedDays = $this->shippingService->getEstimatedDays(
            $validated['shipping_courier'],
            $validated['shipping_service']
        );

        $voucherDiscount = 0;
        $voucherCode = null;
        $voucher = null;

        if (!empty($validated['voucher_code'])) {
            $voucher = Voucher::where('code', strtoupper($validated['voucher_code']))->first();

            if (!$voucher || !$voucher->isValid()) {
                return back()->withErrors(['voucher_code' => 'Kode voucher tidak valid atau sudah kadaluarsa.']);
            }

            if ($totalSubtotal < $voucher->minimum_order) {
                return back()->withErrors([
                    'voucher_code' => 'Minimal pembelian Rp ' . number_format($voucher->minimum_order, 0, ',', '.') . ' untuk menggunakan voucher ini.',
                ]);
            }

            $voucherDiscount = $voucher->calculateDiscount($totalSubtotal);
            $voucherCode = $voucher->code;
        }

        $grandTotal = $totalSubtotal + $shippingCost - $voucherDiscount;

        $primaryStoreId = null;
        $primaryStoreEarning = 0;
        $primaryCommission = 0;
        $primarySubtotal = 0;

        foreach ($grouped as $storeId => $items) {
            if ($storeId !== 'winky') {
                $storeSubtotal = collect($allOrderItems[$storeId])->sum('subtotal');
                $commission = MarketplaceSetting::calculateCommission($storeSubtotal);
                $earning = $storeSubtotal - $commission;

                if (!$primaryStoreId || $earning > $primaryStoreEarning) {
                    $primaryStoreId = $storeId;
                    $primaryStoreEarning = $earning;
                    $primaryCommission = $commission;
                    $primarySubtotal = $storeSubtotal;
                }
            }
        }

        try {
            DB::beginTransaction();

            $lockIds = $cart->items->pluck('product_id')->unique()->toArray();
            \App\Models\Product::whereIn('id', $lockIds)->lockForUpdate()->get();

            $mainOrder = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'store_id' => $primaryStoreId,
                'address_id' => $validated['address_id'] ?? null,
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'shipping_address' => $validated['shipping_address'],
                'subtotal' => $totalSubtotal,
                'shipping_cost' => $shippingCost,
                'shipping_courier' => $validated['shipping_courier'],
                'shipping_service' => $validated['shipping_service'],
                'shipping_estimated_days' => $estimatedDays,
                'discount' => 0,
                'voucher_code' => $voucherCode,
                'voucher_discount' => $voucherDiscount,
                'total' => $grandTotal,
                'status' => 'pending',
                'seller_status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'seller_subtotal' => $primarySubtotal,
                'commission_amount' => $primaryCommission,
                'seller_earning' => $primaryStoreEarning,
            ]);

            foreach ($allOrderItems as $storeId => $storeItems) {
                foreach ($storeItems as $orderItemData) {
                    $mainOrder->items()->create($orderItemData);

                    if ($orderItemData['product_variant_id']) {
                        $variant = \App\Models\ProductVariant::find($orderItemData['product_variant_id']);
                        if ($variant) {
                            $variant->decrement('stock', $orderItemData['quantity']);
                        }
                    } else {
                        $product = \App\Models\Product::find($orderItemData['product_id']);
                        if ($product) {
                            $this->inventoryService->removeStock(
                                $product,
                                $orderItemData['quantity'],
                                'sale',
                                "Pesanan #{$mainOrder->order_number}",
                                $user->id,
                                'App\\Models\\Order',
                                $mainOrder->id,
                                $storeId !== 'winky' ? $storeId : null
                            );
                        }
                    }
                }
            }

            if ($voucherCode && $voucher) {
                $voucher->increment('used_count');
            }

            $cart->items()->delete();

            \App\Models\InAppNotification::create([
                'user_id' => $user->id,
                'type' => 'order_created',
                'data' => [
                    'title' => 'Pesanan Dibuat',
                    'message' => "Pesanan #{$mainOrder->order_number} berhasil dibuat.",
                    'url' => route('orders.show', $mainOrder->order_number),
                ],
            ]);

            DB::commit();

            return redirect()->route('orders.show', $mainOrder->order_number)
                ->with('success', 'Pesanan berhasil dibuat! Nomor pesanan: ' . $mainOrder->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['cart' => 'Gagal membuat pesanan. Silakan coba lagi.']);
        }
    }
}
