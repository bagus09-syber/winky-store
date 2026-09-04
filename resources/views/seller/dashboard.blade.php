@extends('layouts.seller')
@section('title', 'Dashboard Seller - WINKY STORE')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <p class="text-white/50 text-xs mb-1">Pendapatan Hari Ini</p>
        <p class="font-display font-bold text-xl text-emerald-400">Rp {{ number_format($dashboardData['today']['revenue'] ?? 0, 0, ',', '.') }}</p>
        <p class="text-white/40 text-xs mt-1">{{ $dashboardData['today']['orders'] ?? 0 }} pesanan</p>
    </div>
    <div class="stat-card">
        <p class="text-white/50 text-xs mb-1">Pesanan Minggu Ini</p>
        <p class="font-display font-bold text-xl text-blue-400">{{ $dashboardData['weekly']['orders'] ?? 0 }}</p>
        <p class="text-white/40 text-xs mt-1">Rp {{ number_format($dashboardData['weekly']['revenue'] ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="stat-card">
        <p class="text-white/50 text-xs mb-1">Pesanan Bulan Ini</p>
        <p class="font-display font-bold text-xl text-purple-400">{{ $dashboardData['monthly']['orders'] ?? 0 }}</p>
        <p class="text-white/40 text-xs mt-1">Rp {{ number_format($dashboardData['monthly']['revenue'] ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="stat-card">
        <p class="text-white/50 text-xs mb-1">Konversi</p>
        <p class="font-display font-bold text-xl text-cyan-400">{{ $dashboardData['conversion_rate'] ?? 0 }}%</p>
        <p class="text-white/40 text-xs mt-1">{{ $dashboardData['today']['orders'] ?? 0 }} / {{ $dashboardData['today']['visitors'] ?? 1 }} pengunjung</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <p class="text-white/50 text-xs mb-1">Produk Aktif</p>
        <p class="font-display font-bold text-xl text-white">{{ $activeProducts }}</p>
        <p class="text-white/40 text-xs mt-1">{{ $lowStockProducts }} stok rendah</p>
    </div>
    <div class="stat-card">
        <p class="text-white/50 text-xs mb-1">Pesanan Pending</p>
        <p class="font-display font-bold text-xl text-yellow-400">{{ $pendingOrders }}</p>
        <p class="text-white/40 text-xs mt-1">{{ $totalOrders }} total</p>
    </div>
    <div class="stat-card">
        <p class="text-white/50 text-xs mb-1">Saldo Tersedia</p>
        <p class="font-display font-bold text-xl text-cyan-400">Rp {{ number_format($wallet->available_balance ?? 0, 0, ',', '.') }}</p>
        <p class="text-white/40 text-xs mt-1">Escrow: Rp {{ number_format($wallet->escrow_balance ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="stat-card">
        <p class="text-white/50 text-xs mb-1">Retur / Batal</p>
        <p class="font-display font-bold text-xl text-red-400">{{ $dashboardData['returns_count'] ?? 0 }} / {{ $dashboardData['cancelled_count'] ?? 0 }}</p>
        <p class="text-white/40 text-xs mt-1">Bulan ini</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 stat-card">
        <h3 class="text-white font-semibold mb-4">Pendapatan 7 Hari Terakhir</h3>
        <div class="flex items-end gap-2" style="height:160px;">
            @php $maxRevenue = max($last7Days->pluck('revenue')->toArray()); @endphp
            @foreach($last7Days as $day)
            <div class="flex-1 flex flex-col items-center gap-1">
                <span class="text-[10px] text-white/40">Rp {{ number_format($day['revenue'] / 1000, 0, ',', '.') }}k</span>
                <div class="w-full rounded-t-lg transition-all" style="height:{{ $maxRevenue > 0 ? ($day['revenue'] / $maxRevenue) * 120 : 4 }}px;background:linear-gradient(to top, var(--color-blue-600), var(--color-cyan-500));min-height:4px;"></div>
                <span class="text-[10px] text-white/30">{{ $day['date'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="stat-card">
        <h3 class="text-white font-semibold mb-4">Info Toko</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-white/50 text-sm">Status</span>
                <span class="badge badge-green">Aktif</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-white/50 text-sm">Rating</span>
                <span class="text-white font-semibold">{{ $store->rating }} ★</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-white/50 text-sm">Total Produk</span>
                <span class="text-white font-semibold">{{ $totalProducts }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-white/50 text-sm">Bergabung</span>
                <span class="text-white font-semibold">{{ $store->created_at->format('d M Y') }}</span>
            </div>
            @if($store->is_verified)
            <div class="flex items-center justify-between">
                <span class="text-white/50 text-sm">Verifikasi</span>
                <span class="badge badge-cyan" style="background:rgba(34,211,238,0.1);color:var(--cyan);">✓ Terverifikasi</span>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="stat-card">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-white font-semibold">Pesanan Terbaru</h3>
        <a href="{{ route('seller.orders.index') }}" class="text-sm text-[var(--cyan)] hover:underline">Lihat Semua</a>
    </div>

    @if($recentOrders->count() > 0)
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nomor Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td>
                        <a href="{{ route('seller.orders.show', $order->id) }}" class="text-[var(--cyan)] hover:underline font-mono text-sm">{{ $order->order_number }}</a>
                    </td>
                    <td class="text-white/80">{{ $order->user->name }}</td>
                    <td class="text-white font-semibold">Rp {{ number_format($order->seller_subtotal, 0, ',', '.') }}</td>
                    <td>
                        @php
                        $statusColors = [
                            'pending' => 'yellow', 'processing' => 'blue', 'shipped' => 'purple',
                            'delivered' => 'green', 'completed' => 'green', 'cancelled' => 'red',
                        ];
                        @endphp
                        <span class="badge badge-{{ $statusColors[$order->seller_status] ?? 'gray' }}">{{ ucfirst($order->seller_status) }}</span>
                    </td>
                    <td class="text-white/50 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-16 h-16 mx-auto text-white/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        <p class="text-white/40">Belum ada pesanan</p>
    </div>
    @endif
</div>
@endsection
