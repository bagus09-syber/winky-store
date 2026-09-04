<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceSetting;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminMarketplaceController extends Controller
{
    public function index()
    {
        $settings = [
            'commission_type' => MarketplaceSetting::get('commission_type', 'percentage'),
            'commission_value' => MarketplaceSetting::get('commission_value', '5'),
            'min_withdrawal' => MarketplaceSetting::get('min_withdrawal', '50000'),
            'low_stock_threshold' => MarketplaceSetting::get('low_stock_threshold', '5'),
        ];

        return view('admin.marketplace.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|numeric|min:0',
            'min_withdrawal' => 'required|numeric|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
        ]);

        $old = [
            'commission_type' => MarketplaceSetting::get('commission_type'),
            'commission_value' => MarketplaceSetting::get('commission_value'),
            'min_withdrawal' => MarketplaceSetting::get('min_withdrawal'),
            'low_stock_threshold' => MarketplaceSetting::get('low_stock_threshold'),
        ];

        foreach ($validated as $key => $value) {
            if ($old[$key] != $value) {
                AuditLogService::logMarketplaceSetting($key, $old[$key], $value, $request);
            }
            MarketplaceSetting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan marketplace berhasil diperbarui.');
    }
}
