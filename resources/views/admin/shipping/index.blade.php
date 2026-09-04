@extends('layouts.admin')

@section('content')
<div style="padding:24px;max-width:1200px;">

    @if(session('success'))
    <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:12px;padding:16px 20px;margin-bottom:24px;">
        <span style="font-size:13px;color:#4ade80;">{{ session('success') }}</span>
    </div>
    @endif

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:32px;">
        <div>
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:800;color:var(--text);margin:0;">Pengaturan Pengiriman</h1>
            <p style="font-size:14px;color:var(--text-secondary);margin-top:4px;">Kelola warehouse, metode pengiriman, dan konfigurasi ongkir</p>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:12px;color:var(--text-secondary);">Mode:</span>
            <span style="padding:6px 14px;border-radius:8px;font-size:12px;font-weight:700;background:rgba(251,191,36,0.12);color:#fbbf24;border:1px solid rgba(251,191,36,0.2);text-transform:uppercase;letter-spacing:1px;">{{ $driver }}</span>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:32px;" class="shipping-settings-grid">

        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:28px;">
            <h2 style="font-family:'Space Grotesk',sans-serif;font-size:18px;font-weight:700;color:var(--text);margin:0 0 20px;display:flex;align-items:center;gap:10px;">
                <svg style="width:20px;height:20px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Warehouse / Origin
            </h2>

            <form action="{{ route('admin.shipping.updateWarehouse') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Nama Warehouse</label>
                        <input type="text" name="name" value="{{ $warehouse->name ?? '' }}" class="form-input" placeholder="WINKY STORE Warehouse" required>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Kontak</label>
                            <input type="text" name="contact_name" value="{{ $warehouse->contact_name ?? '' }}" class="form-input" placeholder="Nama PIC" required>
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Telepon</label>
                            <input type="text" name="phone" value="{{ $warehouse->phone ?? '' }}" class="form-input" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Provinsi</label>
                            <input type="text" name="province" value="{{ $warehouse->province ?? '' }}" class="form-input" placeholder="DKI Jakarta" required>
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Kota</label>
                            <input type="text" name="city" value="{{ $warehouse->city ?? '' }}" class="form-input" placeholder="Jakarta Selatan" required>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Kecamatan</label>
                            <input type="text" name="district" value="{{ $warehouse->district ?? '' }}" class="form-input" placeholder="Kebayoran Baru">
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Kode Pos</label>
                            <input type="text" name="postal_code" value="{{ $warehouse->postal_code ?? '' }}" class="form-input" placeholder="12190">
                        </div>
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Alamat Lengkap</label>
                        <textarea name="full_address" class="form-input form-textarea" rows="3" placeholder="Jl. xxx No. xxx, RT/RW xxx/xxx" required>{{ $warehouse->full_address ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="w-full btn-primary" style="padding:12px;border-radius:10px;">
                        Simpan Warehouse
                    </button>
                </div>
            </form>
        </div>

        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:28px;">
            <h2 style="font-family:'Space Grotesk',sans-serif;font-size:18px;font-weight:700;color:var(--text);margin:0 0 20px;display:flex;align-items:center;gap:10px;">
                <svg style="width:20px;height:20px;color:var(--magenta)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Metode Pengiriman
            </h2>

            <form action="{{ route('admin.shipping.storeMethod') }}" method="POST">
                @csrf

                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Kurir</label>
                            <select name="courier" class="form-input form-select" required>
                                <option value="">Pilih Kurir</option>
                                <option value="JNE">JNE</option>
                                <option value="J&T Express">J&T Express</option>
                                <option value="SiCepat">SiCepat</option>
                                <option value="AnterAja">AnterAja</option>
                                <option value="Ninja Xpress">Ninja Xpress</option>
                                <option value="POS Indonesia">POS Indonesia</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Service</label>
                            <input type="text" name="service" class="form-input" placeholder="REG, YES, OKE, dll" required>
                        </div>
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Deskripsi</label>
                        <input type="text" name="description" class="form-input" placeholder="Reguler 2-3 hari">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Estimasi (hari)</label>
                            <input type="number" name="estimated_days" class="form-input" value="3" min="1" max="30" required>
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Berat Maks (gram)</label>
                            <input type="number" name="max_weight" class="form-input" value="30000" min="1" required>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Harga Dasar (Rp)</label>
                            <input type="number" name="base_price" class="form-input" value="0" min="0" required>
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Harga per Kg (Rp)</label>
                            <input type="number" name="price_per_kg" class="form-input" value="5000" min="0" required>
                        </div>
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Berat Minimum (gram)</label>
                        <input type="number" name="min_weight" class="form-input" value="0" min="0" required>
                    </div>

                    <button type="submit" class="w-full btn-primary" style="padding:12px;border-radius:10px;">
                        Tambah Metode
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;">
        <div style="padding:24px 28px;border-bottom:1px solid var(--border);">
            <h2 style="font-family:'Space Grotesk',sans-serif;font-size:18px;font-weight:700;color:var(--text);margin:0;">Daftar Metode Pengiriman</h2>
        </div>

        @if($methods->isEmpty())
        <div style="padding:60px 28px;text-align:center;">
            <svg style="width:48px;height:48px;color:var(--text-secondary);margin:0 auto 16px;opacity:0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" "M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <p style="font-size:14px;color:var(--text-secondary);">Belum ada metode pengiriman.</p>
        </div>
        @else
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--border);">
                        <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.5px;">Kurir</th>
                        <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">Service</th>
                        <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">Estimasi</th>
                        <th style="padding:14px 20px;text-align:right;font-size:12px;font-weight:600;color:var(--text-secondary);">Harga Dasar</th>
                        <th style="padding:14px 20px;text-align:right;font-size:12px;font-weight:600;color:var(--text-secondary);">Per Kg</th>
                        <th style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:var(--text-secondary);">Status</th>
                        <th style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($methods as $method)
                    <tr style="border-bottom:1px solid var(--border);{{ $loop->last ? 'border-bottom:none;' : '' }}">
                        <td style="padding:16px 20px;font-weight:600;color:var(--text);font-size:14px;">{{ $method->courier }}</td>
                        <td style="padding:16px 20px;color:var(--text);font-size:14px;">{{ $method->service }}</td>
                        <td style="padding:16px 20px;color:var(--text-secondary);font-size:14px;">{{ $method->estimated_days }} hari</td>
                        <td style="padding:16px 20px;text-align:right;color:var(--text);font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:14px;">Rp {{ number_format($method->base_price, 0, ',', '.') }}</td>
                        <td style="padding:16px 20px;text-align:right;color:var(--text);font-family:'Space Grotesk',sans-serif;font-size:14px;">Rp {{ number_format($method->price_per_kg, 0, ',', '.') }}</td>
                        <td style="padding:16px 20px;text-align:center;">
                            <form action="{{ route('admin.shipping.toggleMethod', $method->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;border:none;cursor:pointer;background:{{ $method->is_active ? 'rgba(34,197,94,0.12)' : 'rgba(239,68,68,0.12)' }};color:{{ $method->is_active ? '#4ade80' : '#ef4444' }};">
                                    {{ $method->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td style="padding:16px 20px;text-align:center;">
                            <form action="{{ route('admin.shipping.destroyMethod', $method->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus metode ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding:5px 12px;border-radius:8px;font-size:12px;font-weight:600;border:none;cursor:pointer;background:rgba(239,68,68,0.1);color:#ef4444;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div style="margin-top:24px;padding:20px;background:rgba(251,191,36,0.06);border:1px solid rgba(251,191,36,0.15);border-radius:12px;">
        <div style="display:flex;align-items:flex-start;gap:12px;">
            <svg style="width:20px;height:20px;color:#fbbf24;flex-shrink:0;margin-top:2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p style="font-size:13px;color:#fbbf24;font-weight:600;margin:0 0 4px;">Mode Development</p>
                <p style="font-size:12px;color:var(--text-secondary);margin:0;">Harga ongkir saat ini menggunakan perhitungan simulasi (base_price + price_per_kg × berat). Untuk integrasi API kurir real (RajaOngkir/Biteship), ubah <code style="background:rgba(255,255,255,0.08);padding:2px 6px;border-radius:4px;font-size:11px;">SHIPPING_DRIVER</code> di file <code style="background:rgba(255,255,255,0.08);padding:2px 6px;border-radius:4px;font-size:11px;">.env</code>.</p>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .shipping-settings-grid { grid-template-columns: 1fr !important; }
}
</style>
@endsection
