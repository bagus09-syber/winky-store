@extends('layouts.seller')
@section('title', 'Detail Pesanan - WINKY STORE')
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="stat-card">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-white font-semibold text-lg">{{ $order->order_number }}</h3>
                    <p class="text-white/40 text-sm">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
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
            </div>

            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 p-4 rounded-xl" style="background:var(--color-navy-900);">
                    @if($item->product && $item->product->image)
                    <img src="{{ asset('storage/' . $item->product->image) }}" style="width:60px;height:60px;object-fit:cover;border-radius:10px;">
                    @else
                    <div style="width:60px;height:60px;background:var(--color-navy-700);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <svg class="w-6 h-6 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                    <div class="flex-1">
                        <p class="text-white font-medium text-sm">{{ $item->product_name }}</p>
                        @if($item->variant_name)<p class="text-white/40 text-xs">{{ $item->variant_name }}</p>@endif
                        <p class="text-white/50 text-xs">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-white font-semibold text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="stat-card">
            <h3 class="text-white font-semibold mb-4">Ringkasan Keuangan</h3>
            <div class="space-y-3">
                <div class="flex justify-between"><span class="text-white/50">Subtotal</span><span class="text-white">Rp {{ number_format($order->seller_subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span class="text-white/50">Komisi Marketplace</span><span class="text-red-400">- Rp {{ number_format($order->commission_amount, 0, ',', '.') }}</span></div>
                <div class="border-t border-white/5 pt-3 flex justify-between"><span class="text-white font-semibold">Pendapatan Bersih</span><span class="text-[var(--cyan)] font-bold text-lg">Rp {{ number_format($order->seller_earning, 0, ',', '.') }}</span></div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="stat-card">
            <h3 class="text-white font-semibold mb-4">Info Pelanggan</h3>
            <div class="space-y-2 text-sm">
                <p class="text-white/80"><span class="text-white/40">Nama:</span> {{ $order->recipient_name }}</p>
                <p class="text-white/80"><span class="text-white/40">Telepon:</span> {{ $order->recipient_phone }}</p>
                <p class="text-white/80"><span class="text-white/40">Alamat:</span> {{ $order->shipping_address }}</p>
            </div>
        </div>

        @if($order->seller_status === 'pending' || $order->seller_status === 'processing')
        <div class="stat-card">
            <h3 class="text-white font-semibold mb-4">Proses Pesanan</h3>
            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="space-y-3">
                    @if($order->seller_status === 'pending')
                    <button type="submit" name="status" value="processing" class="btn-primary w-full text-sm">Mulai Proses</button>
                    @endif
                    @if($order->seller_status === 'processing')
                    <button type="submit" name="status" value="shipped" class="btn-primary w-full text-sm">Tandai Dikirim</button>
                    @endif
                    <button type="submit" name="status" value="cancelled" class="btn-secondary w-full text-sm text-red-400 border-red-500/20" onclick="return confirm('Yakin batalkan pesanan ini?')">Batalkan</button>
                </div>
            </form>
        </div>
        @endif

        @if($order->seller_status === 'shipped')
        <div class="stat-card">
            <h3 class="text-white font-semibold mb-4">Pengiriman</h3>
            <div class="space-y-2 text-sm">
                <p class="text-white/80"><span class="text-white/40">Kurir:</span> {{ strtoupper($order->seller_courier ?? '-') }}</p>
                <p class="text-white/80"><span class="text-white/40">Layanan:</span> {{ strtoupper($order->seller_service ?? '-') }}</p>
                <p class="text-white/80"><span class="text-white/40">Resi:</span> {{ $order->seller_tracking_number ?? '-' }}</p>
                <p class="text-white/80"><span class="text-white/40">Dikirim:</span> {{ $order->seller_shipped_at?->format('d M Y H:i') ?? '-' }}</p>
            </div>
            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST" class="mt-4">
                @csrf @method('PATCH')
                <button type="submit" name="status" value="delivered" class="btn-primary w-full text-sm">Tandai Diterima</button>
            </form>
        </div>
        @endif

        @if($order->seller_status === 'delivered')
        <div class="stat-card">
            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" name="status" value="completed" class="btn-primary w-full text-sm">Selesaikan Pesanan</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
