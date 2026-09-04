@extends('layouts.admin')

@section('title', 'Tambah Voucher')
@section('page-title', 'Tambah Voucher Baru')

@section('content')
<div style="max-width:640px;">
    <form action="{{ route('admin.vouchers.store') }}" method="POST">
        @csrf
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:32px;">
            <div style="display:flex;flex-direction:column;gap:20px;">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Kode Voucher</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="form-input" style="text-transform:uppercase;" required placeholder="e.g. DISKON10">
                        @error('code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipe</label>
                        <select name="type" class="form-input form-select" required>
                            <option value="percentage" {{ old('type')=='percentage'?'selected':'' }}>Persen (%)</option>
                            <option value="fixed" {{ old('type')=='fixed'?'selected':'' }}>Nominal (Rp)</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nilai</label>
                        <input type="number" name="value" value="{{ old('value') }}" class="form-input" min="0" required placeholder="e.g. 10">
                        @error('value')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Min. Order (Rp)</label>
                        <input type="number" name="minimum_order" value="{{ old('minimum_order', 0) }}" class="form-input" min="0" required>
                        @error('minimum_order')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Max. Discount (Rp) <span style="color:var(--text-secondary);font-weight:400;">(opsional)</span></label>
                    <input type="number" name="maximum_discount" value="{{ old('maximum_discount') }}" class="form-input" min="0" placeholder="Leave empty for no limit">
                    @error('maximum_discount')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Berlaku Dari</label>
                        <input type="date" name="starts_at" value="{{ old('starts_at') }}" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Berlaku Sampai</label>
                        <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="form-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Batas Penggunaan <span style="color:var(--text-secondary);font-weight:400;">(opsional)</span></label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" class="form-input" min="1" placeholder="Unlimited">
                        @error('usage_limit')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="display:flex;align-items:flex-end;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 0;">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--cyan);">
                            <span style="font-size:14px;color:var(--text);">Active</span>
                        </label>
                    </div>
                </div>

                <div style="display:flex;gap:12px;padding-top:8px;">
                    <a href="{{ route('admin.vouchers.index') }}" style="flex:1;padding:12px;text-align:center;background:var(--bg-deep);border:1px solid var(--border);border-radius:10px;color:var(--text-secondary);font-size:14px;font-weight:600;text-decoration:none;transition:all .25s;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)'" onmouseout="this.style.borderColor='var(--border)'">Cancel</a>
                    <button type="submit" class="btn-primary" style="flex:1;padding:12px;">Create Voucher</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
