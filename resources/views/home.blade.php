@extends('layouts.app')
@section('content')

{{-- ═══════════════════════ HERO ═══════════════════════ --}}
<section style="height:auto;display:flex;align-items:center;position:relative;overflow:visible;padding-top:80px;padding-bottom:60px;">
    <div style="position:absolute;inset:0;pointer-events:none;overflow:hidden;">
        <div style="position:absolute;top:10%;left:5%;width:600px;height:600px;background:radial-gradient(circle,rgba(0,229,255,0.07),transparent 70%);border-radius:50%;filter:blur(60px);"></div>
        <div style="position:absolute;bottom:10%;right:8%;width:500px;height:500px;background:radial-gradient(circle,rgba(41,121,255,0.06),transparent 70%);border-radius:50%;filter:blur(60px);"></div>
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:900px;height:900px;background:radial-gradient(circle,rgba(0,229,255,0.03),transparent 60%);border-radius:50%;"></div>
    </div>
    <div class="container" style="position:relative;z-index:2;width:100%;">
        <div style="display:grid;grid-template-columns:minmax(0, 1.1fr) minmax(320px, .9fr);gap:48px;align-items:center;overflow:visible;" class="hero-grid">
            <div style="max-width:600px;">
                <div style="display:inline-flex;align-items:center;gap:8px;padding:8px 18px;background:rgba(0,229,255,0.06);border:1px solid rgba(0,229,255,0.12);border-radius:100px;margin-bottom:32px;">
                    <span style="position:relative;width:8px;height:8px;"><span style="position:absolute;inset:0;border-radius:50%;background:var(--cyan);animation:ping 2s ease-in-out infinite;"></span><span style="position:relative;width:8px;height:8px;border-radius:50%;background:var(--cyan);display:block;"></span></span>
                    <span style="color:var(--cyan);font-size:13px;font-weight:600;letter-spacing:.5px;">Flash Sale — Up to 70% OFF</span>
                </div>
                <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(36px,5.5vw,48px);font-weight:800;line-height:1.1;margin:0 0 24px;letter-spacing:-1.5px;">
                    <span class="gx-text">Smart Tech,</span><br>
                    <span style="color:var(--text);">Better Life.</span>
                </h1>
                <p style="color:var(--text-secondary);font-size:16px;line-height:1.6;margin:0 0 32px;max-width:480px;">
                    Discover the latest smartphones, laptops, and gadgets with premium quality at competitive prices.
                </p>
                <div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:40px;">
                    <a href="#products" class="btn-glow">Shop Now <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
                    <a href="#categories" class="btn-ghost">Explore Categories</a>
                </div>
