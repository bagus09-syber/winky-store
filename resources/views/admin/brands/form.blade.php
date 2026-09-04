@extends('layouts.admin')

@section('title', isset($brand) ? 'Edit Brand' : 'Tambah Brand')
@section('page-title', isset($brand) ? 'Edit Brand' : 'Tambah Brand Baru')

@section('content')
<div class="max-w-2xl">
    <form action="{{ isset($brand) ? route('admin.brands.update', $brand) : route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($brand))
        @method('PUT')
        @endif

        <div class="space-y-6">
            <div class="bg-[var(--color-navy-800)] border border-white/5 rounded-2xl p-6">
                <h3 class="font-display font-semibold text-white mb-4">Informasi Brand</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-white/60 text-sm mb-2">Nama Brand *</label>
                        <input type="text" name="name" value="{{ old('name', $brand->name ?? '') }}" class="form-input" required>
                        @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" class="form-input">{{ old('description', $brand->description ?? '') }}</textarea>
                        @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Logo</label>
                        <input type="file" name="logo" accept="image/*" class="form-input">
                        @error('logo')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        @if(isset($brand) && $brand->logo)
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-32 h-32 object-cover rounded-xl">
                        </div>
                        @endif
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $brand->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded border-white/20 bg-[var(--color-navy-900)] text-[var(--color-cyan-400)] focus:ring-[var(--color-cyan-400)]">
                        <span class="text-white">Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="btn-primary">
                    {{ isset($brand) ? 'Perbarui Brand' : 'Tambah Brand' }}
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
