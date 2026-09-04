@extends('layouts.app')
@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:900px;">

        <div style="text-align:center;margin-bottom:48px;">
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(28px,4vw,40px);font-weight:800;color:var(--text);margin:0 0 8px;">Help Center</h1>
            <p style="font-size:16px;color:var(--text-secondary);">Kami siap membantu kamu</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:16px;margin-bottom:48px;" class="help-grid">
            @php
            $cats = [
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Orders', 'desc' => 'Pesanan, status, dan pengiriman', 'color' => 'cyan'],
                ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'title' => 'Payment', 'desc' => 'Pembayaran, refund, dan voucher', 'color' => 'blue'],
                ['icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0', 'title' => 'Shipping', 'desc' => 'Ongkir, kurir, dan lacak paket', 'color' => 'magenta'],
                ['icon' => 'M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z', 'title' => 'Returns', 'desc' => 'Return, refund, dan garansi', 'color' => 'yellow'],
                ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'title' => 'Account', 'desc' => 'Profil, alamat, dan keamanan', 'color' => 'green'],
                ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'title' => 'Product', 'desc' => 'Stok, spesifikasi, dan ulasan', 'color' => 'cyan'],
            ];
            @endphp
            @foreach($cats as $cat)
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;text-align:center;transition:all .25s;cursor:default;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)'" onmouseout="this.style.borderColor='var(--border)'">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(0,229,255,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                    <svg style="width:22px;height:22px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cat['icon'] }}"/></svg>
                </div>
                <div style="font-weight:700;color:var(--text);font-size:15px;margin-bottom:4px;">{{ $cat['title'] }}</div>
                <div style="font-size:13px;color:var(--text-secondary);">{{ $cat['desc'] }}</div>
            </div>
            @endforeach
        </div>

        <div style="margin-bottom:48px;">
            <h2 style="font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:800;color:var(--text);margin-bottom:24px;">Frequently Asked Questions</h2>

            @php
            $faqs = [
                ['q' => 'Bagaimana cara melacak pesanan?', 'a' => 'Setelah pesanan dikirim, kamu akan mendapatkan nomor resi yang bisa digunakan untuk melacak paket di halaman detail pesanan.'],
                ['q' => 'Metode pembayaran apa yang diterima?', 'a' => 'Kami menerima QRIS, Transfer Bank (BCA, BNI, Mandiri), dan E-Wallet (GoPay, OVO, Dana).'],
                ['q' => 'Bagaimana cara mengajukan return?', 'a' => 'Buka halaman detail pesanan yang sudah diterima/selesai, lalu klik "Request Return". Pilih alasan dan isi formulir.'],
                ['q' => 'Berapa lama proses refund?', 'a' => 'Proses refund setelah return disetujui biasanya memakan waktu 3-7 hari kerja tergantung metode pembayaran.'],
                ['q' => 'Apakah bisa mengubah alamat setelah pesanan dibuat?', 'a' => 'Sayangnya alamat tidak bisa diubah setelah pesanan dibuat. Hubungi kami segera jika ada kesalahan alamat.'],
                ['q' => 'Bagaimana cara menggunakan voucher?', 'a' => 'Masukkan kode voucher pada halaman checkout di kolom "Voucher / Promo", lalu klik "Terapkan".'],
            ];
            @endphp

            <div style="display:flex;flex-direction:column;gap:8px;">
                @foreach($faqs as $i => $faq)
                <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:14px;overflow:hidden;">
                    <button onclick="toggleFaq({{ $i }})" style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:18px 20px;background:none;border:none;cursor:pointer;text-align:left;">
                        <span style="font-weight:600;color:var(--text);font-size:14px;">{{ $faq['q'] }}</span>
                        <svg id="faq-icon-{{ $i }}" style="width:18px;height:18px;color:var(--text-secondary);transition:transform .2s;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="faq-content-{{ $i }}" style="display:none;padding:0 20px 18px;">
                        <p style="font-size:14px;color:var(--text-secondary);line-height:1.7;">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div style="text-align:center;padding:40px;background:var(--bg-card);border:1px solid var(--border);border-radius:16px;">
            <h3 style="font-family:'Space Grotesk',sans-serif;font-size:18px;font-weight:700;color:var(--text);margin-bottom:8px;">Masih punya pertanyaan?</h3>
            <p style="font-size:14px;color:var(--text-secondary);margin-bottom:20px;">Tim support kami siap membantu kamu</p>
            <a href="{{ route('help.contact') }}" style="display:inline-flex;align-items:center;gap:8px;padding:14px 28px;background:linear-gradient(135deg,var(--cyan),var(--blue));color:#000;border-radius:12px;font-size:14px;font-weight:700;text-decoration:none;transition:all .3s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                Hubungi Support
            </a>
        </div>
    </div>
</section>

<script>
function toggleFaq(i) {
    var content = document.getElementById('faq-content-' + i);
    var icon = document.getElementById('faq-icon-' + i);
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.style.display = 'none';
        icon.style.transform = 'rotate(0)';
    }
}
</script>

<style>
@media (max-width: 640px) {
    .help-grid { grid-template-columns: 1fr !important; }
}
@media (min-width: 641px) and (max-width: 768px) {
    .help-grid { grid-template-columns: 1fr 1fr !important; }
}
</style>
@endsection