<div style="display:flex;gap:24px;">
                    <div><div style="font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:800;color:var(--text);">50K+</div><div style="color:var(--text-secondary);font-size:11px;margin-top:2px;">Products</div></div>
                    <div style="width:1px;background:var(--border-light);margin:0 8px;"></div>
                    <div><div style="font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:800;color:var(--text);">100K+</div><div style="color:var(--text-secondary);font-size:11px;margin-top:2px;">Customers</div></div>
                    <div style="width:1px;background:var(--border-light);margin:0 8px;"></div>
                    <div><div style="font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:800;color:var(--text);">4.9</div><div style="color:var(--text-secondary);font-size:11px;margin-top:2px;">Rating</div></div>
                </div>
                <style>
                    @media (max-width: 390px) {
                        .hero-grid > div:first-child { gap: 32px; }
                        .hero-grid > div:first-child .cdigit-label { display: none; }
                        .hero-grid > div:first-child div[style*="margin:0 8px"] { display: none; }
                        .hero-grid > div:last-child { max-width: 100%; margin-left: auto; margin-right: auto; }
                    }
                </style>
            </div>
            <div class="hero-card-wrap" style="display:flex;justify-center;position:relative;padding:48px 0;">
                    <div class="floating" style="max-width:420px;width:100%;margin-left:auto;margin-right:auto;position:relative;background:var(--bg-card);border:1px solid var(--border);border-radius:20px;padding:24px;box-shadow:0 12px 32px rgba(0,0,0,0.3);">
                    <div style="position:absolute;inset:-12px;background:linear-gradient(135deg,rgba(0,229,255,0.12),rgba(41,121,255,0.08));border-radius:36px;filter:blur(20px);transform:rotate(3deg);"></div>
                    <div style="position:relative;background:var(--bg-glass);backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,0.08);border-radius:28px;padding:36px;box-shadow:0 32px 64px rgba(0,0,0,0.4);">
                        <div style="text-align:center;margin-bottom:20px;">
                            <div style="font-size:11px;font-weight:700;color:var(--cyan);letter-spacing:2px;text-transform:uppercase;margin-bottom:8px;">Featured Product</div>
                            <a href="{{ route('products.show', 'iphone-16-pro-max') }}" style="text-decoration:none;color:inherit;"><div style="font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:700;color:var(--text);">iPhone 16 Pro Max</div></a>
                        </div>
                        <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:20px;padding:24px;display:flex;align-items:center;justify-content:center;height:auto;margin-bottom:24px;">
                            <img src="{{ asset('images/products/iphone-16-pro-max.jpg') }}" alt="iPhone 16 Pro Max" style="height:auto;object-fit:contain;width:min(100%, 250px);" loading="lazy" onerror="this.src='https://placehold.co/400x300/0e1425/00e5ff.png?text=iPhone+16+Pro+Max&font=roboto'">
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:flex-end;">
                            <div><div style="font-size:14px;color:var(--text-secondary);text-decoration:line-through;">Rp 19.999.000</div><div style="font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:800;color:var(--cyan);">Rp 15.999.000</div></div>
                            <div style="padding:8px 16px;border-radius:10px;background:linear-gradient(135deg,#ff3d00,#ff6d00);color:#fff;font-size:14px;font-weight:800;box-shadow:0 4px 16px rgba(255,61,0,0.3);">-20%</div>
                        </div>
                        <a href="{{ route('products.show', 'iphone-16-pro-max') }}" class="add-cart-btn" style="display:block;text-align:center;margin-top:20px;text-decoration:none;">View Product →</a>
                    </div>
                </div>
            </div>
</div>
        </div>
    </div>
</section>

 <style>
    @media (min-width: 1025px) {
        .adv-grid { grid-template-columns: repeat(6,1fr); gap: 16px; }
        .prod-grid { grid-template-columns: repeat(4,1fr); gap: 20px; }
        .cat-grid { grid-template-columns: repeat(6,1fr); gap: 16px; }
        .brand-grid { grid-template-columns: repeat(8,1fr); gap: 12px; }
        .products-grid-4 { grid-template-columns: repeat(4,1fr); gap: 24px; }
        .adv-grid-2 { grid-template-columns: repeat(4,1fr); gap: 20px; }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .adv-grid { grid-template-columns: repeat(4,1fr); gap: 14px; }
        .prod-grid { grid-template-columns: repeat(3,1fr); gap: 16px; }
        .cat-grid { grid-template-columns: repeat(4,1fr); gap: 14px; }
        .brand-grid { grid-template-columns: repeat(6,1fr); gap: 14px; }
        .products-grid-4 { grid-template-columns: repeat(3,1fr); gap: 20px; }
        .adv-grid-2 { grid-template-columns: repeat(3,1fr); gap: 16px; }
    }

    @media (max-width: 767px) {
        .adv-grid, .cat-grid, .prod-grid, .brand-grid, .products-grid-4, .adv-grid-2 {
            grid-template-columns: repeat(2,1fr) !important; gap: 12px !important;
        }
    }
</style>

