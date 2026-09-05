@php
    $isFlash = ($variant ?? '') === 'flash';
    $cardClass = $isFlash ? 'fs-card' : 'pcard';
    $imgHeight = $isFlash ? 170 : 210;
    $minHeight = $isFlash ? 180 : 220;
@endphp
<div class="{{ $cardClass }} fade-up" data-slug="{{ $product->slug }}">
    <div class="pcard-img" style="min-height:{{ $minHeight }};">
        <a href="{{ route('products.show', $product->slug) }}" style="display:block;width:100%;">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" style="width:100%;height:100%;object-fit:contain;">
            @else
            <img src="{{ asset('images/products/no-image.png') }}" alt="{{ $product->name }}" loading="lazy" style="width:100%;height:100%;object-fit:contain;background:var(--bg-card);">
            @endif
        </a>
        @if($product->getDiscountPercent())
        <div class="badge-sale">-{{ $product->getDiscountPercent() }}%</div>
        @elseif($product->is_featured)
        <div class="badge-sale" style="background:linear-gradient(135deg,var(--blue),var(--cyan));box-shadow:0 4px 15px rgba(41,121,255,0.4);">BEST</div>
        @endif
        <button class="wishlist-float" aria-label="Wishlist" onclick="event.preventDefault();event.stopPropagation();var f=this.closest('.pcard,.fs-card');var n=f?f.querySelector('.pcard-name'):null;var name=n?n.textContent.trim():'';var active=this.classList.toggle('active');var svg=this.querySelector('svg');if(active){svg.innerHTML='<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z\"};showToast(name+' added to Wishlist','wishlist');}else{svg.innerHTML='<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z\"};showToast('Removed from Wishlist','info');}">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
        </button>
        <button class="qv-float" aria-label="Quick View" onclick="event.preventDefault();event.stopPropagation();openQuickView('{{ $product->slug }}')">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </button>
    </div>
    <div class="pcard-info">
        <div class="pcard-brand">
            @if($product->store)
            <a href="{{ route('store.show', $product->store->slug) }}" onclick="event.stopPropagation()" style="color:var(--cyan);text-decoration:none;">{{ $product->store->name }}</a><span style="opacity:0.4"> · </span>
            @endif
            {{ $product->brand->name ?? '' }} · {{ $product->category->name ?? '' }}
        </div>
        <a href="{{ route('products.show', $product->slug) }}"><div class="pcard-name">{{ $product->name }}</div></a>
        <div class="pcard-stars">
            <svg viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <span>4.8</span>
            <span class="pcard-stock">{{ $product->stock }} in stock</span>
        </div>
        @if($product->sale_price)
        <div class="pcard-old">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        @endif
        <div class="pcard-price">Rp {{ number_format($product->getEffectivePrice(), 0, ',', '.') }}</div>
        @if($product->getDiscountPercent())
        <div style="font-size:11px;color:#4ade80;font-weight:600;margin-top:4px;">Hemat Rp {{ number_format($product->price - $product->getEffectivePrice(), 0, ',', '.') }}</div>
        @endif
        <button class="add-cart-btn" onclick="event.preventDefault();event.stopPropagation();fetch('{{ route('cart.store') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({product_id:{{ $product->id }},quantity:1})}).then(function(){showToast('{{ addslashes($product->name) }} added to Cart','cart');this.textContent='✓ Added';var self=this;setTimeout(function(){self.textContent='Add to Cart';},1500);}.bind(this)).catch(function(){showToast('Please login first','info');});">Add to Cart</button>
    </div>
</div>