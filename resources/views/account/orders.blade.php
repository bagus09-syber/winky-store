@extends('layouts.account')

@section('account-content')
<div class="acct-header">
    <div class="acct-header-row">
        <div>
            <h1 class="acct-title">My Orders</h1>
            <p class="acct-subtitle">Track and manage all your orders</p>
        </div>
    </div>
</div>

@php
$statuses = [
    'pending' => 'Pesanan Dibuat',
    'awaiting_payment' => 'Menunggu Pembayaran',
    'paid' => 'Pembayaran Diterima',
    'processing' => 'Diproses',
    'shipped' => 'Dikirim',
    'delivered' => 'Diterima',
    'completed' => 'Selesai',
    'cancelled' => 'Dibatalkan',
];
@endphp

<div class="acct-filters">
    <a href="{{ route('account.orders') }}" class="acct-chip {{ !request('status') ? 'active' : '' }}">All</a>
    @foreach($statuses as $key => $label)
    <a href="{{ route('account.orders', ['status' => $key]) }}" class="acct-chip {{ request('status') == $key ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</div>

@if ($orders->isEmpty())
<div class="acct-empty">
    <div class="acct-empty-icon">
        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
    </div>
    <h3 class="acct-empty-title">No orders found</h3>
    <p class="acct-empty-text">
        @if(request('status'))
            No orders with status "{{ $statuses[request('status')] ?? request('status') }}" found.
        @else
            You haven't placed any orders yet. Start exploring our products!
        @endif
    </p>
    <a href="{{ route('products.index') }}" class="btn-acct-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
        </svg>
        Start Shopping
    </a>
</div>
@else
<div style="display:flex;flex-direction:column;gap:12px;">
    @foreach ($orders as $order)
    <a href="{{ route('orders.show', $order->order_number) }}" style="display:flex;align-items:center;gap:16px;padding:16px 20px;background:var(--bg-card);border:1px solid var(--border);border-radius:14px;text-decoration:none;color:inherit;transition:all .25s;" onmouseover="this.style.borderColor='rgba(0,229,255,0.2)';this.style.transform='translateY(-1px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='none'">
        <div style="width:48px;height:48px;border-radius:12px;background:var(--bg-deep);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:22px;height:22px;color:var(--text-secondary)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div style="flex:1;min-width:0;">
            <div style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:15px;color:var(--text);">{{ $order->order_number }}</div>
            <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">{{ $order->created_at->format('d M Y, H:i') }}</div>
        </div>
        @php
            $statusStyles = [
                'pending' => 'background:rgba(250,204,21,0.1);color:#facc15;border:1px solid rgba(250,204,21,0.2);',
                'awaiting_payment' => 'background:rgba(255,152,0,0.1);color:#ffb74d;border:1px solid rgba(255,152,0,0.2);',
                'paid' => 'background:rgba(0,229,255,0.1);color:var(--cyan);border:1px solid rgba(0,229,255,0.2);',
                'processing' => 'background:rgba(41,121,255,0.1);color:#60a5fa;border:1px solid rgba(41,121,255,0.2);',
                'shipped' => 'background:rgba(168,85,247,0.1);color:#c084fc;border:1px solid rgba(168,85,247,0.2);',
                'delivered' => 'background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);',
                'completed' => 'background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);',
                'cancelled' => 'background:rgba(239,68,68,0.1);color:#f87171;border:1px solid rgba(239,68,68,0.2);',
            ];
        @endphp
        <span style="display:inline-flex;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;flex-shrink:0;{{ $statusStyles[$order->status] ?? '' }}">{{ $order->status_label }}</span>
        <div style="text-align:right;flex-shrink:0;">
            <div style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:15px;color:var(--cyan);">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
            <div style="font-size:11px;color:var(--text-secondary);margin-top:2px;">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</div>
        </div>
    </a>
    @endforeach
</div>

@if($orders->hasPages())
<div style="margin-top:32px;">
    {{ $orders->links() }}
</div>
@endif
@endif
@endsection
