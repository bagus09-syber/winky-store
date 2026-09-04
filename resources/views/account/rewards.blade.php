@extends('layouts.app')

@section('content')
<div class="max-w-9xl mx-auto p-4">
    <!-- Mobile Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-white">My Loyalty Account</h2>
                <p class="text-sm text-text-secondary">Dapat poin setiap transaksi</p>
            </div>
            <button id="closeRewards" class="text-text-secondary hover:text-cyan transition-colors" style="padding: 4px 8px; font-size: 14px;">Tutup</button>
        </div>
    </div>
    
    <div class="bg-card rounded-xl p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            <div>
                <h3 class="text-base font-medium text-text-secondary uppercase mb-3">Saldo Poin</h3>
                <p class="text-4xl font-bold text-cyan">{{ $account['points_balance'] }}</p>
                <p class="text-sm text-text-secondary">Lifetime: {{ $account['lifetime_points'] }}</p>
            </div>
            <div>
                <h3 class="text-base font-medium text-text-secondary uppercase mb-3">Level Keanggotaan</h3>
                <p class="text-xl font-medium {{ match($account['membership_level'], 'PLATINUM') ? 'text-yellow-400' : match($account['membership_level'], 'GOLD') ? 'text-green-400' : match($account['membership_level'], 'SILVER') ? 'text-blue-400' : 'text-gray-400' }}">
                    {{ match($account['membership_level'], 'BRONZE', 'SILVER', 'GOLD', 'PLATINUM') }}
                </p>
                <p class="text-sm text-text-secondary">Progress ke level berikutnya</p>
            </div>
        </div>
        
        <div class="mt-6">
            <div class="bg-elevated rounded-full h-8 -mt-4">
                <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-cyan-500 to-blue-600" style="width: {{ round(number: ($account['points_balance'] / 5000) * 100, precision: 2) }}%;"></div>
            </div>
            <p class="text-xs text-center text-text-secondary mt-1">{{ $account['points_balance'] }}/5000 poin</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 gap-6">
        <!-- Available Rewards -->
        <div class="bg-card rounded-xl p-6">
            <h2 class="text-lg font-bold text-white mb-4">Hadiah Tersedia</h2>
            <div class="space-y-3">
                @foreach ($rewards as $reward)
                <div class="flex items-center justify-between border rounded p-3 hover:border-cyan-500 transition-colors">
                    <div class="flex items-center space-x-3">
                        <span class="text-white">{{ $reward['name'] }}</span>
                        <span class="text-text-secondary text-sm">{{ $reward['type'] }}</span>
                    </div>
                    <div class="text-right">
                        @if($reward['type'] === 'POINTS')
                        <span class="text-cyan">{{ $reward['cost_points'] }} poin</span>
                        @else
                        <span class="text-green-400">{{ $reward['discount_value'] }}% Diskon</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Transaction History -->
        <div class="bg-card rounded-xl p-6">
            <h2 class="text-lg font-bold text-white mb-4">Riwayat Transaksi</h2>
            <div class="space-y-2 max-h-40 overflow-y-auto text-sm">
                @foreach ($recentTransactions as $transaction)
                <div class="flex items-center justify-between px-2 py-1 border-b border-gray-700 last:border-0">
                    <div class="flex items-center space-x-2">
                        <span class="text-cyan ucfirst">{{ ucfirst($transaction->type) }}</span>
                        <span class="text-white">{{ $transaction->points }}</span>
                    </div>
                    <span class="text-text-secondary">{{ $transaction->description }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    // Close modal on backdrop click or escape key
    document.getElementById('closeRewards')?.addEventListener('click', () => {
        window.close() || window.history.back();
    });
    
    // Handle escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            window.close() || window.history.back();
        }
    });
</script>