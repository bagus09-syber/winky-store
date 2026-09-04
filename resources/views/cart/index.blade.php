@extends('layouts.app')
@section('content')

<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container">

        {{-- Page Header --}}
        <div style="margin-bottom:36px;">
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(28px,4vw,40px);font-weight:800;color:var(--text);margin:0 0 8px;">Shopping Cart</h1>
            <p style="color:var(--text-secondary);font-size:15px;">
                @if($cart && $cart->items->count())
                {{ $cart->total_quantity }} {{ Str::plural('item', $cart->total_quantity) }} in your cart
                @else
                Your cart is empty
                @endif
            </p>
        </div>

        @if($cart && $cart->items->count())
        <div style="display:grid;grid-template-columns:1fr 360px;gap:32px;align-items:start;" id="cart-layout">

            {{-- ═══ Cart Items ═══ --}}
            <div>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @foreach($cart->items as $item)
                    @php
                        $product = $item->product;
                        $variant = $item->variant;
                        $maxStock = $variant ? $variant->stock : ($product ? $product->stock : 0);
                        $unitPrice = $item->price;
                        $lineTotal = $unitPrice * $item->quantity;
                        $hasSale = $product && $product->sale_price && $product->sale_price < $product->price;
                    @endphp
                    <div class="cart-item fade-up">
                        {{-- Delete --}}
                        <form method="POST" action="{{ route('cart.destroy', $item) }}" style="position:absolute;top:16px;right:16px;">
                            @csrf @method('DELETE')
                            <button type="submit" class="cart-item-delete" aria-label="Remove item" onclick="return confirm('Remove this item from cart?')">
                                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>

                        {{-- Image --}}
                        <a href="{{ $product ? route('products.show', $product->slug) : '#' }}" class="cart-item-img" style="text-decoration:none;">
                            @include('partials.product-image', ['product' => $product, 'class' => '', 'imgClass' => ''])
                        </a>

                        {{-- Info --}}
                        <div class="cart-item-body">
                            <div>
                                <div class="cart-item-brand">{{ $product->brand->name ?? '' }}</div>
                                <a href="{{ $product ? route('products.show', $product->slug) : '#' }}" style="text-decoration:none;">
                                    <div class="cart-item-name">{{ $product->name ?? 'Product' }}</div>
                                </a>
                                @if($variant)
                                <div class="cart-item-variant">
                                    @foreach($variant->attributes ?? [] as $k => $v)
                                    <span style="text-transform:capitalize;">{{ $k }}: {{ $v }}</span>{{ $loop->last ? '' : ' · ' }}
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <div class="cart-item-price-row">
                                <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                                    {{-- Quantity --}}
                                    <div class="cart-item-qty">
                                        <button type="button" onclick="updateQty({{ $item->id }}, -1, {{ $maxStock }})" aria-label="Decrease">−</button>
                                        <span id="qty-{{ $item->id }}">{{ $item->quantity }}</span>
                                        <button type="button" onclick="updateQty({{ $item->id }}, 1, {{ $maxStock }})" aria-label="Increase">+</button>
                                    </div>
                                    <div style="font-size:12px;color:var(--text-secondary);">
                                        @if($maxStock <= 5 && $maxStock > 0)
                                        <span style="color:#fbbf24;">Only {{ $maxStock }} left</span>
                                        @elseif($maxStock <= 0)
                                        <span style="color:#f87171;">Out of stock</span>
                                        @endif
                                    </div>
                                </div>

                                <div style="text-align:right;">
                                    @if($hasSale)
                                    <div class="cart-item-old">Rp {{ number_format($product->price * $item->quantity, 0, ',', '.') }}</div>
                                    @endif
                                    <div class="cart-item-total">Rp {{ number_format($lineTotal, 0, ',', '.') }}</div>
                                    @if($item->quantity > 1)
                                    <div class="cart-item-unit">Rp {{ number_format($unitPrice, 0, ',', '.') }} each</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Hidden form for qty update --}}
                        <form id="qty-form-{{ $item->id }}" method="POST" action="{{ route('cart.update', $item) }}" style="display:none;">
                            @csrf @method('PUT')
                            <input type="hidden" name="quantity" id="qty-val-{{ $item->id }}" value="{{ $item->quantity }}">
                        </form>
                    </div>
                    @endforeach
                </div>

                {{-- Continue Shopping --}}
                <div style="margin-top:24px;">
                    <a href="{{ route('products.index') }}" style="display:inline-flex;align-items:center;gap:8px;font-size:14px;color:var(--text-secondary);text-decoration:none;font-weight:600;transition:color .2s;" onmouseover="this.style.color='var(--cyan)'" onmouseout="this.style.color='var(--text-secondary)'">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                        Continue Shopping
                    </a>
                </div>
            </div>

            {{-- ═══ Order Summary ═══ --}}
            <div class="cart-summary">
                <h3>Order Summary</h3>

                <div class="summary-row">
                    <span>Subtotal ({{ $cart->total_quantity }} items)</span>
                    <span>Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span style="font-style:italic;font-size:12px;">Calculated at checkout</span>
                </div>

                <div class="summary-row total">
                    <span>Estimated Total</span>
                    <span>Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('checkout.index') }}" class="pay-btn" style="margin-top:24px;">
                    Proceed to Checkout
                    <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>

                <div style="margin-top:20px;padding:16px;background:rgba(0,229,255,0.04);border:1px solid rgba(0,229,255,0.1);border-radius:12px;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                        <svg style="width:16px;height:16px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span style="font-size:12px;font-weight:700;color:var(--cyan);">Secure Checkout</span>
                    </div>
                    <p style="font-size:11px;color:var(--text-secondary);margin:0;line-height:1.5;">All transactions are encrypted and your data is protected.</p>
                </div>
            </div>
        </div>

        @else
        {{-- ═══ Empty Cart ═══ --}}
        <div class="cart-empty">
            <div class="cart-empty-icon">
                <svg style="width:40px;height:40px;color:var(--text-secondary)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:700;color:var(--text);margin:0 0 10px;">Your cart is empty</h3>
            <p style="color:var(--text-secondary);font-size:15px;margin:0 0 36px;max-width:400px;margin-left:auto;margin-right:auto;">Looks like you haven't added any products yet. Start exploring our collection!</p>
            <a href="{{ route('products.index') }}" class="btn-glow" style="font-size:14px;padding:16px 40px;">
                <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                Start Shopping
            </a>
        </div>
        @endif
    </div>
</section>

<script>
function updateQty(itemId, delta, maxStock) {
    var display = document.getElementById('qty-' + itemId);
    var input = document.getElementById('qty-val-' + itemId);
    var val = parseInt(display.textContent) + delta;
    if (val >= 1 && val <= maxStock) {
        display.textContent = val;
        input.value = val;
        document.getElementById('qty-form-' + itemId).submit();
    }
}
</script>
@endsection
