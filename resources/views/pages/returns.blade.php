@extends('layouts.app')
@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:800px;">
        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(28px,4vw,36px);font-weight:800;color:var(--text);margin-bottom:32px;">Kebijakan Pengembalian</h1>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:32px;">
            <div style="display:flex;flex-direction:column;gap:20px;font-size:14px;color:var(--text-secondary);line-height:1.8;">
                <p><strong style="color:var(--text);">Terakhir diperbarui:</strong> 4 September 2026</p>

                <div>
                    <h3 style="color:var(--text);font-size:16px;font-weight:700;margin-bottom:8px;">1. Syarat Pengembalian</h3>
                    <p>Produk dapat dikembalikan dalam waktu 7 hari setelah diterima jika memenuhi salah satu kondisi berikut:</p>
                    <ul style="margin-top:8px;padding-left:20px;">
                        <li>Produk rusak atau cacat saat diterima</li>
                        <li>Produk tidak sesuai dengan deskripsi</li>
                        <li>Salah kirim (ukuran atau warna)</li>
                        <li>Produk tidak berfungsi sebagaimana mestinya</li>
                    </ul>
                </div>

                <div>
                    <h3 style="color:var(--text);font-size:16px;font-weight:700;margin-bottom:8px;">2. Prosedur Pengembalian</h3>
                    <p>Buka halaman detail pesanan, klik "Request Return", pilih alasan, dan isi formulir. Tim kami akan memproses dalam 1-2 hari kerja.</p>
                </div>

                <div>
                    <h3 style="color:var(--text);font-size:16px;font-weight:700;margin-bottom:8px;">3. Pengembalian Dana (Refund)</h3>
                    <p>Setelah return disetujui dan barang diterima, refund akan diproses dalam 3-7 hari kerja sesuai metode pembayaran yang digunakan.</p>
                </div>

                <div>
                    <h3 style="color:var(--text);font-size:16px;font-weight:700;margin-bottom:8px;">4. Barang yang Tidak Dapat Dikembalikan</h3>
                    <ul style="padding-left:20px;">
                        <li>Produk yang sudah digunakan atau rusak karena kesalahan pengguna</li>
                        <li>Produk personalisasi</li>
                        <li>Produk yang dibeli lebih dari 7 hari yang lalu</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
