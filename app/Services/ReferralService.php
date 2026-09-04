<?php

namespace App\Services;

use App\Models\ReferralCode;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralService
{
    public function generateCode(int $userId): ReferralCode
    {
        $code = strtoupper(uniqid('REF-')) . substr(microtime(true), 2, 5);

        return DB::transaction(function () use ($userId, $code) {
            // Check if code already exists
            $existing = ReferralCode::where('code', $code)->first();
            if ($existing) {
                return $this->generateCode($userId);
            }

            $codeObj = ReferralCode::create([
                'user_id' => $userId,
                'code' => $code,
            ]);

            // Create referral record
            Referral::create([
                'referrer_id' => $userId,
                'referred_user_id' => $userId,
                'code' => $code,
                'status' => 'pending',
            ]);

            return $codeObj;
        });
    }

    public function isSelfReferral(int $referrerId, int $referredId): bool
    {
        return $referrerId === $referredId;
    }

    public function validateReferral(int $referrerId, int $referredUserId, string $code): bool
    {
        return DB::transaction(function () use ($referrerId, $referredUserId, $code) {
            // Check self-referral
            if ($referrerId === $referredUserId) {
                return false;
            }

            // Check if already referred
            $existing = Referral::where('referrer_id', $referrerId)
                ->where('referred_user_id', $referredUserId)
                ->first();

            if ($existing) {
                return false; // Already referred once
            }

            // Check code exists and is active
            $referralCode = ReferralCode::where('code', $code)->where('is_active', true)->first();
            if (!$referralCode || $referralCode->user_id !== $referrerId) {
                return false;
            }

            // Create referral
            $referral = Referral::create([
                'referrer_id' => $referrerId,
                'referred_user_id' => $referredUserId,
                'code' => $code,
                'status' => 'qualified',
            ]);

            return true;
        });
    }

    public function awardReward(int $referrerId): bool
    {
        return DB::transaction(function () use ($referrerId) {
            $referral = Referral::where('referrer_id', $referrerId)
                ->where('status', 'qualified')
                ->whereDoesntHave('referredUser', function ($q) {
                    $q->where('referrals.referrer_id', $referrerId);
                })
                ->first();

            if (!$referral || $referral->status === 'rewarded') {
                return false;
            }

            // Check for duplicate reward
            $alreadyRewarded = Referral::where('referrer_id', $referrerId)
                ->where('status', 'rewarded')
                ->exists();

            if ($alreadyRewarded) {
                return false;
            }

            $referral->status = 'rewarded';
            $referral->save();

            return true;
        });
    }

    public function getReferralStats(int $userId): array
    {
        $referralCode = ReferralCode::where('user_id', $userId)->first();

        $totalReferrals = Referral::where('referrer_id', $userId)->count();
        $pending = Referral::where('referrer_id', $userId)->where('status', 'pending')->count();
        $qualified = Referral::where('referrer_id', $userId)->where('status', 'qualified')->count();
        $rewarded = Referral::where('referrer_id', $userId)->where('status', 'rewarded')->count();

        return [
            'code' => $referralCode?->code ?? '',
            'total_referrals' => $totalReferrals,
            'pending' => $pending,
            'qualified' => $qualified,
            'rewarded' => $rewarded,
        ];
    }
}