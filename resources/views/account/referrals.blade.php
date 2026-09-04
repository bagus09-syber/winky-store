@extends('layouts.app')

@section('content')
<div class="max-w-9xl mx-auto p-4">
    <!-- Mobile Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-white">Referral Saya</h2>
                <p class="text-sm text-text-secondary">Ajak teman, dapat poin</p>
            </div>
            <button id="closeReferrals" class="text-text-secondary hover:text-cyan transition-colors" style="padding: 4px 8px; font-size: 14px;">Tutup</button>
        </div>
    </div>
    
    <div class="bg-card rounded-xl p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            <div>
                <p class="text-sm text-text-secondary uppercase mb-2">Kode Referral</p>
                <div class="bg-elevated rounded-lg p-4 mb-4">
                    <input type="text" id="referralCode" readonly class="w-full bg-card rounded px-4 py-2 text-white focus:outline-none focus:border-cyan-500" value="{{ $referralCode ?? 'Tidak ada' }}">
                </div>
                <p class="text-xs text-text-secondary">Bagikan kode ini ke teman Anda</p>
            </div>
            
            <div>
                <p class="text-sm text-text-secondary uppercase mb-2">Link Referral</p>
                <input type="text" id="referralLink" readonly class="w-full bg-card rounded px-4 py-2 text-white focus:outline-none focus:border-cyan-500" value="{{ $referralLink }}">
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 gap-6">
        <!-- Referral Stats -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-card rounded-xl p-5 text-center">
                <p class="text-3xl font-bold text-cyan">{{ $referralInfo['total_referrals'] }}</p>
                <p class="text-xs text-text-secondary uppercase">Total Referral</p>
            </div>
            <div class="bg-card rounded-xl p-5 text-center">
                <p class="text-3xl font-bold text-cyan">{{ $referralInfo['rewarded'] }}</p>
                <p class="text-xs text-text-secondary uppercase">Reward</p>
            </div>
        </div>
        
        <div class="bg-card rounded-xl p-5 text-center">
            <p class="text-3xl font-bold text-cyan">{{ $referralInfo['pending'] }}</p>
            <p class="text-xs text-text-secondary uppercase">Pending</p>
        </div>
        <div class="bg-card rounded-xl p-5 text-center">
            <p class="text-3xl font-bold text-cyan">{{ $referralInfo['qualified'] }}</p>
            <p class="text-xs text-text-secondary uppercase">Qualified</p>
        </div>
    </div>
    
    <!-- Referred Users -->
    <div class="bg-card rounded-xl p-6 mt-6">
        <h3 class="text-lg font-bold text-white mb-4">Referral Teman</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left text-white">Teman</th>
                        <th class="text-left text-white">Status</th>
                        <th class="text-left text-white">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($referredInfo as $referral)
                    <tr class="border-b border-gray-700 hover:bg-gray-900">
                        <td class="text-white">{{ $referral->referredUser->name }}</td>
                        <td class="text-text-secondary">{{ $referral->status }}</td>
                        <td class="text-xs text-text-secondary">{{ $referral->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('closeReferrals')?.addEventListener('click', () => {
        window.close() || window.history.back();
    });
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            window.close() || window.history.back();
        }
    });
</script>