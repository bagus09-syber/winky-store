<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Services\AffiliateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AffiliateController extends Controller
{
    protected $affiliateService;

    public function __construct(AffiliateService $affiliateService)
    {
        $this->affiliateService = $affiliateService;
    }

    public function index()
    {
        $user = Auth::user();
        $affiliate = $this->affiliateService->account($user->id);
        $stats = $this->affiliateService->getAffiliateStats($user->id);

        $links = $affiliate->affiliateLinks()->with('product')->get();

        return view('affiliate.dashboard', compact('affiliate', 'stats', 'links'));
    }

    public function links()
    {
        $user = Auth::user();
        $affiliate = $this->affiliateService->account($user->id);

        $links = $affiliate->affiliateLinks()
            ->with('product')
            ->get();

        return view('affiliate.links', compact('links'));
    }

    public function createLink(Request $request)
    {
        $user = Auth::user();
        $affiliate = $this->affiliateService->account($user->id);

        $link = $this->affiliateService->generateLink($affiliate->id, $request->product_id);

        return back()->with('success', 'Affiliate link created: ' . $link->code);
    }

    public function trackClick(Request $request, $linkId)
    {
        $link = AffiliateLink::findOrFail($linkId);
        $this->affiliateService->trackClick($link);

        return ['status' => 'tracked'];
    }

    public function conversionStats()
    {
        $user = Auth::user();
        $stats = $this->affiliateService->getAffiliateStats($user->id);

        return view('affiliate.conversion_stats', compact('stats'));
    }
}