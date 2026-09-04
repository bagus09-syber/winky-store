@extends('layouts.admin')

@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')
@section('page-title', isset($product) ? 'Edit Produk' : 'Tambah Produk Baru')

@section('content')
<div class="max-w-3xl">
    <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
        @method('PUT')
        @endif

        <div class="space-y-6">
            {{-- Basic Info --}}
            <div class="bg-[var(--color-navy-800)] border border-white/5 rounded-2xl p-6">
                <h3 class="font-display font-semibold text-white mb-4">Informasi Dasar</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-white/60 text-sm mb-2">Nama Produk *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="form-input" required>
                        @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white/60 text-sm mb-2">Kategori *</label>
                            <select name="category_id" class="form-input form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-white/60 text-sm mb-2">Brand *</label>
                            <select name="brand_id" class="form-input form-select" required>
                                <option value="">Pilih Brand</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">SKU *</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="form-input" required>
                        @error('sku')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Deskripsi Singkat</label>
                        <input type="text" name="short_description" value="{{ old('short_description', $product->short_description ?? '') }}" class="form-input">
                        @error('short_description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Deskripsi Lengkap</label>
                        <textarea name="description" rows="5" class="form-input">{{ old('description', $product->description ?? '') }}</textarea>
                        @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="bg-[var(--color-navy-800)] border border-white/5 rounded-2xl p-6">
                <h3 class="font-display font-semibold text-white mb-4">Harga & Stok</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-white/60 text-sm mb-2">Harga Normal (Rp) *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" class="form-input" min="0" required>
                        @error('price')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Harga Sale (Rp)</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}" class="form-input" min="0">
                        @error('sale_price')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Stok *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" class="form-input" min="0" required>
                        @error('stock')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Shipping --}}
            <div class="bg-[var(--color-navy-800)] border border-white/5 rounded-2xl p-6">
                <h3 class="font-display font-semibold text-white mb-4">📦 Shipping Information</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-white/60 text-sm mb-2">Berat Produk (gram) *</label>
                        <input type="number" name="weight" value="{{ old('weight', $product->weight ?? 500) }}" class="form-input" min="1" required>
                        @error('weight')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Panjang (cm)</label>
                        <input type="number" name="length" value="{{ old('length', $product->length ?? '') }}" class="form-input" min="0">
                        @error('length')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Lebar (cm)</label>
                        <input type="number" name="width" value="{{ old('width', $product->width ?? '') }}" class="form-input" min="0">
                        @error('width')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Tinggi (cm)</label>
                        <input type="number" name="height" value="{{ old('height', $product->height ?? '') }}" class="form-input" min="0">
                        @error('height')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <p class="text-white/40 text-xs mt-3">Berat default 500g. Dimensi opsional untuk perhitungan volume.</p>
            </div>

            {{-- Image --}}
            <div class="bg-[var(--color-navy-800)] border border-white/5 rounded-2xl p-6">
                <h3 class="font-display font-semibold text-white mb-4">Gambar Produk</h3>

                <div>
                    <label class="block text-white/60 text-sm mb-2">Gambar Utama</label>
                    <input type="file" name="image" accept="image/*" class="form-input">
                    @error('image')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    @if(isset($product) && $product->image)
                    <div class="mt-4">
                        <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-contain rounded-xl">
                    </div>
                    @endif
                </div>
            </div>

            {{-- Status --}}
            <div class="bg-[var(--color-navy-800)] border border-white/5 rounded-2xl p-6">
                <h3 class="font-display font-semibold text-white mb-4">Status</h3>

                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded border-white/20 bg-[var(--color-navy-900)] text-[var(--color-cyan-400)] focus:ring-[var(--color-cyan-400)]">
                        <span class="text-white">Produk Featured</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded border-white/20 bg-[var(--color-navy-900)] text-[var(--color-cyan-400)] focus:ring-[var(--color-cyan-400)]">
                        <span class="text-white">Aktif</span>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-4">
                <button type="submit" class="btn-primary">
                    {{ isset($product) ? 'Perbarui Produk' : 'Tambah Produk' }}
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
