<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceSetting;
use App\Models\SellerTransaction;
use App\Models\Store;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerWalletController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        $wallet = Store::getOrCreateWallet($store);
        $transactions = $store->transactions()->latest()->paginate(15);

        return view('seller.wallet.index', compact('store', 'wallet', 'transactions'));
    }

    public function requestWithdrawal(Request $request)
    {
        $store = Auth::user()->store;
        $wallet = Store::getOrCreateWallet($store);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_name' => 'required|string|max:100',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
        ]);

        $minWithdrawal = MarketplaceSetting::getMinWithdrawal();

        if ($validated['amount'] < $minWithdrawal) {
            return back()->withErrors(['amount' => 'Minimal penarikan Rp ' . number_format($minWithdrawal, 0, ',', '.')]);
        }

        if (!$wallet->hasSufficientBalance($validated['amount'])) {
            return back()->withErrors(['amount' => 'Saldo tidak mencukupi. Saldo tersedia: Rp ' . number_format($wallet->available_balance, 0, ',', '.')]);
        }

        $recentWithdrawal = $store->withdrawals()
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subHours(24))
            ->exists();

        if ($recentWithdrawal) {
            return back()->withErrors(['amount' => 'Anda sudah memiliki permintaan penarikan yang pending.']);
        }

        DB::beginTransaction();

        try {
            $wallet->decrement('available_balance', $validated['amount']);

            $withdrawal = Withdrawal::create([
                'store_id' => $store->id,
                'amount' => $validated['amount'],
                'bank_name' => $validated['bank_name'],
                'account_name' => $validated['account_name'],
                'account_number' => $validated['account_number'],
                'status' => 'pending',
            ]);

            $wallet->refresh();

            SellerTransaction::create([
                'store_id' => $store->id,
                'type' => 'withdrawal',
                'amount' => -$validated['amount'],
                'balance_after' => $wallet->available_balance,
                'description' => "Penarikan dana ke {$validated['bank_name']}",
            ]);

            DB::commit();

            return redirect()->route('seller.wallet')
                ->with('success', 'Permintaan penarikan berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penarikan.');
        }
    }
}
