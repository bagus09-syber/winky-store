@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
{{-- Enterprise Stats Cards --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/50 text-xs mb-1">GMV</p>
                <p class="font-display font-bold text-xl text-[var(--color-cyan-400)]">Rp {{ number_format($stats['gmv'], 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-cyan-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/50 text-xs mb-1">Komisi</p>
                <p class="font-display font-bold text-xl text-emerald-400">Rp {{ number_format($stats['total_commission'], 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/50 text-xs mb-1">Pesanan</p>
                <p class="font-display font-bold text-xl text-white">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/50 text-xs mb-1">Seller Aktif</p>
                <p class="font-display font-bold text-xl text-purple-400">{{ $stats['active_sellers'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/50 text-xs mb-1">Produk</p>
                <p class="font-display font-bold text-xl text-blue-400">{{ $stats['total_products'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Pending Actions --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <a href="{{ route('admin.orders.index', ['status' => 'awaiting_payment']) }}" class="stat-card hover:border-yellow-500/30 transition-colors">
        <p class="text-white/50 text-sm mb-1">Menunggu Pembayaran</p>
        <p class="font-display font-bold text-2xl text-yellow-400">{{ $stats['pending_payments'] }}</p>
    </a>
    <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}" class="stat-card hover:border-orange-500/30 transition-colors">
        <p class="text-white/50 text-sm mb-1">Penarikan Pending</p>
        <p class="font-display font-bold text-2xl text-orange-400">{{ $stats['pending_withdrawals'] }}</p>
    </a>
    <a href="{{ route('admin.orders.index') }}" class="stat-card hover:border-red-500/30 transition-colors">
        <p class="text-white/50 text-sm mb-1">Retur Pending</p>
        <p class="font-display font-bold text-2xl text-red-400">{{ $stats['pending_returns'] }}</p>
    </a>
</div>

{{-- Revenue Chart --}}
<div class="stat-card mb-8">
    <h3 class="font-display font-semibold text-white mb-4">Pendapatan 30 Hari</h3>
    <div class="overflow-x-auto">
        <div class="min-w-[600px] h-48 flex items-end gap-1">
            @php
            $maxRevenue = $revenueByDay->max('revenue') ?: 1;
            @endphp
            @forelse($revenueByDay as $day)
            <div class="flex-1 flex flex-col items-center gap-1" title="{{ $day->date }}: Rp {{ number_format($day->revenue, 0, ',', '.') }} ({{ $day->orders }} pesanan)">
                <div class="w-full bg-cyan-500/20 rounded-t" style="height: {{ ($day->revenue / $maxRevenue) * 100 }}%"></div>
                <span class="text-[10px] text-white/40">{{ \Carbon\Carbon::parse($day->date)->format('d') }}</span>
            </div>
            @empty
            <div class="w-full text-center text-white/40 py-8">Belum ada data</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Orders by Status --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
    <div class="stat-card text-center">
        <p class="text-white/50 text-xs mb-1">Pending</p>
        <p class="font-display font-bold text-xl text-yellow-400">{{ $ordersByStatus->get('pending', 0) }}</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-white/50 text-xs mb-1">Awaiting</p>
        <p class="font-display font-bold text-xl text-orange-400">{{ $ordersByStatus->get('awaiting_payment', 0) }}</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-white/50 text-xs mb-1">Paid</p>
        <p class="font-display font-bold text-xl text-blue-400">{{ $ordersByStatus->get('paid', 0) }}</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-white/50 text-xs mb-1">Processing</p>
        <p class="font-display font-bold text-xl text-purple-400">{{ $ordersByStatus->get('processing', 0) }}</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-white/50 text-xs mb-1">Shipped</p>
        <p class="font-display font-bold text-xl text-cyan-400">{{ $ordersByStatus->get('shipped', 0) }}</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-white/50 text-xs mb-1">Completed</p>
        <p class="font-display font-bold text-xl text-green-400">{{ $ordersByStatus->get('completed', 0) }}</p>
    </div>
</div>

{{-- Recent Orders --}}
<div class="table-container">
    <div class="flex items-center justify-between p-6 border-b border-white/5">
        <h3 class="font-display font-semibold text-white">Pesanan Terbaru</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-[var(--color-cyan-400)] text-sm hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Customer</th>
                    <th>Toko</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td class="font-medium text-white">{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td class="text-white/60 text-sm">{{ $order->store->name ?? '-' }}</td>
                    <td class="text-[var(--color-cyan-400)]">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td>
                        @php
                        $statusColors = [
                            'pending' => 'yellow',
                            'awaiting_payment' => 'orange',
                            'paid' => 'blue',
                            'processing' => 'blue',
                            'shipped' => 'purple',
                            'delivered' => 'green',
                            'completed' => 'green',
                            'cancelled' => 'red',
                        ];
                        $color = $statusColors[$order->status] ?? 'gray';
                        @endphp
                        <span class="badge badge-{{ $color }}">{{ $order->status_label }}</span>
                    </td>
                    <td class="text-white/50">{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->order_number) }}" class="text-[var(--color-cyan-400)] hover:underline text-sm">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-white/40 py-8">Belum ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
