@extends('layouts.app')
@section('content')

<section style="padding-top:100px;padding-bottom:80px;">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-secondary);margin-bottom:32px;flex-wrap:wrap;">
            <a href="{{ route('home') }}" style="color:var(--text-secondary);text-decoration:none;transition:color .2s;" onmouseover="this.style.color='var(--cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Home</a>
            <span style="opacity:0.4;">/</span>
            <a href="{{ route('products.index') }}" style="color:var(--text-secondary);text-decoration:none;transition:color .2s;" onmouseover="this.style.color='var(--cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Products</a>
            <span style="opacity:0.4;">/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" style="color:var(--text-secondary);text-decoration:none;transition:color .2s;" onmouseover="this.style.color='var(--cyan)'" onmouseout="this.style.color='var(--text-secondary)'">{{ $product->category->name }}</a>
            <span style="opacity:0.4;">/</span>
            <span style="color:var(--text);font-weight:500;">{{ $product->name }}</span>
        </nav>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start;" id="detail-grid">

            {{-- ═══ Gallery ═══ --}}
            <div class="detail-gallery">
                @php
                    $hasImage = $product->image && file_exists(public_path('images/products/' . $product->image));
                    $allImages = collect();
                    if($hasImage) $allImages->push($product->image);
                    foreach($product->images as $img) {
                        if(!in_array($img->image, $allImages->toArray())) $allImages->push($img->image);
                    }
                    if($allImages->isEmpty()) $allImages->push('no-image.png');
                @endphp

                <div class="detail-main-img" id="main-img-container">
                    @if($product->getDiscountPercent())
                    <div style="position:absolute;top:16px;left:16px;z-index:3;padding:6px 14px;border-radius:10px;background:linear-gradient(135deg,#ff3d00,#ff6d00);color:#fff;font-size:14px;font-weight:800;box-shadow:0 4px 16px rgba(255,61,0,0.35);">-{{ $product->getDiscountPercent() }}%</div>
                    @endif
                    <img src="{{ asset('images/products/' . $allImages->first()) }}" alt="{{ $product->name }}" id="main-detail-img">
                </div>

                @if($allImages->count() > 1)
                <div class="detail-thumbs" id="thumb-list">
                    @foreach($allImages as $idx => $img)
                    <button class="detail-thumb {{ $idx === 0 ? 'active' : '' }}" onclick="switchImg('{{ asset('images/products/'.$img) }}', this)" aria-label="Image {{ $idx + 1 }}">
                        <img src="{{ asset('images/products/' . $img) }}" alt="">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- ═══ Info ═══ --}}
            <div class="detail-info">
                {{-- Brand + Category --}}
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <a href="{{ route('products.index', ['brand' => $product->brand->slug]) }}" class="detail-brand">{{ $product->brand->name }}</a>
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" style="font-size:13px;color:var(--text-secondary);text-decoration:none;transition:color .2s;" onmouseover="this.style.color='var(--cyan)'" onmouseout="this.style.color='var(--text-secondary)'">{{ $product->category->name }}</a>
                </div>

                {{-- Name --}}
                <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(24px,3vw,32px);font-weight:800;color:var(--text);margin:0;line-height:1.2;">{{ $product->name }}</h1>

                {{-- Rating --}}
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="display:flex;gap:2px;">
                        @for($i = 0; $i < 5; $i++)
                        <svg style="width:16px;height:16px;fill:#ffc107;" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <span style="font-size:13px;color:var(--text-secondary);">No reviews yet</span>
                </div>

                {{-- Price --}}
                <div class="detail-price-box">
                    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                        @if($product->sale_price)
                        <div class="detail-price" id="display-price">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</div>
                        <div class="detail-discount">-{{ $product->getDiscountPercent() }}%</div>
                        @else
                        <div class="detail-price" id="display-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @endif
                    </div>
                    @if($product->sale_price)
                    <div class="detail-old-price" style="margin-top:4px;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    @endif
                </div>

                {{-- SKU & Stock --}}
                <div style="display:flex;gap:24px;font-size:13px;">
                    <div><span style="color:var(--text-secondary);">SKU:</span> <span style="color:var(--text);font-weight:500;">{{ $product->sku }}</span></div>
                    <div>
                        <span style="color:var(--text-secondary);">Stock:</span>
                        @if($product->stock > 0)
                        <span style="color:#4ade80;font-weight:600;" id="display-stock">{{ $product->stock }} units</span>
                        @else
                        <span style="color:#f87171;font-weight:600;">Out of Stock</span>
                        @endif
                    </div>
                </div>

                {{-- Short Description --}}
                @if($product->short_description)
                <p style="color:var(--text-secondary);font-size:14px;line-height:1.65;margin:0;">{{ $product->short_description }}</p>
                @endif

                {{-- Variants --}}
                @if($product->variants->count())
                <div id="variants-section">
                    @php
                    $grouped = $product->variants->groupBy(function($v) {
                        $attrs = $v->attributes ?? [];
                        $keys = array_keys($attrs);
                        return $keys[0] ?? 'default';
                    });
                    @endphp

                    @foreach($grouped as $attrName => $variants)
                    <div class="variant-group">
                        <div class="variant-label">{{ ucfirst($attrName) }}</div>
                        <div style="display:flex;flex-wrap:wrap;gap:8px;">
                            @foreach($variants as $variant)
                            <button type="button"
                                class="variant-btn {{ $loop->first ? 'active' : '' }} {{ $variant->stock <= 0 ? 'out' : '' }}"
                                data-variant-id="{{ $variant->id }}"
                                data-variant-price="{{ $variant->price }}"
                                data-variant-stock="{{ $variant->stock }}"
                                data-variant-sku="{{ $variant->sku }}"
                                onclick="selectVariant(this)">
                                {{ $variant->attributes[$attrName] ?? $variant->name }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Quantity + CTA --}}
                <form method="POST" action="{{ route('cart.store') }}" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="product_variant_id" id="selected-variant-id" value="">
                    <input type="hidden" name="quantity" id="qty-input" value="1">

                    <div style="margin-bottom:16px;">
                        <div class="variant-label">Quantity</div>
                        <div class="qty-box">
                            <button type="button" onclick="changeQty(-1)">−</button>
                            <span class="qty-val" id="qty-display">1</span>
                            <button type="button" onclick="changeQty(1)">+</button>
                        </div>
                    </div>

                    <div class="cta-row">
                        <button type="submit" class="cta-cart" {{ $product->stock <= 0 ? 'disabled style="opacity:0.4;cursor:not-allowed;"' : '' }}>
                            <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                        </button>
                        @if($product->stock > 0)
                        <button type="button" class="cta-buy" onclick="buyNow()">
                            <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Buy Now
                        </button>
                        @endif
                    </div>
                </form>

                {{-- Wishlist --}}
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    @auth
                    @php $isWishlisted = Auth::user()->wishlists()->where('product_id', $product->id)->exists(); @endphp
                    <form method="POST" action="{{ route('wishlist.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;transition:all .25s;border:1px solid {{ $isWishlisted ? 'rgba(239,68,68,0.4)' : 'var(--border-light)' }};background:{{ $isWishlisted ? 'rgba(239,68,68,0.08)' : 'transparent' }};color:{{ $isWishlisted ? '#f87171' : 'var(--text-secondary)' }};">
                            <svg style="width:16px;height:16px" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            {{ $isWishlisted ? 'Wishlisted' : 'Wishlist' }}
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:10px;font-size:13px;font-weight:600;border:1px solid var(--border-light);background:transparent;color:var(--text-secondary);text-decoration:none;transition:all .25s;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)';this.style.color='var(--cyan)'" onmouseout="this.style.borderColor='var(--border-light)';this.style.color='var(--text-secondary)'">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Login for Wishlist
                    </a>
                    @endauth

                    <button class="share-btn" onclick="shareProduct()">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        Share
                    </button>
                </div>

                {{-- Trust Badges --}}
                <div class="trust-grid">
                    <div class="trust-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>100% Original</span>
                    </div>
                    <div class="trust-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Official Warranty</span>
                    </div>
                    <div class="trust-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        <span>Free Shipping 500K+</span>
                    </div>
                    <div class="trust-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>7-Day Returns</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ Tabs: Description / Specs ═══ --}}
        <div class="detail-tabs" style="margin-top:56px;">
            <div class="tab-btns">
                <button class="tab-btn active" onclick="switchTab('desc', this)">Description</button>
                @if($product->variants->count())
                <button class="tab-btn" onclick="switchTab('specs', this)">Specifications</button>
                @endif
            </div>

            <div id="tab-desc" class="tab-content active">
                @if($product->description)
                <div style="color:var(--text-secondary);font-size:14px;line-height:1.8;max-width:800px;">{!! nl2br(e($product->description)) !!}</div>
                @else
                <p style="color:var(--text-secondary);font-size:14px;">No description available for this product.</p>
                @endif
            </div>

            @if($product->variants->count())
            <div id="tab-specs" class="tab-content">
                <table class="spec-table" style="max-width:600px;">
                    <tr><td>Brand</td><td>{{ $product->brand->name }}</td></tr>
                    <tr><td>Category</td><td>{{ $product->category->name }}</td></tr>
                    <tr><td>SKU</td><td>{{ $product->sku }}</td></tr>
                    @foreach($product->variants->first()->attributes ?? [] as $key => $val)
                    <tr><td>{{ ucfirst($key) }}</td><td>{{ $val }}</td></tr>
                    @endforeach
                    <tr><td>Stock</td><td>{{ $product->stock }} units</td></tr>
                </table>
            </div>
            @endif
        </div>

        {{-- ═══ Related Products ═══ --}}
        @if(!empty($similarProducts) && count($similarProducts) > 0)
        <div style="margin-top:64px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:32px;">
                <h2 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(24px,3vw,32px);font-weight:800;color:var(--text);margin:0;">Produk Serupa</h2>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" style="font-size:13px;color:var(--cyan);text-decoration:none;font-weight:600;transition:opacity .2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">View All →</a>
            </div>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;" id="related-grid">
                @foreach($similarProducts as $rp)
                <div class="cat-card fade-up">
                    <div class="card-img-wrap" style="position:relative;">
                        <a href="{{ route('products.show', $rp['slug']) }}" style="display:block;width:100%;">
                            @if($rp['image'])
                            <img src="{{ asset('storage/' . $rp['image']) }}" alt="{{ $rp['name'] }}" style="width:100%;height:180px;object-fit:contain;padding:12px;" loading="lazy">
                            @else
                            <img src="{{ asset('images/products/no-image.png') }}" alt="Product" style="width:100%;height:180px;object-fit:contain;padding:12px;" loading="lazy">
                            @endif
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="card-brand">{{ $rp['brand'] ?? '-' }}</div>
                        <a href="{{ route('products.show', $rp['slug']) }}" style="text-decoration:none;color:inherit;">
                            <div class="card-name">{{ $rp['name'] }}</div>
                        </a>
                        @if(!empty($rp['average_rating']))
                        <div style="font-size:12px;color:#fbbf24;">★ {{ number_format($rp['average_rating'], 1) }}</div>
                        @endif
                        <div class="card-price">Rp {{ number_format($rp['effective_price'] ?? $rp['price'], 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ═══ Frequently Bought Together ═══ --}}
        @if(!empty($frequentlyBought) && count($frequentlyBought) > 0)
        <div style="margin-top:64px;">
            <h2 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(20px,2.5vw,28px);font-weight:800;color:var(--text);margin:0 0 24px;">Sering Dibeli Bersamaan</h2>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;" id="related-grid">
                @foreach($frequentlyBought as $fb)
                <div class="cat-card fade-up">
                    <div class="card-img-wrap">
                        <a href="{{ route('products.show', $fb['slug']) }}" style="display:block;width:100%;">
                            @if($fb['image'])
                            <img src="{{ asset('storage/' . $fb['image']) }}" alt="{{ $fb['name'] }}" style="width:100%;height:180px;object-fit:contain;padding:12px;" loading="lazy">
                            @else
                            <img src="{{ asset('images/products/no-image.png') }}" alt="Product" style="width:100%;height:180px;object-fit:contain;padding:12px;" loading="lazy">
                            @endif
                        </a>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('products.show', $fb['slug']) }}" style="text-decoration:none;color:inherit;">
                            <div class="card-name">{{ $fb['name'] }}</div>
                        </a>
                        <div class="card-price">Rp {{ number_format($fb['effective_price'] ?? $fb['price'], 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ═══ AI Assistant ═══ --}}
        <div style="margin-top:64px;padding:32px;background:linear-gradient(135deg,rgba(0,229,255,0.05),rgba(41,121,255,0.05));border:1px solid rgba(0,229,255,0.12);border-radius:20px;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--cyan),var(--blue));display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                </div>
                <div>
                    <div style="font-weight:700;color:var(--text);font-size:16px;">Tanya WINKY AI tentang produk ini</div>
                    <div style="font-size:12px;color:var(--text-secondary);">Dapatkan rekomendasi dan informasi lengkap</div>
                </div>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                <button onclick="askAIAboutProduct('Apakah {{ addslashes($product->name) }} cocok untuk saya?')" class="ai-suggest-btn">🤔 Cocok untuk saya?</button>
                <button onclick="askAIAboutProduct('Bandingkan {{ addslashes($product->name) }} dengan produk lain')" class="ai-suggest-btn">📊 Bandingkan produk</button>
                <button onclick="askAIAboutProduct('Apa kelebihan {{ addslashes($product->name) }}?')" class="ai-suggest-btn">✨ Apa kelebihannya?</button>
                <button onclick="askAIAboutProduct('Produk alternatif {{ addslashes($product->category->name ?? '') }}')" class="ai-suggest-btn">🔄 Alternatif lain</button>
            </div>
        </div>

        {{-- ═══ Compare Button ═══ --}}
        <div style="margin-top:24px;text-align:center;">
            <button onclick="addToCompare({{ $product->id }})" style="padding:12px 24px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;color:var(--text);font-size:13px;font-weight:600;cursor:pointer;transition:all .25s;display:inline-flex;align-items:center;gap:8px;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)'" onmouseout="this.style.borderColor='var(--border)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Bandingkan Produk
            </button>
        </div>
    </div>
</section>

{{-- ═══ Sticky Cart Bar (Mobile) ═══ --}}
<div class="sticky-cart-bar" id="sticky-bar">
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="flex:1;min-width:0;">
            <div style="font-size:11px;color:var(--text-secondary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $product->name }}</div>
            <div style="font-family:'Space Grotesk',sans-serif;font-size:18px;font-weight:800;color:var(--cyan);" id="sticky-price">Rp {{ number_format($product->getEffectivePrice(), 0, ',', '.') }}</div>
        </div>
        <div class="cta-row" style="flex-shrink:0;">
            <button type="button" class="cta-cart" onclick="document.getElementById('add-to-cart-form').dispatchEvent(new Event('submit'))" style="padding:12px 16px;font-size:12px;border-radius:10px;">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Cart
            </button>
            <button type="button" class="cta-buy" onclick="buyNow()" style="padding:12px 16px;font-size:12px;border-radius:10px;">
                Buy Now
            </button>
        </div>
    </div>
</div>

<script>
var selectedVariantId = null;

function switchImg(src, btn) {
    var main = document.getElementById('main-detail-img');
    if (main) main.src = src;
    document.querySelectorAll('.detail-thumb').forEach(function(t) { t.classList.remove('active'); });
    if (btn) btn.classList.add('active');
}

function selectVariant(btn) {
    if (btn.classList.contains('out')) return;
    document.querySelectorAll('.variant-btn').forEach(function(b) {
        if (!b.classList.contains('out')) {
            b.classList.remove('active');
        }
    });
    btn.classList.add('active');

    var price = btn.getAttribute('data-variant-price');
    var stock = btn.getAttribute('data-variant-stock');
    selectedVariantId = btn.getAttribute('data-variant-id');
    document.getElementById('selected-variant-id').value = selectedVariantId;

    if (price) {
        var formatted = 'Rp ' + Number(price).toLocaleString('id-ID');
        document.getElementById('display-price').textContent = formatted;
        var stickyP = document.getElementById('sticky-price');
        if (stickyP) stickyP.textContent = formatted;
    }
    if (stock !== undefined) {
        var stockEl = document.getElementById('display-stock');
        if (parseInt(stock) > 0) {
            stockEl.textContent = stock + ' units';
            stockEl.style.color = '#4ade80';
        } else {
            stockEl.textContent = 'Out of Stock';
            stockEl.style.color = '#f87171';
        }
    }
}

function changeQty(delta) {
    var display = document.getElementById('qty-display');
    var input = document.getElementById('qty-input');
    var val = parseInt(display.textContent) + delta;
    var max = parseInt('{{ $product->stock }}');
    if (val >= 1 && val <= max) {
        display.textContent = val;
        input.value = val;
    }
}

function switchTab(tab, btn) {
    document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });
    document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
    document.getElementById('tab-' + tab).classList.add('active');
    btn.classList.add('active');
}

