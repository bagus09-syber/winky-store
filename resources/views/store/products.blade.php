@extends('layouts.app')
@section('title', 'Produk ' . $store->name . ' - WINKY STORE')

@section('content')
<section style="padding:100px 0 60px;min-height:100vh;">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px;">
        <div style="margin-bottom:24px;">
            <a href="{{ route('store.show', $store->slug) }}" style="color:var(--cyan);font-size:13px;text-decoration:none;">← Kembali ke Toko</a>
        </div>

        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:700;color:var(--text);margin-bottom:32px;">Semua Produk {{ $store->name }}</h1>

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