{{-- ═══════════════════════ ADVANTAGES BAR ═══════════════════════ --}}
<section class="sec-sm" style="border-top:1px solid var(--border);border-bottom:1px solid var(--border);background:var(--bg-card);">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px;" class="adv-grid">
            @php $advantages = [
                ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','title'=>'100% Original','sub'=>'Official Warranty'],
                ['icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','title'=>'Best Price','sub'=>'Price Guarantee'],
                ['icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4','title'=>'Free Shipping','sub'=>'Min. Purchase 500K'],
                ['icon'=>'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z','title'=>'24/7 Support','sub'=>'Always Here For You'],
            ]; @endphp
            @foreach($advantages as $a)
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,rgba(0,229,255,0.08),rgba(41,121,255,0.06));border:1px solid rgba(0,229,255,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:22px;height:22px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $a['icon'] }}"/></svg>
                </div>
                <div><div style="font-weight:700;font-size:14px;color:var(--text);">{{ $a['title'] }}</div><div style="color:var(--text-secondary);font-size:12px;">{{ $a['sub'] }}</div></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════ FLASH SALE ═══════════════════════ --}}
<section id="flash-sale" class="sec" style="background:var(--bg-deep);">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:24px;margin-bottom:48px;">
            <div class="sh" style="text-align:left;margin-bottom:0;">
                <div class="sh-badge" style="background:rgba(255,61,0,0.08);border-color:rgba(255,61,0,0.15);color:#ff5252;">
                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/></svg>
                    Flash Sale
                </div>
                <h2 style="font-family:'Space Grotesk',sans-serif;font-size:36px;font-weight:800;margin:0;">Today's Flash Deals</h2>
            </div>
            <div style="display:flex;align-items:center;gap:8px;" class="home-countdown">
                <span style="color:var(--text-secondary);font-size:12px;margin-right:4px;">Ends in:</span>
                <div class="cdigit"><div id="cd-h" class="cdigit-num">00</div><div class="cdigit-label" style="font-size:10px;">Jam</div></div>
                <span class="cdot" style="font-size:12px;">:</span>
                <div class="cdigit"><div id="cd-m" class="cdigit-num">00</div><div class="cdigit-label" style="font-size:10px;">Menit</div></div>
                <span class="cdot" style="font-size:12px;">:</span>
                <div class="cdigit"><div id="cd-s" class="cdigit-num">00</div><div class="cdigit-label" style="font-size:10px;">Detik</div></div>
            </div>
            <style>
                @media (max-width: 390px) {
                    .home-countdown { flex-direction: column; align-items: flex-start; gap: 4px; }
                    .home-countdown .cdot { display: none; }
                }
            </style>
        </div>
        @if($flashSaleProducts->count())
        <div class="fs-scroll scrollbar-hide">
            @foreach($flashSaleProducts as $product)
            @include('partials.product-card', ['product' => $product, 'variant' => 'flash'])
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:64px 0;color:var(--text-secondary);">No flash sale products right now.</div>
        @endif
        <div style="text-align:center;margin-top:40px;">
            <a href="{{ route('products.index') }}" class="btn-ghost" style="font-size:14px;padding:14px 32px;">View All Flash Sales <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
        </div>
    </div>
</section>

{{-- ═══════════════════════ PROMO BANNER ═══════════════════════ --}}
<section class="sec" style="background:var(--bg-card);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container">
        <div class="promo-wrap fade-up" style="padding:80px 72px;position:relative;overflow:visible;">
            <div class="promo-glow-1"></div>
            <div class="promo-glow-2"></div>
            <div style="position:relative;z-index:2;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;" class="promo-grid">
                <div>
                    <div style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:rgba(255,255,255,0.08);border-radius:100px;font-size:13px;font-weight:600;color:rgba(255,255,255,0.9);backdrop-filter:blur(8px);margin-bottom:24px;">Special Promo This Month</div>
                    <h2 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(32px,4vw,52px);font-weight:800;color:#fff;line-height:1.1;margin:0 0 20px;">Cashback Up To<br><span style="color:#ffd740;">Rp 2.000.000</span></h2>
                    <p style="color:rgba(255,255,255,0.65);font-size:17px;line-height:1.65;margin:0 0 36px;max-width:440px;">Get massive cashback on smartphones and gadgets. Limited time offer — don't miss out!</p>
                    <a href="{{ route('products.index') }}" style="display:inline-flex;align-items:center;gap:10px;padding:16px 36px;background:#fff;color:#0d1b3e;font-weight:800;font-size:15px;border-radius:14px;text-decoration:none;transition:all .3s ease;box-shadow:0 8px 32px rgba(0,0,0,0.2);">Claim Promo <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
                </div>
                <div style="display:flex;justify-content:center;">
                    <div style="width:280px;height:280px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:32px;backdrop-filter:blur(12px);display:flex;align-items:center;justify-content:center;">
                        <div style="text-align:center;">
                            <div style="font-family:'Space Grotesk',sans-serif;font-size:80px;font-weight:800;color:#fff;line-height:1;">70%</div>
                            <div style="color:rgba(255,255,255,0.6);font-size:16px;font-weight:600;letter-spacing:2px;margin-top:8px;">MAX DISCOUNT</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════ FEATURED PRODUCTS ═══════════════════════ --}}
