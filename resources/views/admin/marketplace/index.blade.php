@extends('layouts.admin')
@section('title', 'Pengaturan Marketplace - WINKY STORE')
@section('page-title', 'Pengaturan Marketplace')

@section('content')
<div style="max-width:640px;">
    <form action="{{ route('admin.marketplace.update') }}" method="POST" class="stat-card">
        @csrf @method('PUT')

        <h3 class="text-white font-semibold mb-6">Komisi Marketplace</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Tipe Komisi</label>
                <select name="commission_type" class="form-input form-select">
                    <option value="percentage" {{ $settings['commission_type'] == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                    <option value="fixed" {{ $settings['commission_type'] == 'fixed' ? 'selected' : '' }}>Fixed (Rp)</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Nilai Komisi</label>
                <input type="number" name="commission_value" value="{{ $settings['commission_value'] }}" min="0" step="0.01" class="form-input">
            </div>
        </div>

        <h3 class="text-white font-semibold mb-6">Penarikan Dana</h3>

        <div class="mb-6">
            <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Minimal Penarikan (Rp)</label>
            <input type="number" name="min_withdrawal" value="{{ $settings['min_withdrawal'] }}" min="0" class="form-input">
        </div>

        <h3 class="text-white font-semibold mb-6">Stok</h3>

        <div class="mb-6">
            <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Threshold Stok Rendah</label>
            <input type="number" name="low_stock_threshold" value="{{ $settings['low_stock_threshold'] }}" min="1" class="form-input">
        </div>

        <button type="submit" class="btn-primary">Simpan Pengaturan</button>
    </form>
</div>
@endsection
