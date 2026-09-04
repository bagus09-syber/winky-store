<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Models\AffiliateClick;
use App\Models\AffiliateConversion;
use App\Models\AffiliateTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AffiliateService
{
    public function account(int $userId): Affiliate
    {
        return Affiliate::firstOrCreate(
            ['user_id' => $userId],
            [
                'commission_rate' => 0.10,
                'total_clicks' => 0,
                'total_sales' => 0,
                'total_earnings' => 0,
            ]
        );
    }

    public function generateLink(int $affiliateId, ?int $productId = null): AffiliateLink
    {
        $code = strtoupper(uniqid('AFL-')) . substr(microtime(true), 2, 5);

        return AffiliateLink::create([
            'affiliate_id' => $affiliateId,
            'product_id' => $productId,
            'code' => $code,
        ]);
    }

    public function trackClick(AffiliateLink $link): AffiliateClick
    {
        return AffiliateClick::create([
            'affiliate_link_id' => $link->id,
        ]);
    }

    public function trackConversion(AffiliateLink $link, int $orderId, float $commission): AffiliateConversion
    {
        return AffiliateConversion::create([
            'affiliate_link_id' => $link->id,
            'order_id' => $orderId,
            'commission' => $commission,
            'status' => 'pending',
        ]);
    }

    public function confirmConversion(int $conversionId): bool
    {
        return DB::transaction(function () use ($conversionId) {
            $conversion = AffiliateConversion::findOrFail($conversionId);

            if ($conversion->status !== 'pending') {
                return false;
            }

            $conversion->status = 'confirmed';
            $conversion->confirmed_at = now();
            $conversion->save();

            // Update affiliate stats
            $affiliate = $conversion->affiliateLink->affiliate;
            $affiliate->total_sales += 1;
            $affiliate->total_earnings += $conversion->commission;
            $affiliate->save();

            // Create transaction
            AffiliateTransaction::create([
                'affiliate_id' => $affiliate->id,
                'amount' => $conversion->commission,
                'type' => 'earned',
                'reference_type' => 'affiliate_conversion',
                'reference_id' => $conversion->id,
            ]);

            return true;
        });
    }

    public function reverseConversion(int $conversionId): bool
    {
        return DB::transaction(function () use ($conversionId) {
            $conversion = AffiliateConversion::findOrFail($conversionId);

            if ($conversion->status === 'reversed') {
                return false;
            }

            $affiliate = $conversion->affiliateLink->affiliate;

            $conversion->status = 'reversed';
            $conversion->save();

            // Reverse affiliate stats
            $affiliate->total_sales -= 1;
            $affiliate->total_earnings -= $conversion->commission;
            $affiliate->save();

            // Create reversal transaction
            AffiliateTransaction::create([
                'affiliate_id' => $affiliate->id,
                'amount' => $conversion->commission,
                'type' => 'refund',
                'reference_type' => 'affiliate_conversion',
                'reference_id' => $conversion->id,
            ]);

            return true;
        });
    }

    public function getAffiliateStats(int $userId): array
    {
        $affiliate = Affiliate::where('user_id', $userId)->first();

        if (!$affiliate) {
            return [
                'clicks' => 0,
                'sales' => 0,
                'conversion_rate' => 0,
                'earnings' => 0,
                'commission_rate' => 0.10,
            ];
        }

        $clicks = $affiliate->total_clicks;
        $sales = $affiliate->total_sales;
        $conversionRate = $clicks > 0 ? round(($sales / $clicks) * 100, 2) : 0;
        $earnings = $affiliate->total_earnings;

        return [
            'clicks' => $clicks,
            'sales' => $sales,
            'conversion_rate' => $conversionRate,
            'earnings' => $earnings,
            'commission_rate' => $affiliate->commission_rate,
        ];
    }
}