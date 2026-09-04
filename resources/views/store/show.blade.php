@extends('layouts.app')
@section('title', $store->name . ' - WINKY STORE')

@section('content')
<section style="padding:100px 0 60px;min-height:100vh;">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px;">
        {{-- Store Banner --}}
        @if($store->banner)
        <div style="width:100%;height:200px;border-radius:20px;overflow:hidden;margin-bottom:24px;">
            <img src="{{ asset('storage/' . $store->banner) }}" style="width:100%;height:100%;object-fit:cover;">
        </div>
        @endif

        {{-- Store Header --}}
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:20px;padding:32px;margin-bottom:32px;">
            <div class="flex flex-col md:flex-row items-start gap-6">
                @if($store->logo)
                <img src="{{ asset('storage/' . $store->logo) }}" style="width:80px;height:80px;object-fit:cover;border-radius:16px;">
                @else
                <div style="width:80px;height:80px;background:linear-gradient(135deg,var(--blue),var(--cyan));border-radius:16px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span style="font-size:28px;font-weight:700;color:#fff;">{{ substr($store->name, 0, 1) }}</span>
                </div>
                @endif

                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:700;color:var(--text);">{{ $store->name }}</h1>
                        @if($store->is_verified)
                        <span style="background:rgba(34,211,238,0.1);color:var(--cyan);padding:4px 12px;border-radius:9999px;font-size:12px;font-weight:600;">✓ Verified</span>
                        @endif
                    </div>
                    <p style="color:var(--text-secondary);font-size:14px;margin-bottom:12px;">{{ $store->description ?? 'Toko resmi di WINKY STORE' }}</p>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <span style="color:var(--text-secondary);">⭐ {{ $avgRating }} ({{ $reviewsCount }} ulasan)</span>
                        <span style="color:var(--text-secondary);">📦 {{ $totalProducts }} produk</span>
                        <span style="color:var(--text-secondary);">📍 {{ $store->city }}, {{ $store->province }}</span>
                        <span style="color:var(--text-secondary);">📅 Bergabung {{ $store->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div style="display:flex;gap:8px;margin-bottom:24px;border-bottom:1px solid var(--border);padding-bottom:8px;">
            <a href="{{ route('store.show', $store->slug) }}" style="padding:8px 20px;border-radius:10px;font-size:14px;font-weight:600;{{ request()->routeIs('store.show') ? 'background:rgba(34,211,238,0.1);color:var(--cyan);' : 'color:var(--text-secondary);' }}">Produk</a>
            <a href="{{ route('store.reviews', $store->slug) }}" style="padding:8px 20px;border-radius:10px;font-size:14px;font-weight:600;{{ request()->routeIs('store.reviews') ? 'background:rgba(34,211,238,0.1);color:var(--cyan);' : 'color:var(--text-secondary);' }}">Ulasan</a>
        </div>

        {{-- Products --}}
        @if($products->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px;">
            @foreach($products as $product)
            @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
        @else
        <div style="text-align:center;padding:60px 0;">
            <p style="color:var(--text-secondary);">Belum ada produk</p>
        </div>
        @endif
    </div>
</section>
@endsection
