@extends('layouts.app')

@section('content')
<section class="pt-28 pb-16 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="acct-header" style="margin-bottom:32px;">
            <h1 class="acct-title">My Wishlist</h1>
            <p class="acct-subtitle">Products you've saved for later</p>
        </div>

        @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-emerald-300 text-sm">{{ session('success') }}</p>
        </div>
        @endif

        @if (session('info'))
        <div class="mb-6 p-4 bg-blue-500/10 border border-blue-500/30 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-blue-300 text-sm">{{ session('info') }}</p>
        </div>
        @endif

        @if ($wishlists->isEmpty())
        <div class="acct-empty" style="max-width:480px;margin:0 auto;">
            <div class="acct-empty-icon">
                <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h3 class="acct-empty-title">Your wishlist is empty</h3>
            <p class="acct-empty-text">Browse our products and save your favorites</p>
            <a href="{{ route('products.index') }}" class="btn-acct-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Explore Products
            </a>
        </div>
        @else
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
            @foreach ($wishlists as $wishlist)
            @php $product = $wishlist->product; @endphp
            <div class="cat-card" style="position:relative;">
                {{-- Image --}}
                <a href="{{ route('products.show', $product->slug) }}" class="card-img-wrap" style="background:var(--bg-elevated);display:flex;align-items:center;justify-content:center;padding:20px;">
                    @if($product->image)
                    <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" style="max-height:160px;object-fit:contain;">
                    @else
                    <div style="width:60px;height:72px;background:linear-gradient(to bottom,#64748b,#475569);border-radius:12px;position:relative;">
                        <div style="position:absolute;top:3px;left:50%;transform:translateX(-50%);width:20px;height:3px;background:#1e293b;border-radius:99px;"></div>
                    </div>
                    @endif
                </a>

                {{-- Badge --}}
                @if($product->getDiscountPercent())
                <div class="card-badge">-{{ $product->getDiscountPercent() }}%</div>
                @endif

                {{-- Remove Button --}}
                <form method="POST" action="{{ route('wishlist.destroy', $wishlist) }}" style="position:absolute;top:12px;right:12px;z-index:5;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="width:36px;height:36px;border-radius:12px;background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;color:#f87171;" onmouseover="this.style.background='rgba(239,68,68,0.25)'" onmouseout="this.style.background='rgba(239,68,68,0.15)'">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>

                {{-- Card Body --}}
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                        @if($product->brand)
                        <span style="font-size:11px;font-weight:600;color:var(--cyan);text-transform:uppercase;letter-spacing:0.05em;">{{ $product->brand->name }}</span>
                        @endif
                        @if($product->category)
                        <span style="color:rgba(255,255,255,0.2);">·</span>
                        <span style="font-size:11px;color:var(--text-secondary);">{{ $product->category->name }}</span>
                        @endif
                    </div>
                    <a href="{{ route('products.show', $product->slug) }}">
                        <h3 class="card-name">{{ $product->name }}</h3>
                    </a>

                    {{-- Stock --}}
                    <div style="margin-bottom:8px;">
                        @if($product->stock > 0)
                        <span style="font-size:11px;color:rgba(255,255,255,0.35);">In Stock</span>
                        @else
                        <span style="font-size:11px;color:#f87171;font-weight:500;">Out of Stock</span>
                        @endif
                    </div>

                    {{-- Price --}}
                    @if($product->sale_price)
                    <div style="font-size:12px;color:var(--text-secondary);text-decoration:line-through;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    @endif
                    <div class="card-price">Rp {{ number_format($product->getEffectivePrice(), 0, ',', '.') }}</div>

                    {{-- Add to Cart --}}
                    <div class="card-actions" style="opacity:1;transform:none;margin-top:12px;">
                        <form method="POST" action="{{ route('cart.store') }}" style="width:100%;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-cart-btn" style="width:100%;padding:10px;border-radius:12px;font-size:13px;font-weight:600;border:1px solid rgba(0,229,255,0.3);background:transparent;color:var(--cyan);cursor:pointer;transition:all .25s;font-family:inherit;" {{ $product->stock <= 0 ? 'disabled style="opacity:0.4;cursor:not-allowed;"' : '' }} onmouseover="if(!this.disabled){this.style.background='rgba(0,229,255,0.08)';this.style.borderColor='var(--cyan)';}" onmouseout="if(!this.disabled){this.style.background='transparent';this.style.borderColor='rgba(0,229,255,0.3)';}">
                                {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

<style>
@media(max-width:1024px){
    div[style*="grid-template-columns:repeat(4"]{ grid-template-columns: repeat(3,1fr) !important; }
}
@media(max-width:768px){
    div[style*="grid-template-columns:repeat(4"]{ grid-template-columns: repeat(2,1fr) !important; gap: 12px !important; }
}
@media(max-width:480px){
    div[style*="grid-template-columns:repeat(4"]{ grid-template-columns: 1fr !important; }
}
</style>
@endsection
