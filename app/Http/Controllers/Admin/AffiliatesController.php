<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Promotion;
use App\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AffiliatesController extends AdminController
{
    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function index()
    {
        $this->authorize('view', 'affiliates');

        $affiliates = Affiliate::with(['user', 'affiliateClickCounts', 'affiliateConversions'])
            ->latest('created_at')
            ->paginate(20);

        return view('admin.affiliates', compact('affiliates'));
    }

    public function approve(int $affiliateId)
    {
        $this->authorize('update', 'affiliates');

        $affiliate = Affiliate::findOrFail($affiliateId);
        $affiliate->status = 'active';
        $affiliate->save();

        return back()->with('success', 'Affiliate approved successfully.');
    }

    public function suspend(int $affiliateId)
    {
        $this->authorize('update', 'affiliates');

        $affiliate = Affiliate::findOrFail($affiliateId);
        $affiliate->status = 'suspended';
        $affiliate->save();

        return back()->with('success', 'Affiliate suspended.');
    }

    public function setRate(int $affiliateId, Request $request)
    {
        $this->authorize('update', 'affiliates');

        $request->validate([
            'commission_rate' => 'required|numeric|between:0,0.50',
        ]);

        $affiliate = Affiliate::findOrFail($affiliateId);
        $affiliate->commission_rate = $request->commission_rate;
        $affiliate->save();

        return back()->with('success', 'Commission rate updated.');
    }

    public function reviewEarnings(int $affiliateId)
    {
        $this->authorize('view', 'affiliates');

        $affiliate = Affiliate::findOrFail($affiliateId);
        $stats = $this->promotionService->getAffiliateStats($affiliate->user->id);

        return view('admin.affiliate-earnings', compact('affiliate', 'stats'));
    }
}