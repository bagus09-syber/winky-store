<nav id="main-navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-[72px]">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 font-display font-bold text-xl lg:text-2xl group" aria-label="Winky Store Home">
                <div class="w-10 h-10 lg:w-11 lg:h-11 rounded-xl glow-cyan flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="gradient-text hidden sm:block">WINKY STORE</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex lg:items-center lg:gap-1">
                <a href="{{ route('home') }}" class="nx-link text-white/80 hover:text-[var(--cyan)] font-medium text-[13px] tracking-wide px-4 py-2 relative">Home</a>
                <a href="{{ route('products.index') }}" class="nx-link text-white/80 hover:text-[var(--cyan)] font-medium text-[13px] tracking-wide px-4 py-2">Semua Produk</a>
                @php
                $navCategories = \App\Models\Category::where('is_active', true)
                    ->whereNull('parent_id')
                    ->with(['children' => function ($q) {
                        $q->where('is_active', true)->withCount('products');
                    }])
                    ->get()
                    ->map(function ($cat) {
                        $cat->total_products_count = $cat->products_count + $cat->children->sum('products_count');
                        return $cat;
                    })
                    ->sortBy('name')
                    ->take(8)
                    ->values();
                @endphp
                @foreach($navCategories as $navCat)
                <div class="relative group" style="position:relative;">
                    <a href="{{ route('products.index', ['category' => $navCat->slug]) }}" class="nx-link text-white/80 hover:text-[var(--cyan)] font-medium text-[13px] tracking-wide px-4 py-2 flex items-center gap-1">
                        {{ $navCat->name }}
                        @if($navCat->children->count() > 0)
                        <svg style="width:10px;height:10px;opacity:0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        @endif
                    </a>
                    @if($navCat->children->count() > 0)
                    <div class="group-hover:block hidden" style="position:absolute;top:100%;left:0;min-width:200px;background:var(--bg-card);border:1px solid var(--border);border-radius:14px;padding:8px;box-shadow:0 20px 40px rgba(0,0,0,0.4);z-index:100;">
                        <a href="{{ route('products.index', ['category' => $navCat->slug]) }}" style="display:block;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;color:var(--cyan);text-decoration:none;">Lihat Semua ({{ $navCat->total_products_count ?? $navCat->products_count }})</a>
                        @foreach($navCat->children->take(10) as $child)
                        <a href="{{ route('products.index', ['category' => $child->slug]) }}" style="display:block;padding:7px 14px;border-radius:8px;font-size:12px;color:var(--text-secondary);text-decoration:none;transition:all .2s;" onmouseover="this.style.color='var(--cyan)';this.style.background='rgba(0,229,255,0.05)'" onmouseout="this.style.color='var(--text-secondary)';this.style.background='transparent'">
                            {{ $child->name }}
                            @if($child->products_count > 0)<span style="opacity:0.5;font-size:11px;margin-left:4px;">({{ $child->products_count }})</span>@endif
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center gap-2 lg:gap-3">
                <!-- Search -->
                <button id="search-btn" class="relative p-2.5 text-white/60 hover:text-[var(--cyan)] transition-colors rounded-xl hover:bg-white/5" aria-label="Search">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- Wishlist -->
                @auth
                <a href="{{ route('wishlist.index') }}" class="relative p-2.5 text-white/60 hover:text-[var(--cyan)] transition-colors rounded-xl hover:bg-white/5" aria-label="Wishlist">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    @if(Auth::user()->wishlists()->count() > 0)
                    <span class="absolute -top-0.5 -right-0.5 w-[18px] h-[18px] bg-[var(--cyan)] text-[#000] text-[10px] font-bold rounded-full flex items-center justify-center">{{ Auth::user()->wishlists()->count() }}</span>
                    @endif
                </a>
                @else
                <a href="{{ route('login') }}" class="relative p-2.5 text-white/60 hover:text-[var(--cyan)] transition-colors rounded-xl hover:bg-white/5" aria-label="Wishlist">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </a>
                @endauth

                <!-- Cart -->
                @auth
                <a href="{{ route('cart.index') }}" class="relative p-2.5 text-white/60 hover:text-[var(--cyan)] transition-colors rounded-xl hover:bg-white/5" aria-label="Shopping Cart">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    @php
                    $cartCount = 0;
                    if(Auth::user()->cart) {
                        $cartCount = Auth::user()->cart->total_quantity;
                    }
                    @endphp
                    @if($cartCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 w-[18px] h-[18px] bg-[var(--cyan)] text-[#000] text-[10px] font-bold rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
                @else
                <a href="{{ route('login') }}" class="relative p-2.5 text-white/60 hover:text-[var(--cyan)] transition-colors rounded-xl hover:bg-white/5" aria-label="Shopping Cart">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </a>
                @endauth

                <!-- Notification Bell -->
                @auth
                <div class="relative" id="notif-dropdown-wrapper">
                    <button onclick="toggleNotifDropdown()" class="relative p-2.5 text-white/60 hover:text-[var(--cyan)] transition-colors rounded-xl hover:bg-white/5" aria-label="Notifications">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
                        @if($unreadCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 w-[18px] h-[18px] bg-[var(--magenta)] text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </button>
                    <div id="notif-dropdown-menu" class="absolute right-0 top-full mt-2 w-80 opacity-0 invisible scale-95 transition-all duration-200 bg-[var(--bg-card)] border border-white/10 rounded-2xl shadow-2xl shadow-black/40 z-50 overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-white/5">
                            <span class="text-sm font-semibold text-white">Notifikasi</span>
                            @if($unreadCount > 0)
                            <form action="{{ route('notifications.markAllRead') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="text-xs text-[var(--cyan)] hover:underline">Tandai semua dibaca</button>
                            </form>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @php $notifs = Auth::user()->notifications()->take(10)->get(); @endphp
                            @forelse($notifs as $notif)
                            <a href="{{ $notif->data['url'] ?? '#' }}" class="flex items-start gap-3 px-4 py-3 {{ $notif->isRead() ? 'opacity-60' : '' }} hover:bg-white/5 transition-colors border-b border-white/5 last:border-0" onclick="event.preventDefault();fetch('{{ route('notifications.markRead', $notif->id) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>window.location.href='{{ $notif->data['url'] ?? '#' }}')">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg style="width:16px;height:16px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $notif->icon }}"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-white font-medium leading-tight">{{ $notif->data['title'] ?? 'Notifikasi' }}</div>
                                    <div class="text-[11px] text-white/50 mt-0.5 truncate">{{ $notif->data['message'] ?? '' }}</div>
                                    <div class="text-[10px] text-white/30 mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                                </div>
                                @if(!$notif->isRead())
                                <div class="w-2 h-2 rounded-full bg-[var(--cyan)] flex-shrink-0 mt-2"></div>
                                @endif
                            </a>
                            @empty
                            <div class="px-4 py-8 text-center">
                                <svg style="width:32px;height:32px;color:var(--text-secondary);margin:0 auto 8px;opacity:0.4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <p class="text-xs text-white/40">Belum ada notifikasi</p>
                            </div>
                            @endforelse
                        </div>
                        @if($notifs->count() > 0)
                        <div class="px-4 py-2 border-t border-white/5 text-center">
                            <a href="{{ route('notifications.index') }}" class="text-xs text-[var(--cyan)] hover:underline">Lihat Semua Notifikasi</a>
                        </div>
                        @endif
                    </div>
                </div>
                @endauth

                <!-- Language Selector -->
                <div class="relative" id="lang-dropdown-wrapper">
                    <button onclick="toggleLangDropdown()" class="relative flex items-center gap-2 px-3 py-1.5 bg-white/5 border border-white/10 text-sm font-medium text-white/60 rounded-full hover:bg-white/10 hover:border-white/20 transition-all" aria-label="Language">
                        <span class="hidden sm:inline font-medium" id="lang-current">{{ app()->getLocale() === 'id' ? '🇮🇩 Indonesia' : '🇬🇧 English' }}</span>
                        <svg class="w-3.5 h-3.5 text-white/50 transition-transform" id="lang-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="lang-dropdown-menu" class="absolute right-0 top-full mt-2 w-48 opacity-0 invisible scale-95 transition-all duration-200 bg-[var(--bg-card)] border border-white/10 rounded-xl py-2 shadow-2xl shadow-black/40 z-50">
                        <a href="javascript:;" data-lang="id" class="flex items-center gap-3 px-3 py-2 text-sm text-white/70 hover:text-[var(--text)] hover:bg-[var(--bg-card)] transition-all block">{{ __('id') }} 🇮🇩</a>
                        <a href="javascript:;" data-lang="en" class="flex items-center gap-3 px-3 py-2 text-sm text-white/70 hover:text-[var(--text)] hover:bg-[var(--bg-card)] transition-all block">{{ __('en') }} 🇬🇧</a>
                    </div>
                </div>

                <!-- Currency Selector -->
                <div class="relative" id="currency-dropdown-wrapper">
                    <button onclick="toggleCurrencyDropdown()" class="relative flex items-center gap-2 px-3 py-1.5 bg-white/5 border border-white/10 text-sm font-medium text-white/60 rounded-full hover:bg-white/10 hover:border-white/20 transition-all" aria-label="Currency">
                        <span class="hidden sm:inline" id="currency-current">IDR</span>
                        <svg class="w-3.5 h-3.5 text-white/50 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="currency-dropdown-menu" class="absolute right-0 top-full mt-2 w-48 opacity-0 invisible scale-95 transition-all duration-200 bg-[var(--bg-card)] border border-white/10 rounded-xl py-2 shadow-2xl shadow-black/40 z-50">
                        @foreach(config('currency.enabled', ['IDR', 'USD', 'SGD', 'MYR', 'EUR']) as $code)
                        <a href="javascript:;" data-code="$code" class="flex items-center gap-2 px-3 py-1.5 text-sm text-white/70 hover:text-[var(--text)] hover:bg-[var(--bg-card)] transition-all block">
                            <span class="font-medium">$code</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile Language Selector -->
                <button id="mobile-lang-btn" class="hidden lg:inline-flex items-center gap-2 px-3 py-1.5 text-sm text-white/60 rounded" aria-label="Language">
                    <span id="mobile-lang-current" class="hidden sm:inline">🇮🇩</span>
                    <span class="sm:hidden">Bahasa</span>
                </button>

                @auth
                @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="hidden lg:inline-flex items-center gap-2 px-4 py-2 bg-purple-500/10 border border-purple-500/30 text-purple-400 font-medium text-sm rounded-xl hover:bg-purple-500/20 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Admin
                </a>
                @endif
                <!-- User Menu -->
                <div class="hidden lg:block relative" id="user-dropdown-wrapper">
                    <button id="user-dropdown-btn" onclick="toggleUserDropdown()" class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 bg-white/5 border border-white/10 text-white font-medium text-sm rounded-xl hover:bg-white/10 hover:border-white/20 transition-all duration-300">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-[var(--blue)] to-[var(--cyan)] flex items-center justify-center text-white text-xs font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                        <svg class="w-3.5 h-3.5 text-white/50 transition-transform" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="user-dropdown-menu" class="absolute right-0 top-full mt-2 w-56 opacity-0 invisible scale-95 transition-all duration-200 bg-[var(--bg-card)] border border-white/10 rounded-2xl py-2 shadow-2xl shadow-black/40 z-50">
                        @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-purple-400 hover:text-purple-300 hover:bg-purple-500/10 text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Admin Panel
                        </a>
                        <div class="border-t border-white/5 my-1"></div>
                        @endif
                        @if(Auth::user()->store && Auth::user()->store->status === 'active')
                        <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-[var(--cyan)] hover:text-[var(--cyan)] hover:bg-[var(--cyan)]/10 text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Seller Center
                        </a>
                        @elseif(Auth::user()->store && Auth::user()->store->status === 'pending')
                        <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-yellow-400 hover:text-yellow-300 hover:bg-yellow-500/10 text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Toko Pending
                        </a>
                        @elseif(!Auth::user()->store)
                        <a href="{{ route('seller.register') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/80 hover:text-[var(--cyan)] hover:bg-white/5 text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 1 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            Buka Toko
                        </a>
                        @endif
                        <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/80 hover:text-[var(--cyan)] hover:bg-white/5 text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('account.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/80 hover:text-[var(--cyan)] hover:bg-white/5 text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Saya
                        </a>
                        <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/80 hover:text-[var(--cyan)] hover:bg-white/5 text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Pesanan Saya
                        </a>
                        <a href="{{ route('account.addresses') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/80 hover:text-[var(--cyan)] hover:bg-white/5 text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Alamat Saya
                        </a>
                        <div class="border-t border-white/5 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-red-400 hover:bg-red-500/10 text-sm transition-colors w-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <!-- Login -->
                <a href="{{ route('login') }}" class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[var(--blue)] to-[var(--cyan)] text-white font-semibold text-sm rounded-xl hover:shadow-lg hover:shadow-[var(--cyan)]/25 transition-all duration-300 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Login
                </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden p-2.5 text-white/60 hover:text-[var(--cyan)] transition-colors rounded-xl hover:bg-white/5" aria-label="Toggle menu" aria-expanded="false">
                    <svg id="menu-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="close-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="mobile-menu lg:hidden fixed inset-0 z-40 flex flex-col pt-20 px-6 pb-10 overflow-y-auto" style="background:rgba(8,13,24,0.98);backdrop-filter:blur(24px);">
        <div class="flex flex-col gap-1">
            <a href="{{ route('home') }}" class="text-white/90 hover:text-[var(--cyan)] font-medium text-lg py-3.5 border-b border-white/5 transition-colors">Home</a>
            <a href="{{ route('products.index') }}" class="text-white/90 hover:text-[var(--cyan)] font-medium text-lg py-3.5 border-b border-white/5 transition-colors">Semua Produk</a>
            @foreach($navCategories as $navCat)
            <div class="border-b border-white/5">
                <button class="w-full flex items-center justify-between text-white/90 hover:text-[var(--cyan)] font-medium text-lg py-3.5 transition-colors" onclick="toggleSubmenu(this)">
                    {{ $navCat->name }}
                    @if($navCat->children->count() > 0)
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    @endif
                </button>
                @if($navCat->children->count() > 0)
                <div class="submenu hidden pl-4 py-2 space-y-1">
                    <a href="{{ route('products.index', ['category' => $navCat->slug]) }}" class="block text-[var(--cyan)] text-sm py-2 font-semibold">Lihat Semua</a>
                    @foreach($navCat->children->take(8) as $child)
                    <a href="{{ route('products.index', ['category' => $child->slug]) }}" class="block text-white/60 hover:text-[var(--cyan)] text-base py-2 transition-colors">{{ $child->name }}</a>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="mt-8 pt-8 border-t border-white/5 flex flex-col gap-3">
            <button data-mobile-search class="w-full flex items-center justify-center gap-3 px-6 py-3.5 bg-white/5 border border-white/10 text-white font-medium rounded-xl hover:bg-white/10 hover:border-white/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Search Products
            </button>

            @auth
            <a href="{{ route('account.dashboard') }}" class="w-full flex items-center justify-center gap-3 px-6 py-3.5 bg-white/5 border border-white/10 text-white font-medium rounded-xl hover:bg-white/10 hover:border-white/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Akun Saya
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 px-6 py-3.5 border border-red-500/20 text-red-400 font-medium rounded-xl hover:bg-red-500/10 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-3 px-6 py-3.5 bg-gradient-to-r from-[var(--blue)] to-[var(--cyan)] text-white font-semibold rounded-xl hover:shadow-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Login
            </a>
            <a href="{{ route('register') }}" class="w-full flex items-center justify-center gap-3 px-6 py-3.5 bg-white/5 border border-white/10 text-white font-medium rounded-xl hover:bg-white/10 hover:border-white/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 1 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Daftar
            </a>
            @endauth
        </div>
    </div>

    <script>
        function toggleSubmenu(btn) {
            const submenu = btn.nextElementSibling;
            const icon = btn.querySelector('svg');
            submenu.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        function toggleNotifDropdown() {
            var menu = document.getElementById('notif-dropdown-menu');
            var isOpen = !menu.classList.contains('invisible');
            closeAllDropdowns();
            if (!isOpen) {
                menu.classList.remove('invisible', 'opacity-0', 'scale-95');
                menu.classList.add('opacity-100', 'scale-100');
            }
        }

        function toggleUserDropdown() {
            var menu = document.getElementById('user-dropdown-menu');
            var arrow = document.getElementById('dropdown-arrow');
            var isOpen = !menu.classList.contains('invisible');
            closeAllDropdowns();
            if (!isOpen) {
                menu.classList.remove('invisible', 'opacity-0', 'scale-95');
                menu.classList.add('opacity-100', 'scale-100');
                if (arrow) arrow.classList.add('rotate-180');
            }
        }

        function closeAllDropdowns() {
            var nd = document.getElementById('notif-dropdown-menu');
            var ud = document.getElementById('user-dropdown-menu');
            var arrow = document.getElementById('dropdown-arrow');
            if (nd) { nd.classList.add('invisible', 'opacity-0', 'scale-95'); nd.classList.remove('opacity-100', 'scale-100'); }
            if (ud) { ud.classList.add('invisible', 'opacity-0', 'scale-95'); ud.classList.remove('opacity-100', 'scale-100'); }
            if (arrow) arrow.classList.remove('rotate-180');
        }

        document.addEventListener('click', function(e) {
            var nd = document.getElementById('notif-dropdown-wrapper');
            var ud = document.getElementById('user-dropdown-wrapper');
            if (nd && !nd.contains(e.target) && ud && !ud.contains(e.target)) closeAllDropdowns();
            else if (nd && !nd.contains(e.target)) closeAllDropdowns();
            else if (ud && !ud.contains(e.target)) closeAllDropdowns();
        });
    </script>
</nav>
