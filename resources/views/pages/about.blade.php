@extends('layouts.app')
@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:800px;">
        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(28px,4vw,36px);font-weight:800;color:var(--text);margin-bottom:32px;">About WINKY STORE</h1>

        <div style="display:flex;flex-direction:column;gap:24px;">
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:32px;">
                <h2 style="font-family:'Space Grotesk',sans-serif;font-size:20px;font-weight:700;color:var(--text);margin-bottom:16px;">Our Story</h2>
                <p style="font-size:15px;color:var(--text-secondary);line-height:1.8;">WINKY STORE adalah marketplace premium yang menyediakan produk berkualitas tinggi dengan pengalaman berbelanja yang modern dan terpercaya. Kami berkomitmen untuk memberikan layanan terbaik kepada setiap pelanggan kami.</p>
            </div>

            <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:16px;" class="about-grid">
                @php
                $values = [
                    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Trusted', 'desc' => 'Keamanan data dan transaksi'],
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Fast', 'desc' => 'Pengiriman cepat dan akurat'],
                    ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'title' => 'Quality', 'desc' => 'Produk pilihan terbaik'],
                ];
                @endphp
                @foreach($values as $v)
                <div style="text-align:center;padding:24px;background:var(--bg-card);border:1px solid var(--border);border-radius:16px;">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(0,229,255,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <svg style="width:22px;height:22px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $v['icon'] }}"/></svg>
                    </div>
                    <div style="font-weight:700;color:var(--text);font-size:15px;margin-bottom:4px;">{{ $v['title'] }}</div>
                    <div style="font-size:13px;color:var(--text-secondary);">{{ $v['desc'] }}</div>
                </div>
                @endforeach
            </div>

            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:32px;">
                <h2 style="font-family:'Space Grotesk',sans-serif;font-size:20px;font-weight:700;color:var(--text);margin-bottom:16px;">Contact</h2>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div><span style="font-size:13px;color:var(--text-secondary);">Email:</span><br><span style="color:var(--text);">support@winkystore.id</span></div>
                    <div><span style="font-size:13px;color:var(--text-secondary);">Phone:</span><br><span style="color:var(--text);">+62 812 3456 7890</span></div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>@media(max-width:640px){.about-grid{grid-template-columns:1fr!important;}}</style>
@endsection
