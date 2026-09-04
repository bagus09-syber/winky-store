<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function addStock(Product $product, int $quantity, ?string $type = 'initial', ?string $notes = null, ?int $userId = null, ?string $referenceType = null, ?int $referenceId = null, ?int $storeId = null): bool
    {
        if ($quantity <= 0) return false;

        try {
            DB::beginTransaction();

            $before = $product->stock;
            $product->increment('stock', $quantity);

            InventoryMovement::create([
                'product_id' => $product->id,
                'store_id' => $storeId,
                'type' => $type,
                'quantity_before' => $before,
                'quantity_change' => $quantity,
                'quantity_after' => $before + $quantity,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'notes' => $notes,
                'user_id' => $userId,
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function removeStock(Product $product, int $quantity, ?string $type = 'sale', ?string $notes = null, ?int $userId = null, ?string $referenceType = null, ?int $referenceId = null, ?int $storeId = null): bool
    {
        if ($quantity <= 0) return false;

        try {
            DB::beginTransaction();

            if ($product->stock < $quantity) {
                DB::rollBack();
                return false;
            }

            $before = $product->stock;
            $product->decrement('stock', $quantity);

            InventoryMovement::create([
                'product_id' => $product->id,
                'store_id' => $storeId,
                'type' => $type,
                'quantity_before' => $before,
                'quantity_change' => -$quantity,
                'quantity_after' => $before - $quantity,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'notes' => $notes,
                'user_id' => $userId,
            ]);

            $this->checkLowStock($product);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function reserveStock(Product $product, int $quantity, ?int $referenceId = null, ?int $storeId = null): bool
    {
        if ($quantity <= 0) return false;

        try {
            DB::beginTransaction();

            $availableStock = $product->stock - $product->reserved_stock;
            if ($availableStock < $quantity) {
                DB::rollBack();
                return false;
            }

            $before = $product->reserved_stock;
            $product->increment('reserved_stock', $quantity);

            InventoryMovement::create([
                'product_id' => $product->id,
                'store_id' => $storeId,
                'type' => 'reserved',
                'quantity_before' => $before,
                'quantity_change' => $quantity,
                'quantity_after' => $before + $quantity,
                'reference_type' => 'App\\Models\\Order',
                'reference_id' => $referenceId,
                'notes' => "Reserved {$quantity} units",
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function releaseStock(Product $product, int $quantity, ?int $referenceId = null, ?int $storeId = null): bool
    {
        if ($quantity <= 0) return false;

        try {
            DB::beginTransaction();

            $before = $product->reserved_stock;
            $newReserved = max(0, $before - $quantity);
            $product->update(['reserved_stock' => $newReserved]);

            InventoryMovement::create([
                'product_id' => $product->id,
                'store_id' => $storeId,
                'type' => 'released',
                'quantity_before' => $before,
                'quantity_change' => -$quantity,
                'quantity_after' => $newReserved,
                'reference_type' => 'App\\Models\\Order',
                'reference_id' => $referenceId,
                'notes' => "Released {$quantity} units",
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function adjustStock(Product $product, int $newQuantity, ?string $notes = null, ?int $userId = null): bool
    {
        try {
            DB::beginTransaction();

            $before = $product->stock;
            $change = $newQuantity - $before;
            $product->update(['stock' => $newQuantity]);

            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'adjustment',
                'quantity_before' => $before,
                'quantity_change' => $change,
                'quantity_after' => $newQuantity,
                'notes' => $notes ?? "Manual adjustment to {$newQuantity}",
                'user_id' => $userId,
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function finalizeSale(Product $product, int $quantity, ?int $orderId = null, ?int $storeId = null): bool
    {
        return $this->removeStock($product, $quantity, 'sale', "Order #{$orderId} completed", null, 'App\\Models\\Order', $orderId, $storeId);
    }

    protected function checkLowStock(Product $product): void
    {
        $threshold = $product->low_stock_threshold ?? config('marketplace.low_stock_threshold', 5);

        if ($product->stock <= $threshold && $product->stock > 0 && $product->store_id) {
            \App\Models\InAppNotification::create([
                'user_id' => $product->store->user_id,
                'type' => 'stock_low',
                'data' => [
                    'title' => 'Stok Rendah',
                    'message' => "Produk '{$product->name}' stok tersisa {$product->stock}.",
                    'url' => route('seller.products.index'),
                ],
            ]);
        }

        if ($product->stock == 0 && $product->store_id) {
            \App\Models\InAppNotification::create([
                'user_id' => $product->store->user_id,
                'type' => 'stock_out',
                'data' => [
                    'title' => 'Stok Habis',
                    'message' => "Produk '{$product->name}' stok habis.",
                    'url' => route('seller.products.index'),
                ],
            ]);
        }
    }
}
