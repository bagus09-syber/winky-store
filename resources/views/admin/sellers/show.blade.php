@extends('layouts.admin')
@section('title', $seller->name . ' - Admin')
@section('page-title', 'Detail Seller')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="stat-card">
        <div class="flex items-center gap-3 mb-4">
            @if($seller->logo)
            <img src="{{ asset('storage/' . $seller->logo) }}" style="width:56px;height:56px;object-fit:cover;border-radius:14px;">
            @else
            <div style="width:56px;height:56px;background:linear-gradient(135deg,var(--color-blue-600),var(--color-cyan-500));border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:20px;">{{ substr($seller->name, 0, 1) }}</div>
            @endif
            <div>
                <h3 class="text-white font-semibold text-lg">{{ $seller->name }}</h3>
                <p class="text-white/40 text-sm">{{ $seller->user->name }}</p>
            </div>
        </div>
        <div class="space-y-2 text-sm">
            <p class="text-white/80"><span class="text-white/40">Status:</span>
                @php $sc = ['pending'=>'yellow','active'=>'green','suspended'=>'red','rejected'=>'red']; @endphp
                <span class="badge badge-{{ $sc[$seller->status] }}">{{ ucfirst($seller->status) }}</span>
            </p>
            <p class="text-white/80"><span class="text-white/40">Rating:</span> {{ $seller->rating }} ★</p>
            <p class="text-white/80"><span class="text-white/40">Lokasi:</span> {{ $seller->city }}, {{ $seller->province }}</p>
            <p class="text-white/80"><span class="text-white/40">Bergabung:</span> {{ $seller->created_at->format('d M Y') }}</p>
            @if($seller->is_verified)
            <p class="text-white/80"><span class="text-white/40">Verifikasi:</span> <span style="color:var(--cyan);">✓ Terverifikasi</span></p>
            @endif
        </div>
        <div class="flex gap-2 mt-4">
            @if($seller->status === 'pending')
            <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST">@csrf @method('PATCH')
                <button type="submit" class="btn-primary text-xs">Approve</button>
            </form>
            <form action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST">@csrf @method('PATCH')
                <button type="submit" class="btn-secondary text-xs text-red-400">Reject</button>
            </form>
            @endif
            @if($seller->status === 'active')
            <form action="{{ route('admin.sellers.suspend', $seller->id) }}" method="POST">@csrf @method('PATCH')
                <button type="submit" class="btn-secondary text-xs text-yellow-400">Suspend</button>
            </form>
            @endif
            <form action="{{ route('admin.sellers.toggleVerification', $seller->id) }}" method="POST">@csrf @method('PATCH')
                <button type="submit" class="btn-secondary text-xs">{{ $seller->is_verified ? 'Unverify' : 'Verify' }}</button>
            </form>
        </div>
    </div>

    <div class="stat-card">
        <h3 class="text-white font-semibold mb-4">Ringkasan</h3>
        <div class="space-y-3">
            <div class="flex justify-between"><span class="text-white/50">Total Produk</span><span class="text-white font-semibold">{{ $totalProducts }}</span></div>
            <div class="flex justify-between"><span class="text-white/50">Produk Aktif</span><span class="text-white font-semibold">{{ $activeProducts }}</span></div>
            <div class="flex justify-between"><span class="text-white/50">Total Pesanan</span><span class="text-white font-semibold">{{ $totalOrders }}</span></div>
            <div class="flex justify-between"><span class="text-white/50">Total Pendapatan</span><span class="text-[var(--cyan)] font-semibold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span></div>
        </div>
    </div>

    <div class="stat-card">
        <h3 class="text-white font-semibold mb-4">Wallet</h3>
        @if($seller->wallet)
        <div class="space-y-3">
            <div class="flex justify-between"><span class="text-white/50">Tersedia</span><span class="text-white font-semibold">Rp {{ number_format($seller->wallet->available_balance, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span class="text-white/50">Pending</span><span class="text-white font-semibold">Rp {{ number_format($seller->wallet->pending_balance, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span class="text-white/50">Total Ditarik</span><span class="text-white font-semibold">Rp {{ number_format($seller->wallet->total_withdrawn, 0, ',', '.') }}</span></div>
        </div>
        @else
        <p class="text-white/40 text-sm">Wallet tidak ditemukan</p>
        @endif
    </div>
</div>

<div class="stat-card">
    <h3 class="text-white font-semibold mb-4">Pesanan Terbaru</h3>
    @if($recentOrders->count() > 0)
    <div class="table-container">
        <table>
            <thead><tr><th>Nomor</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td class="font-mono text-sm text-[var(--cyan)]">{{ $order->order_number }}</td>
                    <td class="text-white/80">{{ $order->user->name }}</td>
                    <td class="text-white font-semibold">Rp {{ number_format($order->seller_subtotal, 0, ',', '.') }}</td>
                    <td>
                        @php $sc2 = ['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','completed'=>'green','cancelled'=>'red']; @endphp
                        <span class="badge badge-{{ $sc2[$order->seller_status] ?? 'gray' }}">{{ ucfirst($order->seller_status) }}</span>
                    </td>
                    <td class="text-white/50 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-white/40 text-sm text-center py-8">Belum ada pesanan</p>
    @endif
</div>
@endsection
