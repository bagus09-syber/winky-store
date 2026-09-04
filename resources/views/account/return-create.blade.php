@extends('layouts.app')
@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:700px;">
        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:800;color:var(--text);margin-bottom:28px;">Request Return — Order #{{ $order->order_number }}</h1>

        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:28px;">
            <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px;">
                @foreach($order->items as $item)
                <div style="display:flex;gap:12px;align-items:center;">
                    <div style="width:48px;height:48px;border-radius:10px;background:var(--bg-deep);overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                        @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:contain;padding:4px;" loading="lazy">
                        @else
                        <img src="{{ asset('images/products/no-image.png') }}" alt="Product" style="width:100%;height:100%;object-fit:contain;padding:4px;" loading="lazy">
                        @endif
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:14px;font-weight:600;color:var(--text);">{{ $item->product_name }}</div>
                        <div style="font-size:12px;color:var(--text-secondary);">x{{ $item->quantity }} — Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <form action="{{ route('returns.store', $order->id) }}" method="POST">
                @csrf
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Alasan Return *</label>
                        <select name="reason" class="form-input form-select" required>
                            <option value="">Pilih alasan</option>
                            <option value="Produk rusak/cacat">Produk rusak/cacat</option>
                            <option value="Salah kirim (ukuran/warna)">Salah kirim (ukuran/warna)</option>
                            <option value="Tidak sesuai deskripsi">Tidak sesuai deskripsi</option>
                            <option value="Produk tidak berfungsi">Produk tidak berfungsi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Deskripsi Tambahan</label>
                        <textarea name="description" class="form-input form-textarea" rows="4" placeholder="Jelaskan masalah yang kamu alami...">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="w-full btn-primary" style="padding:14px;border-radius:12px;" onclick="return confirm('Yakin ingin mengajukan return?')">
                        Kirim Request Return
                    </button>
                </div>
            </form>
        </div>

        <a href="{{ route('orders.show', $order->order_number) }}" style="display:inline-flex;align-items:center;gap:6px;margin-top:16px;font-size:13px;color:var(--text-secondary);text-decoration:none;">← Kembali ke Detail Pesanan</a>
    </div>
</section>
@endsection
