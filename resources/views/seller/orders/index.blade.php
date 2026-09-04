@extends('layouts.seller')
@section('title', 'Pesanan - WINKY STORE')
@section('page-title', 'Pesanan')

@section('content')
<div class="flex flex-wrap gap-3 mb-6">
    <form action="{{ route('seller.orders.index') }}" method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor pesanan..." class="form-input" style="width:240px;">
        <select name="status" class="form-input form-select" style="width:160px;">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="btn-secondary text-sm">Filter</button>
    </form>
</div>

@if($orders->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nomor Pesanan</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td class="font-mono text-sm text-[var(--cyan)]">{{ $order->order_number }}</td>
                <td class="text-white/80">{{ $order->user->name }}</td>
                <td class="text-white font-semibold">Rp {{ number_format($order->seller_subtotal, 0, ',', '.') }}</td>
                <td>
                    @php
                    $statusColors = [
                        'pending' => 'yellow', 'processing' => 'blue', 'shipped' => 'purple',
                        'delivered' => 'green', 'completed' => 'green', 'cancelled' => 'red',
                    ];
                    $statusLabels = [
                        'pending' => 'Menunggu', 'processing' => 'Diproses', 'shipped' => 'Dikirim',
                        'delivered' => 'Diterima', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan',
                    ];
                    @endphp
                    <span class="badge badge-{{ $statusColors[$order->seller_status] ?? 'gray' }}">{{ $statusLabels[$order->seller_status] ?? $order->seller_status }}</span>
                </td>
                <td class="text-white/50 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('seller.orders.show', $order->id) }}" class="text-white/60 hover:text-[var(--cyan)]" title="Detail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $orders->links() }}</div>
@else
<div class="stat-card text-center py-16">
    <svg class="w-16 h-16 mx-auto text-white/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    <p class="text-white/40">Belum ada pesanan</p>
</div>
@endif
@endsection