<section id="products" class="sec" style="background:var(--bg-deep);">
    <div class="container">
        <div class="sh fade-up">
            <div class="sh-badge">Our Picks</div>
            <h2>Featured Products</h2>
            <p>Handpicked selections for your tech needs</p>
        </div>
        @if($featuredProducts->count())
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;" class="prod-grid">
            @foreach($featuredProducts as $product)
            @include('partials.product-card', ['product' => $product, 'variant' => 'featured'])
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:64px 0;color:var(--text-secondary);">No featured products right now.</div>
        @endif
        <div style="text-align:center;margin-top:48px;">
            <a href="{{ route('products.index') }}" class="btn-glow" style="font-size:14px;">View All Products <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
        </div>
    </div>
</section>

{{-- ═══════════════════════ SHOP BY CATEGORY ═══════════════════════ --}}
<section id="categories" class="sec" style="background:var(--bg-card);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container">
        <div class="sh fade-up">
            <div class="sh-badge">Browse</div>
            <h2>Shop by Category</h2>
            <p>Explore our wide range of product categories</p>
        </div>
        @php
        $gradients = [
            'linear-gradient(135deg,rgba(0,229,255,0.12),rgba(41,121,255,0.08))',
            'linear-gradient(135deg,rgba(41,121,255,0.12),rgba(130,177,255,0.08))',
            'linear-gradient(135deg,rgba(224,64,251,0.12),rgba(41,121,255,0.08))',
            'linear-gradient(135deg,rgba(0,229,255,0.1),rgba(0,184,212,0.06))',
            'linear-gradient(135deg,rgba(255,109,0,0.12),rgba(255,61,0,0.06))',
            'linear-gradient(135deg,rgba(34,197,94,0.12),rgba(0,184,212,0.06))',
        ];
        @endphp
        <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:16px;" class="cat-grid">
            @foreach($categories->take(18) as $index => $category)
            @php $grad = $gradients[$index % count($gradients)]; @endphp
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="ccard fade-up">
                <div class="ccard-icon" style="background:{{ $grad }};border:1px solid rgba(255,255,255,0.05);">
                    <svg style="width:28px;height:28px;color:var(--text)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($category->icon)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $category->icon }}"/>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        @endif
                    </svg>
                </div>
                <h4>{{ $category->name }}</h4>
                <span>{{ $category->total_products_count ?? $category->products_count }} products</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════ BRANDS ═══════════════════════ --}}
<section class="sec" style="background:var(--bg-deep);">
    <div class="container">
        <div class="sh fade-up">
            <div class="sh-badge">Trusted Brands</div>
            <h2>Brands We Carry</h2>
            <p>100% original products from world-class manufacturers</p>
        </div>
        @if($brands->count())
        <div style="display:grid;grid-template-columns:repeat({{ min($brands->count(), 8) }},1fr);gap:16px;" class="brand-grid">
            @foreach($brands as $brand)
            <a href="{{ route('products.index', ['brand' => $brand->slug]) }}" class="blogo fade-up">
                <div class="blogo-letter">{{ substr($brand->name, 0, 1) }}</div>
                <span>{{ $brand->name }}</span>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════ NEW ARRIVALS ═══════════════════════ --}}
