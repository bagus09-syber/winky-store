@extends('layouts.app')

@section('content')
<section class="acct-section">
    <div class="acct-wrap">

        {{-- Mobile Sidebar Toggle --}}
        <button class="acct-mob-toggle" id="acct-sidebar-toggle" aria-label="Toggle menu">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span>Menu</span>
        </button>

        {{-- Mobile Sidebar Overlay --}}
        <div class="acct-mob-overlay" id="acct-sidebar-overlay"></div>

        {{-- Sidebar --}}
        <aside class="acct-sidebar" id="acct-sidebar">
            <div class="acct-sidebar-head">
                <div class="acct-avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="acct-sidebar-user">
                    <p class="acct-sidebar-greeting">Welcome back,</p>
                    <p class="acct-sidebar-name">{{ Auth::user()->name }}</p>
                </div>
                <button class="acct-sidebar-close" id="acct-sidebar-close" aria-label="Close menu">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="acct-nav">
                <a href="{{ route('account.dashboard') }}" class="acct-nav-link {{ request()->routeIs('account.dashboard') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('account.orders') }}" class="acct-nav-link {{ request()->routeIs('account.orders') || request()->routeIs('orders.show') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>My Orders</span>
                </a>
                <a href="{{ route('wishlist.index') }}" class="acct-nav-link {{ request()->routeIs('wishlist.index') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span>Wishlist</span>
                </a>
                <a href="{{ route('account.addresses') }}" class="acct-nav-link {{ request()->routeIs('account.addresses*') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Addresses</span>
                </a>
                <a href="{{ route('account.notifications') }}" class="acct-nav-link {{ request()->routeIs('account.notifications') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span>Notifications</span>
                </a>
                <a href="{{ route('account.returns') }}" class="acct-nav-link {{ request()->routeIs('account.returns') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
                    </svg>
                    <span>Returns</span>
                </a>
                <a href="{{ route('account.tickets') }}" class="acct-nav-link {{ request()->routeIs('account.tickets') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Support</span>
                </a>

                <div class="acct-nav-divider"></div>

                <a href="{{ route('account.profile') }}" class="acct-nav-link {{ request()->routeIs('account.profile') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Profile</span>
                </a>
                <a href="{{ route('account.security') }}" class="acct-nav-link {{ request()->routeIs('account.security') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span>Security</span>
                </a>
            </nav>

            <div class="acct-sidebar-foot">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="acct-nav-link acct-logout">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="acct-main">
            @if (session('success'))
            <div class="acct-alert acct-alert-success">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if (session('error'))
            <div class="acct-alert acct-alert-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @yield('account-content')
        </main>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var toggle = document.getElementById('acct-sidebar-toggle');
    var sidebar = document.getElementById('acct-sidebar');
    var overlay = document.getElementById('acct-sidebar-overlay');
    var closeBtn = document.getElementById('acct-sidebar-close');

    function openSidebar(){ sidebar.classList.add('open'); overlay.classList.add('open'); document.body.style.overflow='hidden'; }
    function closeSidebar(){ sidebar.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow=''; }

    if(toggle) toggle.addEventListener('click', openSidebar);
    if(overlay) overlay.addEventListener('click', closeSidebar);
    if(closeBtn) closeBtn.addEventListener('click', closeSidebar);

    sidebar.querySelectorAll('.acct-nav-link').forEach(function(link){
        link.addEventListener('click', function(){
            if(window.innerWidth < 1024) closeSidebar();
        });
    });
});
</script>
@endsection
