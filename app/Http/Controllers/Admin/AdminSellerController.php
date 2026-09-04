<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminSellerController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::with('user');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $sellers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.sellers.index', compact('sellers'));
    }

    public function show(Store $seller)
    {
        $seller->load('user', 'wallet');
        $totalProducts = $seller->products()->count();
        $activeProducts = $seller->products()->where('is_active', true)->count();
        $totalOrders = $seller->orders()->count();
        $totalRevenue = $seller->orders()->where('seller_status', 'completed')->sum('seller_earning');
        $recentOrders = $seller->orders()->with('user')->latest()->take(10)->get();

        return view('admin.sellers.show', compact(
            'seller', 'totalProducts', 'activeProducts', 'totalOrders', 'totalRevenue', 'recentOrders'
        ));
    }

    public function approve(Store $seller)
    {
        $old = ['status' => $seller->status];
        $seller->update(['status' => 'active']);

        AuditLogService::logSellerApproval($seller, 'approved', request());

        \App\Models\InAppNotification::create([
            'user_id' => $seller->user_id,
            'type' => 'store_approved',
            'data' => [
                'title' => 'Toko Disetujui',
                'message' => "Toko '{$seller->name}' telah disetujui dan aktif.",
                'url' => route('seller.dashboard'),
            ],
        ]);

        return back()->with('success', "Toko '{$seller->name}' berhasil disetujui.");
    }

    public function reject(Store $seller)
    {
        $seller->update(['status' => 'rejected']);

        AuditLogService::logSellerApproval($seller, 'rejected', request());

        \App\Models\InAppNotification::create([
            'user_id' => $seller->user_id,
            'type' => 'store_rejected',
            'data' => [
                'title' => 'Toko Ditolak',
                'message' => "Toko '{$seller->name}' telah ditolak.",
                'url' => route('seller.dashboard'),
            ],
        ]);

        return back()->with('success', "Toko '{$seller->name}' ditolak.");
    }

    public function suspend(Store $seller)
    {
        $seller->update(['status' => 'suspended']);

        AuditLogService::logSellerApproval($seller, 'suspended', request());

        \App\Models\InAppNotification::create([
            'user_id' => $seller->user_id,
            'type' => 'store_suspended',
            'data' => [
                'title' => 'Toko Disuspend',
                'message' => "Toko '{$seller->name}' telah disuspend.",
                'url' => route('seller.dashboard'),
            ],
        ]);

        return back()->with('success', "Toko '{$seller->name}' berhasil disuspend.");
    }

    public function toggleVerification(Store $seller)
    {
        $old = ['is_verified' => $seller->is_verified];
        $seller->update(['is_verified' => !$seller->is_verified]);

        AuditLogService::logModelUpdate($seller, ['is_verified' => $seller->is_verified], request());

        return back()->with('success', 'Status verifikasi berhasil diubah.');
    }
}