<section class="sec" style="background:var(--bg-card);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container">
        <div class="sh fade-up">
            <div class="sh-badge" style="background:rgba(34,197,94,0.08);border-color:rgba(34,197,94,0.15);color:#4ade80;">New Arrivals</div>
            <h2>Just Dropped</h2>
            <p>The latest additions to our ever-growing collection</p>
        </div>
        @if($newProducts->count())
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;" class="prod-grid">
            @foreach($newProducts as $product)
            @include('partials.product-card', ['product' => $product, 'variant' => 'new'])
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:64px 0;color:var(--text-secondary);">No new products yet.</div>
        @endif
    </div>
</section>

{{-- ═══════════════════════ TRENDING PRODUCTS ═══════════════════════ --}}
@if(!empty($trendingProducts) && count($trendingProducts) > 0)
<section class="sec" id="trending" style="padding:100px 0;">
    <div class="container">
        <div class="fade-up" style="text-align:center;margin-bottom:56px;">
            <div class="sh-badge">🔥 Trending</div>
            <h2>Produk Populer Hari Ini</h2>
            <p>Produk yang paling banyak dilihat dan dibeli oleh pelanggan kami</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px;" class="products-grid-4">
            @foreach($trendingProducts as $index => $product)
            @php
                $effectivePrice = $product['sale_price'] && $product['sale_price'] < $product['price']
                    ? $product['sale_price'] : $product['price'];
                $discount = $product['sale_price'] && $product['sale_price'] < $product['price']
                    ? round(($product['price'] - $product['sale_price']) / $product['price'] * 100) : 0;
            @endphp
            <a href="{{ route('products.show', $product['slug']) }}" class="product-card fade-up" style="text-decoration:none;color:inherit;animation-delay:{{ $index * 0.05 }}s;">
                <div class="pc-image">
                    @if($product['image'])
                    <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}" loading="lazy">
                    @else
                    <img src="{{ asset('images/products/no-image.png') }}" alt="Product" loading="lazy">
                    @endif
                    @if($discount > 0)
                    <div class="pc-badge" style="background:linear-gradient(135deg,#ff3d00,#ff6d00);">-{{ $discount }}%</div>
                    @endif
                </div>
                <div class="pc-info">
                    <div class="pc-brand">{{ $product['brand'] ?? '-' }}</div>
                    <div class="pc-name">{{ $product['name'] }}</div>
                    <div class="pc-price">
                        @if($discount > 0)
                        <span style="text-decoration:line-through;color:var(--text-secondary);font-size:12px;margin-right:6px;">Rp {{ number_format($product['price'], 0, ',', '.') }}</span>
                        @endif
                        <span style="color:var(--cyan);">Rp {{ number_format($effectivePrice, 0, ',', '.') }}</span>
                    </div>
                    @if(!empty($product['average_rating']))
                    <div class="pc-rating">
                        <span style="color:#fbbf24;">★</span> {{ number_format($product['average_rating'], 1) }}
                        <span style="color:var(--text-secondary);">({{ $product['reviews_count'] ?? 0 }})</span>
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════ RECENTLY VIEWED ═══════════════════════ --}}
@if(!empty($recentlyViewed) && count($recentlyViewed) > 0)
<section class="sec" id="recently-viewed" style="padding:60px 0;">
    <div class="container">
        <div class="fade-up" style="margin-bottom:40px;">
            <div class="sh-badge">👁️ Recently Viewed</div>
            <h2>Yang Baru Anda Lihat</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;" class="products-grid-4">
            @foreach(array_slice($recentlyViewed, 0, 4) as $product)
            @php
                $effectivePrice = $product['sale_price'] && $product['sale_price'] < $product['price']
                    ? $product['sale_price'] : $product['price'];
            @endphp
            <a href="{{ route('products.show', $product['slug']) }}" class="product-card fade-up" style="text-decoration:none;color:inherit;">
                <div class="pc-image">
                    @if($product['image'])
                    <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}" loading="lazy">
                    @else
                    <img src="{{ asset('images/products/no-image.png') }}" alt="Product" loading="lazy">
                    @endif
                </div>
                <div class="pc-info">
                    <div class="pc-brand">{{ $product['brand'] ?? '-' }}</div>
                    <div class="pc-name">{{ $product['name'] }}</div>
                    <div class="pc-price" style="color:var(--cyan);">Rp {{ number_format($effectivePrice, 0, ',', '.') }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════ WHY CHOOSE US ═══════════════════════ --}}
