<?php

namespace App\Services;

use App\Models\AbandonedCart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AbandonedCartService
{
    const ABANDONED_TIMEOUT = 30; // minutes configurable

    public function markAbandoned(int $userId, string $cartId): AbandonedCart
    {
        return DB::transaction(function () use ($userId, $cartId) {
            $abandonedCart = AbandonedCart::firstOrCreate(
                ['user_id' => $userId, 'cart_id' => $cartId],
                [
                    'status' => 'active',
                    'last_activity_at' => now(),
                ]
            );

            // Check if cart has been inactive for the timeout period
            $minutesInactive = now()->diffInMinutes($abandonedCart->last_activity_at, true);

            if ($minutesInactive >= self::ABANDONED_TIMEOUT && $abandonedCart->status !== 'abandoned') {
                $abandonedCart->status = 'abandoned';
                $abandonedCart->save();
            }

            return $abandonedCart;
        });
    }

    public function recoverCart(int $userId, string $cartId): ?AbandonedCart
    {
        return DB::transaction(function () use ($userId, $cartId) {
            $abandonedCart = AbandonedCart::where('user_id', $userId)
                ->where('cart_id', $cartId)
                ->where('status', 'abandoned')
                ->first();

            if (!$abandonedCart) {
                return null;
            }

            $abandonedCart->status = 'recovered';
            $abandonedCart->recovered_at = now();
            $abandonedCart->save();

            return $abandonedCart;
        });
    }

    public function isAbandoned(int $userId, string $cartId): bool
    {
        $abandonedCart = AbandonedCart::where('user_id', $userId)
            ->where('cart_id', $cartId)
            ->where('status', 'abandoned')
            ->first();

        if (!$abandonedCart) {
            return false;
        }

        $minutesInactive = now()->diffInMinutes($abandonedCart->last_activity_at, true);
        return $minutesInactive >= self::ABANDONED_TIMEOUT;
    }

    public function getAbandonedStats(): array
    {
        $total = AbandonedCart::count();
        $abandoned = AbandonedCart::where('status', 'abandoned')->count();
        $recovered = AbandonedCart::where('status', 'recovered')->count();

        // Calculate recovery rate
        $recoveryRate = $total > 0 ? round(($recovered / $total) * 100, 2) : 0;

        // Estimate lost revenue (simplified - would need order items)
        $lostRevenue = $abandoned * 50; // avg estimate

        return [
            'total' => $total,
            'abandoned' => $abandoned,
            'recovered' => $recovered,
            'recovery_rate' => $recoveryRate,
            'lost_revenue_estimate' => $lostRevenue,
        ];
    }
}