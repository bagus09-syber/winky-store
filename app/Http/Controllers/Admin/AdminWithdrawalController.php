<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerTransaction;
use App\Models\Store;
use App\Models\Withdrawal;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with('store');

        if ($request->search) {
            $query->whereHas('store', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->latest()->paginate(10)->withQueryString();

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load('store.user');

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Hanya permintaan pending yang bisa disetujui.');
        }

        DB::beginTransaction();

        try {
            $withdrawal->update([
                'status' => 'approved',
                'admin_notes' => 'Disetujui oleh admin',
            ]);

            AuditLogService::logWithdrawal($withdrawal, 'approved', request());

            $wallet = $withdrawal->store->wallet;
            if ($wallet) {
                $wallet->deductAvailable((float) $withdrawal->amount);

                SellerTransaction::create([
                    'store_id' => $withdrawal->store_id,
                    'type' => 'withdrawal',
                    'amount' => -$withdrawal->amount,
                    'balance_after' => $wallet->fresh()->available_balance,
                    'description' => "Penarikan #{$withdrawal->id} disetujui",
                ]);
            }

            \App\Models\InAppNotification::create([
                'user_id' => $withdrawal->store->user_id,
                'type' => 'withdrawal_approved',
                'data' => [
                    'title' => 'Penarikan Disetujui',
                    'message' => 'Penarikan sebesar Rp ' . number_format($withdrawal->amount, 0, ',', '.') . ' telah disetujui.',
                    'url' => route('seller.wallet'),
                ],
            ]);

            DB::commit();

            return back()->with('success', 'Penarikan berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penarikan.');
        }
    }

    public function reject(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Hanya permintaan pending yang bisa ditolak.');
        }

        DB::beginTransaction();

        try {
            $wallet = $withdrawal->store->wallet;
            if ($wallet) {
                $wallet->increment('available_balance', $withdrawal->amount);
            }

            $withdrawal->update([
                'status' => 'rejected',
                'admin_notes' => 'Ditolak oleh admin',
            ]);

            AuditLogService::logWithdrawal($withdrawal, 'rejected', request());

            SellerTransaction::create([
                'store_id' => $withdrawal->store_id,
                'type' => 'adjustment',
                'amount' => $withdrawal->amount,
                'balance_after' => $wallet->fresh()->available_balance,
                'description' => "Penarikan #{$withdrawal->id} ditolak, saldo dikembalikan",
            ]);

            \App\Models\InAppNotification::create([
                'user_id' => $withdrawal->store->user_id,
                'type' => 'withdrawal_rejected',
                'data' => [
                    'title' => 'Penarikan Ditolak',
                    'message' => 'Penarikan sebesar Rp ' . number_format($withdrawal->amount, 0, ',', '.') . ' ditolak.',
                    'url' => route('seller.wallet'),
                ],
            ]);

            DB::commit();

            return back()->with('success', 'Penarikan ditolak dan saldo dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penolakan.');
        }
    }

    public function pay(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'approved') {
            return back()->with('error', 'Hanya penarikan approved yang bisa ditandai paid.');
        }

        $withdrawal->update([
            'status' => 'paid',
            'processed_at' => now(),
        ]);

        AuditLogService::logWithdrawal($withdrawal, 'paid', request());

        return back()->with('success', 'Penarikan ditandai sebagai sudah dibayar.');
    }
}
