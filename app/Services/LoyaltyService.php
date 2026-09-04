<?php

namespace App\Services;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\Reward;
use App\Models\RewardRedemption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoyaltyService
{
    public function account(int $userId): LoyaltyAccount
    {
        return LoyaltyAccount::firstOrCreate(
            ['user_id' => $userId],
            [
                'points_balance' => 0,
                'lifetime_points' => 0,
                'membership_level' => 'BRONZE',
            ]
        );
    }

    public function canEarnPoints(int $userId, int $orderId): bool
    {
        $account = $this->account($userId);
        $alreadyEarned = LoyaltyTransaction::where('user_id', $userId)
            ->where('type', 'earn')
            ->where('reference_id', $orderId)
            ->exists();

        return !$alreadyEarned;
    }

    public function earnPoints(int $userId, int $points, string $type, string $description, $referenceType = null, $referenceId = null): LoyaltyTransaction
    {
        return DB::transaction(function () use ($userId, $points, $type, $description, $referenceType, $referenceId) {
            $account = $this->account($userId);
            $account->points_balance += $points;
            $account->lifetime_points += $points;
            $account->save();

            $transaction = LoyaltyTransaction::create([
                'user_id' => $userId,
                'type' => $type,
                'points' => $points,
                'balance_after' => $account->points_balance,
                'description' => $description,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);

            return $transaction;
        });
    }

    public function redeemPoints(int $userId, int $points, string $description, $referenceType = null, $referenceId = null): ?LoyaltyTransaction
    {
        return DB::transaction(function () use ($userId, $points, $description, $referenceType, $referenceId) {
            $account = $this->account($userId);

            if ($account->points_balance < $points) {
                return null;
            }

            $account->points_balance -= $points;
            $account->save();

            $transaction = LoyaltyTransaction::create([
                'user_id' => $userId,
                'type' => 'redeem',
                'points' => $points,
                'balance_after' => $account->points_balance,
                'description' => $description,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);

            return $transaction;
        });
    }

    public function refundPoints(int $userId, int $points, string $description, $referenceType = null, $referenceId = null): LoyaltyTransaction
    {
        return DB::transaction(function () use ($userId, $points, $description, $referenceType, $referenceId) {
            $account = $this->account($userId);
            $account->points_balance += $points;
            $account->save();

            $transaction = LoyaltyTransaction::create([
                'user_id' => $userId,
                'type' => 'refund',
                'points' => $points,
                'balance_after' => $account->points_balance,
                'description' => $description,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);

            return $transaction;
        });
    }

    public function getMembershipLevel(int $totalPoints): string
    {
        if ($totalPoints >= 10000) return 'PLATINUM';
        if ($totalPoints >= 5000) return 'GOLD';
        if ($totalPoints >= 2000) return 'SILVER';
        return 'BRONZE';
    }

    public function getPointsToNextLevel(int $userId): int
    {
        $account = $this->account($userId);
        $currentLevelPoints = match($account->membership_level) {
            'BRONZE' => 0,
            'SILVER' => 2000,
            'GOLD' => 5000,
            'PLATINUM' => 10000,
        };
        $nextLevelPoints = match($account->membership_level) {
            'BRONZE' => 2000,
            'SILVER' => 5000,
            'GOLD' => 10000,
            'PLATINUM' => 10000,
        };
        return max(0, $nextLevelPoints - $account->points_balance);
    }
}