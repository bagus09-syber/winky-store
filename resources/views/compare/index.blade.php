@extends('layouts.app')

@section('meta_title', 'Bandingkan Produk - WINKY STORE')
@section('meta_description', 'Bandingkan produk favorit Anda di WINKY STORE. Bandingkan harga, fitur, dan spesifikasi.')

@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:1200px;">

        <div style="margin-bottom:24px;">
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(24px,3vw,32px);font-weight:800;color:var(--text);margin:0;">
                Bandingkan Produk
            </h1>
            <p style="color:var(--text-secondary);font-size:14px;margin-top:4px;">Pilih hingga 4 produk untuk dibandingkan</p>
        </div>

        @if(empty($products))
        <div class="stat-card" style="text-align:center;padding:60px 20px;">
            <svg style="width:64px;height:64px;margin:0 auto 16px;color:var(--text-secondary);opacity:0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <h3 style="color:var(--text);font-size:18px;margin-bottom:8px;">Belum Ada Produk</h3>
            <p style="color:var(--text-secondary);font-size:14px;margin-bottom:20px;">Tambahkan produk dari halaman produk atau detail produk untuk membandingkan.</p>
            <a href="{{ route('products.index') }}" style="display:inline-block;padding:12px 24px;background:linear-gradient(135deg,var(--cyan),var(--blue));color:#000;border-radius:12px;font-weight:700;text-decoration:none;">Jelajahi Produk</a>
        </div>
        @else
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;min-width:{{ count($products) > 2 ? '700px' : '100%' }};">
                <thead>
                    <tr>
                        <th style="padding:16px;min-width:140px;"></th>
                        @foreach($products as $product)
                        <th style="padding:16px;text-align:center;min-width:180px;">
                            <div class="stat-card" style="position:relative;">
                                <button onclick="removeCompare({{ $product['id'] }})" style="position:absolute;top:8px;right:8px;width:24px;height:24px;border-radius:50%;background:rgba(239,68,68,0.1);border:none;color:#ef4444;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;">✕</button>
                                <a href="{{ route('products.show', $product['slug']) }}" style="text-decoration:none;">
                                    <div style="width:100%;height:120px;border-radius:12px;background:var(--bg-deep);margin-bottom:12px;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                                        @if($product['image'])
                                        <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}" style="width:100%;height:100%;object-fit:contain;padding:8px;" loading="lazy">
                                        @else
                                        <img src="{{ asset('images/products/no-image.png') }}" alt="Product" style="width:100%;height:100%;object-fit:contain;padding:8px;" loading="lazy">
                                        @endif
                                    </div>
                                    <h3 style="font-size:14px;font-weight:600;color:var(--text);margin:0 0 4px;line-height:1.3;">{{ $product['name'] }}</h3>
                                    <p style="font-size:12px;color:var(--text-secondary);margin:0;">{{ $product['brand'] }}</p>
                                </a>
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-top:1px solid var(--border);">
                        <td style="padding:14px 16px;font-weight:600;color:var(--text);font-size:13px;">Harga</td>
                        @foreach($products as $product)
                        <td style="padding:14px 16px;text-align:center;">
                            @if($product['sale_price'])
                            <span style="text-decoration:line-through;color:var(--text-secondary);font-size:12px;display:block;">Rp {{ number_format($product['price'], 0, ',', '.') }}</span>
                            <span style="color:var(--cyan);font-weight:700;font-size:16px;">Rp {{ number_format($product['effective_price'], 0, ',', '.') }}</span>
                            @else
                            <span style="color:var(--text);font-weight:700;font-size:16px;">Rp {{ number_format($product['price'], 0, ',', '.') }}</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr style="border-top:1px solid var(--border);">
                        <td style="padding:14px 16px;font-weight:600;color:var(--text);font-size:13px;">Rating</td>
                        @foreach($products as $product)
                        <td style="padding:14px 16px;text-align:center;">
                            <span style="color:#fbbf24;font-weight:600;">{{ $product['average_rating'] }} ★</span>
                            <span style="color:var(--text-secondary);font-size:12px;display:block;">{{ $product['reviews_count'] }} ulasan</span>
                        </td>
                        @endforeach
                    </tr>
                    <tr style="border-top:1px solid var(--border);">
                        <td style="padding:14px 16px;font-weight:600;color:var(--text);font-size:13px;">Kategori</td>
                        @foreach($products as $product)
                        <td style="padding:14px 16px;text-align:center;color:var(--text-secondary);font-size:13px;">{{ $product['category'] }}</td>
                        @endforeach
                    </tr>
                    <tr style="border-top:1px solid var(--border);">
                        <td style="padding:14px 16px;font-weight:600;color:var(--text);font-size:13px;">Brand</td>
                        @foreach($products as $product)
                        <td style="padding:14px 16px;text-align:center;color:var(--text);font-size:13px;font-weight:600;">{{ $product['brand'] }}</td>
                        @endforeach
                    </tr>
                    <tr style="border-top:1px solid var(--border);">
                        <td style="padding:14px 16px;font-weight:600;color:var(--text);font-size:13px;">Stok</td>
                        @foreach($products as $product)
                        <td style="padding:14px 16px;text-align:center;">
                            @if($product['stock'] > 10)
                            <span style="color:#4ade80;font-size:13px;">Tersedia</span>
                            @elseif($product['stock'] > 0)
                            <span style="color:#fbbf24;font-size:13px;">Stok {{ $product['stock'] }}</span>
                            @else
                            <span style="color:#ef4444;font-size:13px;">Habis</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr style="border-top:1px solid var(--border);">
                        <td style="padding:14px 16px;font-weight:600;color:var(--text);font-size:13px;">Berat</td>
                        @foreach($products as $product)
                        <td style="padding:14px 16px;text-align:center;color:var(--text-secondary);font-size:13px;">{{ $product['weight'] ?? '-' }} gr</td>
                        @endforeach
                    </tr>
                    <tr style="border-top:1px solid var(--border);">
                        <td style="padding:14px 16px;font-weight:600;color:var(--text);font-size:13px;">Deskripsi</td>
                        @foreach($products as $product)
                        <td style="padding:14px 16px;text-align:center;color:var(--text-secondary);font-size:12px;max-width:200px;">{{ Str::limit($product['description'], 100) }}</td>
                        @endforeach
                    </tr>
                    <tr style="border-top:1px solid var(--border);">
                        <td style="padding:16px;"></td>
                        @foreach($products as $product)
                        <td style="padding:16px;text-align:center;">
                            <a href="{{ route('products.show', $product['slug']) }}" style="display:inline-block;padding:10px 20px;background:linear-gradient(135deg,var(--cyan),var(--blue));color:#000;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;">Lihat Produk</a>
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin-top:24px;text-align:center;">
            <button onclick="clearCompare()" style="padding:10px 20px;background:var(--bg-card);border:1px solid var(--border);border-radius:10px;color:var(--text-secondary);font-size:13px;cursor:pointer;">Hapus Semua</button>
        </div>
        @endif
    </div>
</section>

<script>
function removeCompare(productId) {
    fetch('/compare/remove/' + productId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    }).then(r => r.json()).then(data => {
        if (data.success) location.reload();
    });
}

function clearCompare() {
    fetch('/compare/clear', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    }).then(r => r.json()).then(data => {
        if (data.success) location.reload();
    });
}
</script>
@endsection
