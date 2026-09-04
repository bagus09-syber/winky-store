@extends('layouts.seller')
@section('title', 'Wallet - WINKY STORE')
@section('page-title', 'Wallet')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="stat-card">
        <span class="text-white/50 text-sm">Saldo Tersedia</span>
        <p class="text-3xl font-bold text-white mt-2">Rp {{ number_format($wallet->available_balance, 0, ',', '.') }}</p>
        <p class="text-white/40 text-xs mt-1">Dapat ditarik</p>
    </div>
    <div class="stat-card">
        <span class="text-white/50 text-sm">Saldo Pending</span>
        <p class="text-3xl font-bold text-white mt-2">Rp {{ number_format($wallet->pending_balance, 0, ',', '.') }}</p>
        <p class="text-white/40 text-xs mt-1">Menunggu pesanan selesai</p>
    </div>
    <div class="stat-card">
        <span class="text-white/50 text-sm">Total Pendapatan</span>
        <p class="text-3xl font-bold text-[var(--cyan)] mt-2">Rp {{ number_format($wallet->total_earned, 0, ',', '.') }}</p>
        <p class="text-white/40 text-xs mt-1">Total kotor</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="stat-card">
        <h3 class="text-white font-semibold mb-4">Penarikan Dana</h3>
        <form action="{{ route('seller.wallet.withdraw') }}" method="POST" onsubmit="return confirm('Yakin ingin menarik dana?')">
            @csrf
            <div class="space-y-4">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Jumlah (Rp) *</label>
                    <input type="number" name="amount" min="1" max="{{ $wallet->available_balance }}" required class="form-input" placeholder="Masukkan jumlah">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Bank *</label>
                    <select name="bank_name" required class="form-input form-select">
                        <option value="">Pilih Bank</option>
                        <option value="BCA">BCA</option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="BRI">BRI</option>
                        <option value="BNI">BNI</option>
                        <option value="CIMB Niaga">CIMB Niaga</option>
                        <option value="Danamon">Danamon</option>
                        <option value="Permata">Permata</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Nama Pemilik Rekening *</label>
                    <input type="text" name="account_name" required class="form-input" placeholder="Nama sesuai rekening">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Nomor Rekening *</label>
                    <input type="text" name="account_number" required class="form-input" placeholder="Nomor rekening">
                </div>
                <button type="submit" class="btn-primary w-full">Ajukan Penarikan</button>
            </div>
        </form>
    </div>

    <div class="stat-card">
        <h3 class="text-white font-semibold mb-4">Penarikan Terakhir</h3>
        @php $recentWithdrawals = $store->withdrawals()->latest()->take(5)->get(); @endphp
        @if($recentWithdrawals->count() > 0)
        <div class="space-y-3">
            @foreach($recentWithdrawals as $w)
            <div class="flex items-center justify-between p-3 rounded-xl" style="background:var(--color-navy-900);">
                <div>
                    <p class="text-white text-sm font-medium">Rp {{ number_format($w->amount, 0, ',', '.') }}</p>
                    <p class="text-white/40 text-xs">{{ $w->bank_name }} • {{ $w->created_at->format('d M Y') }}</p>
                </div>
                @php
                $wColors = ['pending' => 'yellow', 'approved' => 'green', 'rejected' => 'red', 'paid' => 'blue'];
                @endphp
                <span class="badge badge-{{ $wColors[$w->status] ?? 'gray' }}">{{ ucfirst($w->status) }}</span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-white/40 text-sm text-center py-8">Belum ada penarikan</p>
        @endif
    </div>
</div>

<div class="stat-card">
    <h3 class="text-white font-semibold mb-4">Riwayat Transaksi</h3>
    @if($transactions->count() > 0)
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $txn)
                <tr>
                    <td class="text-white/50 text-sm">{{ $txn->created_at->format('d M Y H:i') }}</td>
                    <td>
                        @php
                        $typeLabels = ['sale' => 'Penjualan', 'commission' => 'Komisi', 'withdrawal' => 'Penarikan', 'refund' => 'Refund', 'adjustment' => 'Penyesuaian'];
                        $typeColors = ['sale' => 'green', 'commission' => 'yellow', 'withdrawal' => 'purple', 'refund' => 'red', 'adjustment' => 'blue'];
                        @endphp
                        <span class="badge badge-{{ $typeColors[$txn->type] ?? 'gray' }}">{{ $typeLabels[$txn->type] ?? $txn->type }}</span>
                    </td>
                    <td class="text-white/80 text-sm">{{ $txn->description ?? '-' }}</td>
                    <td class="font-semibold {{ $txn->amount >= 0 ? 'text-emerald-400' : 'text-red-400' }}">{{ $txn->amount >= 0 ? '+' : '' }} Rp {{ number_format(abs($txn->amount), 0, ',', '.') }}</td>
                    <td class="text-white/60 text-sm">Rp {{ number_format($txn->balance_after, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $transactions->links() }}</div>
    @else
    <p class="text-white/40 text-sm text-center py-8">Belum ada transaksi</p>
    @endif
</div>
@endsection