<section class="sec" style="background:var(--bg-deep);">
    <div class="container">
        <div class="sh fade-up">
            <div class="sh-badge">Why Us</div>
            <h2>Why Choose Winky Store</h2>
            <p>We are committed to delivering the best shopping experience</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;" class="adv-grid-2">
            @php $whyUs = [
                ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','color'=>'var(--cyan)','bg'=>'rgba(0,229,255,0.1)','border'=>'rgba(0,229,255,0.1)','title'=>'100% Authentic','desc'=>'All products guaranteed genuine with official warranty from trusted distributors.'],
                ['icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'#4ade80','bg'=>'rgba(34,197,94,0.1)','border'=>'rgba(34,197,94,0.1)','title'=>'Best Price Guaranteed','desc'=>'We guarantee the lowest price for every product. Compare and see for yourself.'],
                ['icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4','color'=>'#82b1ff','bg'=>'rgba(130,177,255,0.1)','border'=>'rgba(130,177,255,0.1)','title'=>'Free Shipping','desc'=>'Free delivery on orders over Rp 500,000 across Indonesia.'],
                ['icon'=>'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z','color'=>'var(--magenta)','bg'=>'rgba(224,64,251,0.1)','border'=>'rgba(224,64,251,0.1)','title'=>'24/7 Customer Support','desc'=>'Our support team is ready to help you anytime, every day of the week.'],
            ]; @endphp
            @foreach($whyUs as $w)
            <div class="adv-card fade-up">
                <div class="adv-icon" style="background:linear-gradient(135deg,{{ $w['bg'] }},{{ str_replace('0.1','0.06',$w['bg']) }});border:1px solid {{ $w['border'] }};">
                    <svg style="width:28px;height:28px;color:{{ $w['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $w['icon'] }}"/></svg>
                </div>
                <h3>{{ $w['title'] }}</h3>
                <p>{{ $w['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════ NEWSLETTER ═══════════════════════ --}}
<section class="sec" style="background:var(--bg-card);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container">
        <div class="fade-up" style="background:var(--bg-deep);border:1px solid var(--border);border-radius:32px;padding:80px 72px;text-align:center;position:relative;overflow:visible;">
            <div style="position:absolute;top:-80px;right:-80px;width:300px;height:300px;background:radial-gradient(circle,rgba(0,229,255,0.06),transparent 70%);border-radius:50%;pointer-events:none;"></div>
            <div style="position:absolute;bottom:-60px;left:-60px;width:240px;height:240px;background:radial-gradient(circle,rgba(224,64,251,0.04),transparent 70%);border-radius:50%;pointer-events:none;"></div>
            <div style="position:relative;z-index:2;">
                <div style="width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,rgba(0,229,255,0.1),rgba(41,121,255,0.08));border:1px solid rgba(0,229,255,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 28px;">
                    <svg style="width:28px;height:28px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h2 style="font-family:'Space Grotesk',sans-serif;font-size:36px;font-weight:800;margin:0 0 14px;">Stay In The Loop</h2>
                <p style="color:var(--text-secondary);font-size:16px;max-width:480px;margin:0 auto 36px;line-height:1.6;">Subscribe to our newsletter and be the first to know about exclusive deals, new arrivals, and flash sales.</p>
                <div style="display:flex;gap:12px;max-width:480px;margin:0 auto;">
                    <input type="email" placeholder="Enter your email address" class="nl-input" aria-label="Email address">
                    <button class="btn-glow" style="padding:16px 28px;font-size:14px;white-space:nowrap;">Subscribe</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
