<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SellerTransaction;
use App\Models\SellerWallet;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class SellerSettlementService
{
    public function moveToEscrow(Order $order): bool
    {
        if (!$order->store_id || $order->seller_earning <= 0) return false;

        $wallet = Store::getOrCreateWallet(Store::find($order->store_id));
        if (!$wallet) return false;

        try {
            DB::beginTransaction();

            $wallet->addToEscrow((float) $order->seller_earning);

            SellerTransaction::create([
                'store_id' => $order->store_id,
                'order_id' => $order->id,
                'type' => 'escrow',
                'amount' => $order->seller_earning,
                'balance_after' => $wallet->fresh()->escrow_balance,
                'description' => "Pesanan #{$order->order_number} masuk escrow",
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function settleOrder(Order $order): bool
    {
        if (!$order->store_id || $order->seller_earning <= 0) return false;

        $wallet = Store::getOrCreateWallet(Store::find($order->store_id));
        if (!$wallet) return false;

        try {
            DB::beginTransaction();

            $wallet->addPendingBalance((float) $order->seller_earning);

            SellerTransaction::create([
                'store_id' => $order->store_id,
                'order_id' => $order->id,
                'type' => 'sale',
                'amount' => $order->seller_earning,
                'balance_after' => $wallet->fresh()->pending_balance,
                'description' => "Pesanan #{$order->order_number} dibayar - menunggu penyelesaian",
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function completeOrder(Order $order): bool
    {
        if (!$order->store_id || $order->seller_earning <= 0) return false;

        $wallet = Store::getOrCreateWallet(Store::find($order->store_id));
        if (!$wallet) return false;

        try {
            DB::beginTransaction();

            $wallet->movePendingToAvailable((float) $order->seller_earning);

            $store = Store::find($order->store_id);
            $store->increment('total_sales', $order->items->sum('quantity'));

            SellerTransaction::create([
                'store_id' => $order->store_id,
                'order_id' => $order->id,
                'type' => 'sale',
                'amount' => $order->seller_earning,
                'balance_after' => $wallet->fresh()->available_balance,
                'description' => "Pesanan #{$order->order_number} selesai - dana tersedia",
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function refundOrder(Order $order, float $amount, string $reason = 'Refund'): bool
    {
        if (!$order->store_id) return false;

        $wallet = Store::getOrCreateWallet(Store::find($order->store_id));
        if (!$wallet) return false;

        try {
            DB::beginTransaction();

            if ($wallet->escrow_balance >= $amount) {
                $wallet->refundEscrow($amount);
                $type = 'escrow_refund';
            } elseif ($wallet->pending_balance >= $amount) {
                $wallet->refundPending($amount);
                $type = 'refund';
            } else {
                $wallet->decrement('available_balance', $amount);
                $type = 'refund';
            }

            SellerTransaction::create([
                'store_id' => $order->store_id,
                'order_id' => $order->id,
                'type' => $type,
                'amount' => -$amount,
                'balance_after' => $wallet->fresh()->available_balance,
                'description' => "Refund pesanan #{$order->order_number}: {$reason}",
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
