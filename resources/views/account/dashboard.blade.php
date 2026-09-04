@extends('layouts.account')

@section('account-content')
@php
    $user = Auth::user();
    $orderCount = \App\Models\Order::where('user_id', $user->id)->count();
    $pendingOrders = \App\Models\Order::where('user_id', $user->id)->where('status', 'pending')->count();
    $completedOrders = \App\Models\Order::where('user_id', $user->id)->where('status', 'completed')->count();
    $wishlistCount = \App\Models\Wishlist::where('user_id', $user->id)->count();
    $addressCount = \App\Models\Address::where('user_id', $user->id)->count();
    $recentOrders = \App\Models\Order::where('user_id', $user->id)->with('items')->latest()->take(5)->get();
@endphp

<div class="acct-header">
    <h1 class="acct-title">Welcome back, {{ $user->name }}</h1>
    <p class="acct-subtitle">Here's an overview of your account activity</p>
</div>

{{-- Stats --}}
<div class="acct-stats">
    <div class="stat-card">
        <div class="stat-icon cyan">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ $orderCount }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $pendingOrders }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Completed</div>
            <div class="stat-value">{{ $completedOrders }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon magenta">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Wishlist</div>
            <div class="stat-value">{{ $wishlistCount }}</div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="acct-actions">
    <a href="{{ route('account.orders') }}" class="acct-action">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        View All Orders
    </a>
    <a href="{{ route('wishlist.index') }}" class="acct-action">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        My Wishlist
    </a>
    <a href="{{ route('account.addresses') }}" class="acct-action">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Manage Addresses
    </a>
    <a href="{{ route('products.index') }}" class="acct-action">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
        </svg>
        Continue Shopping
    </a>
</div>

{{-- Recent Orders --}}
<div class="acct-card">
    <div class="acct-card-header">
        <h2 class="acct-card-title">Recent Orders</h2>
        @if($orderCount > 0)
        <a href="{{ route('account.orders') }}" class="acct-card-link">
            View All
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif
    </div>

    @if($recentOrders->isEmpty())
    <div class="acct-empty" style="padding: 40px 20px;">
        <div class="acct-empty-icon">
            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <h3 class="acct-empty-title">No orders yet</h3>
        <p class="acct-empty-text">Start shopping to see your orders here</p>
        <a href="{{ route('products.index') }}" class="btn-acct-primary">Browse Products</a>
    </div>
    @else
    <div style="display:flex;flex-direction:column;gap:8px;">
        @foreach($recentOrders as $order)
        <a href="{{ route('orders.show', $order->order_number) }}" class="acct-order">
            <div class="acct-order-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="acct-order-info">
                <div class="acct-order-num">{{ $order->order_number }}</div>
                <div class="acct-order-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
            </div>
            <span class="badge-status badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
            <div class="acct-order-right">
                <div class="acct-order-total">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                <div class="acct-order-count">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>
@endsection
