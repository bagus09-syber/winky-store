@extends('layouts.app')
@section('title', 'Daftar Seller - WINKY STORE')

@section('content')
<section style="padding:100px 0 60px;min-height:100vh;">
    <div style="max-width:640px;margin:0 auto;padding:0 24px;">
        <div style="text-align:center;margin-bottom:40px;">
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:32px;font-weight:700;margin-bottom:8px;">
                <span class="gradient-text">Daftar Sebagai Seller</span>
            </h1>
            <p style="color:var(--text-secondary);font-size:15px;">Buka toko Anda di WINKY STORE dan jangkau jutaan pelanggan</p>
        </div>

        @if($errors->any())
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:14px;padding:16px;margin-bottom:24px;">
            @foreach($errors->all() as $error)
            <p style="color:#f87171;font-size:13px;">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('seller.register') }}" method="POST" style="background:var(--bg-card);border:1px solid var(--border);border-radius:20px;padding:32px;">
            @csrf
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Nama Toko *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input" style="width:100%;padding:12px 16px;background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;color:var(--text);font-size:14px;" placeholder="Nama toko Anda">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Deskripsi Toko</label>
                <textarea name="description" rows="3" style="width:100%;padding:12px 16px;background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;color:var(--text);font-size:14px;resize:vertical;" placeholder="Ceritakan tentang toko Anda">{{ old('description') }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" style="width:100%;padding:12px 16px;background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;color:var(--text);font-size:14px;" placeholder="Nomor telepon">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Email</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" style="width:100%;padding:12px 16px;background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;color:var(--text);font-size:14px;" placeholder="Email toko">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Provinsi *</label>
                    <input type="text" name="province" value="{{ old('province') }}" required style="width:100%;padding:12px 16px;background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;color:var(--text);font-size:14px;" placeholder="Provinsi">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Kota *</label>
                    <input type="text" name="city" value="{{ old('city') }}" required style="width:100%;padding:12px 16px;background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;color:var(--text);font-size:14px;" placeholder="Kota">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Kecamatan</label>
                    <input type="text" name="district" value="{{ old('district') }}" style="width:100%;padding:12px 16px;background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;color:var(--text);font-size:14px;" placeholder="Kecamatan">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Kode Pos</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" style="width:100%;padding:12px 16px;background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;color:var(--text);font-size:14px;" placeholder="Kode pos">
                </div>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Alamat Lengkap *</label>
                <textarea name="address" rows="2" required style="width:100%;padding:12px 16px;background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;color:var(--text);font-size:14px;resize:vertical;" placeholder="Alamat lengkap toko">{{ old('address') }}</textarea>
            </div>

            <button type="submit" style="width:100%;padding:14px;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;transition:all .3s;">
                Daftar Sekarang
            </button>

            <p style="text-align:center;margin-top:16px;font-size:12px;color:var(--text-secondary);">
                Dengan mendaftar, Anda menyetujui Syarat & Ketentuan yang berlaku.
            </p>
        </form>
    </div>
</section>
@endsection
