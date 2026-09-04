<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seller Center - @yield('title', 'WINKY STORE')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --color-navy-900: #0a0e1a;
            --color-navy-800: #111827;
            --color-navy-700: #1a2332;
            --color-navy-600: #243044;
            --color-cyan-400: #22d3ee;
            --color-cyan-500: #06b6d4;
            --color-blue-600: #2563eb;
        }
        body { font-family: 'Inter', sans-serif; background: var(--color-navy-900); color: #fff; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        .seller-sidebar { width: 260px; min-height: 100vh; background: var(--color-navy-800); border-right: 1px solid rgba(255,255,255,0.05); position: fixed; left: 0; top: 0; z-index: 40; transition: transform 0.3s; }
        .seller-main { margin-left: 260px; min-height: 100vh; }
        .seller-topbar { height: 64px; background: var(--color-navy-800); border-bottom: 1px solid rgba(255,255,255,0.05); position: sticky; top: 0; z-index: 30; display: flex; align-items: center; justify-content: space-between; padding: 0 24px; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.6); transition: all 0.2s; }
        .sidebar-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .sidebar-link.active { color: var(--color-cyan-400); background: rgba(34,211,238,0.1); }
        .stat-card { background: var(--color-navy-800); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; padding: 24px; }
        .btn-primary { background: linear-gradient(135deg, var(--color-blue-600), var(--color-cyan-500)); color: #fff; padding: 10px 20px; border-radius: 12px; font-weight: 600; transition: all 0.3s; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-secondary { background: rgba(255,255,255,0.05); color: #fff; padding: 10px 20px; border-radius: 12px; font-weight: 500; border: 1px solid rgba(255,255,255,0.1); transition: all 0.2s; }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); }
        .table-container { background: var(--color-navy-800); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; overflow: hidden; }
        .table-container table { width: 100%; border-collapse: collapse; }
        .table-container th { padding: 16px 20px; text-align: left; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: rgba(255,255,255,0.5); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .table-container td { padding: 16px 20px; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .form-input { width: 100%; padding: 12px 16px; background: var(--color-navy-900); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: #fff; font-size: 14px; transition: border-color 0.2s; }
        .form-input:focus { outline: none; border-color: var(--color-cyan-400); }
        .form-input::placeholder { color: rgba(255,255,255,0.3); }
        .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='rgba(255,255,255,0.5)'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; }
        .badge { padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 500; }
        .badge-yellow { background: rgba(234,179,8,0.1); color: #eab308; }
        .badge-blue { background: rgba(59,130,246,0.1); color: #3b82f6; }
        .badge-purple { background: rgba(168,85,247,0.1); color: #a855f7; }
        .badge-green { background: rgba(34,197,94,0.1); color: #22c55e; }
        .badge-red { background: rgba(239,68,68,0.1); color: #ef4444; }
        .badge-gray { background: rgba(107,114,128,0.1); color: #6b7280; }
        @media (max-width: 1024px) {
            .seller-sidebar { transform: translateX(-100%); }
            .seller-sidebar.open { transform: translateX(0); }
            .seller-main { margin-left: 0; }
        }
    </style>
</head>
<body class="antialiased">
    <aside class="seller-sidebar" id="seller-sidebar">
        <div class="p-6">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[var(--color-blue-600)] to-[var(--color-cyan-500)] flex items-center justify-center">
                    <span class="font-display font-bold text-white text-lg">W</span>
                </div>
                <div>
                    <p class="font-display font-bold text-white text-lg leading-tight">WINKY</p>
                    <p class="text-white/40 text-xs">Seller Center</p>
                </div>
            </a>
        </div>

        <nav class="px-4 space-y-1">
            <a href="{{ route('seller.dashboard') }}" class="sidebar-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <p class="text-white/30 text-xs font-semibold uppercase tracking-wider px-4 pt-6 pb-2">Toko</p>

            <a href="{{ route('seller.products.index') }}" class="sidebar-link {{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Produk
            </a>

            <a href="{{ route('seller.orders.index') }}" class="sidebar-link {{ request()->routeIs('seller.orders.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pesanan
            </a>

            <a href="{{ route('seller.wallet') }}" class="sidebar-link {{ request()->routeIs('seller.wallet') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Wallet
            </a>

            <a href="{{ route('store.show', Auth::user()->store->slug) }}" class="sidebar-link" target="_blank">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Lihat Toko
            </a>
        </nav>

        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/5">
            <a href="{{ route('home') }}" class="sidebar-link">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Lihat Website
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left text-red-400/60 hover:text-red-400 hover:bg-red-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="seller-main">
        <div class="seller-topbar">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 text-white/60 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h2 class="font-display font-semibold text-white text-lg">@yield('page-title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-white/50 text-sm">{{ Auth::user()->store->name }}</span>
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[var(--color-blue-600)] to-[var(--color-cyan-500)] flex items-center justify-center text-white font-bold text-sm">
                    {{ substr(Auth::user()->store->name, 0, 1) }}
                </div>
            </div>
        </div>

        <div class="p-6">
            @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-emerald-300 text-sm">{{ session('success') }}</p>
            </div>
            @endif

            @if (session('error'))
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-red-300 text-sm">{{ session('error') }}</p>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
    function toggleSidebar() {
        document.getElementById('seller-sidebar').classList.toggle('open');
    }
    </script>
</body>
</html>
