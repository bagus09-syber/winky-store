@extends('layouts.seller')
@section('title', ($product->name ?? 'Tambah') . ' Produk - WINKY STORE')
@section('page-title', isset($product) ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div style="max-width:800px;">
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:14px;padding:16px;margin-bottom:24px;">
        @foreach($errors->all() as $error)
        <p style="color:#f87171;font-size:13px;">{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form action="{{ isset($product) ? route('seller.products.update', $product->id) : route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="stat-card">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="form-input" placeholder="Nama produk">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">SKU *</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required class="form-input" placeholder="SKU unik">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Kategori *</label>
                <select name="category_id" required class="form-input form-select">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Brand *</label>
                <select name="brand_id" required class="form-input form-select">
                    <option value="">Pilih Brand</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Deskripsi Singkat</label>
            <input type="text" name="short_description" value="{{ old('short_description', $product->short_description ?? '') }}" class="form-input" placeholder="Deskripsi singkat produk">
        </div>

        <div class="mb-6">
            <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Deskripsi Lengkap</label>
            <textarea name="description" rows="6" class="form-input" style="resize:vertical;" placeholder="Deskripsi lengkap produk">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Harga (Rp) *</label>
                <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" required min="0" class="form-input" placeholder="0">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Harga Sale (Rp)</label>
                <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}" min="0" class="form-input" placeholder="Kosongkan jika tidak ada">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Stok *</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required min="0" class="form-input" placeholder="0">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Berat (gram) *</label>
                <input type="number" name="weight" value="{{ old('weight', $product->weight ?? 500) }}" required min="1" class="form-input">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Panjang (cm)</label>
                <input type="number" name="length" value="{{ old('length', $product->length ?? '') }}" min="0" class="form-input">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Lebar (cm)</label>
                <input type="number" name="width" value="{{ old('width', $product->width ?? '') }}" min="0" class="form-input">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Tinggi (cm)</label>
                <input type="number" name="height" value="{{ old('height', $product->height ?? '') }}" min="0" class="form-input">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Gambar Produk</label>
                <input type="file" name="image" accept="image/*" class="form-input" style="padding:10px;">
                @if(isset($product) && $product->image)
                <img src="{{ asset('storage/' . $product->image) }}" style="width:80px;height:80px;object-fit:cover;border-radius:10px;margin-top:8px;">
                @endif
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;">Status</label>
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:12px 0;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--cyan);">
                    <span style="font-size:14px;color:rgba(255,255,255,0.7);">Aktifkan produk</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">{{ isset($product) ? 'Simpan Perubahan' : 'Tambah Produk' }}</button>
            <a href="{{ route('seller.products.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