function buyNow() {
    var form = document.getElementById('add-to-cart-form');
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'buy_now';
    input.value = '1';
    form.appendChild(input);
    form.submit();
}

function shareProduct() {
    if (navigator.share) {
        navigator.share({ title: '{{ $product->name }}', url: window.location.href });
    } else {
        navigator.clipboard.writeText(window.location.href);
        if (typeof showToast === 'function') showToast('Link copied!', 'success');
    }
}

// Responsive grid
function handleDetailResize() {
    var w = window.innerWidth;
    var grid = document.getElementById('detail-grid');
    var relGrid = document.getElementById('related-grid');
    if (grid) grid.style.gridTemplateColumns = w < 768 ? '1fr' : '1fr 1fr';
    if (relGrid) {
        if (w < 480) relGrid.style.gridTemplateColumns = 'repeat(2,1fr)';
        else if (w < 768) relGrid.style.gridTemplateColumns = 'repeat(2,1fr)';
        else relGrid.style.gridTemplateColumns = 'repeat(4,1fr)';
    }
}
window.addEventListener('resize', handleDetailResize);
handleDetailResize();

function askAIAboutProduct(question) {
    if (typeof toggleAIChat === 'function') {
        toggleAIChat();
    }
    setTimeout(function() {
        if (typeof askAI === 'function') {
            askAI(question + ' (Produk: {{ addslashes($product->name) }})');
        }
    }, 300);
}

function addToCompare(productId) {
    fetch('/compare/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ product_id: productId }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (typeof showToast === 'function') showToast(data.message, 'success');
        } else {
            if (typeof showToast === 'function') showToast(data.message, 'error');
        }
    })
    .catch(() => {
        if (typeof showToast === 'function') showToast('Gagal menambahkan ke perbandingan', 'error');
    });
}
</script>
@endsection
