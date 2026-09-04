@extends('layouts.admin')

@section('title', isset($category) ? 'Edit Kategori' : 'Tambah Kategori')
@section('page-title', isset($category) ? 'Edit: ' . $category->name : 'Tambah Kategori Baru')

@section('content')
<div class="max-w-3xl">
    <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($category))
        @method('PUT')
        @endif

        <div class="space-y-6">
            <div class="bg-[var(--color-navy-800)] border border-white/5 rounded-2xl p-6">
                <h3 class="font-display font-semibold text-white mb-4">Informasi Kategori</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-white/60 text-sm mb-2">Nama Kategori *</label>
                        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="form-input" required>
                        @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Kategori Utama (Parent)</label>
                        <select name="parent_id" class="form-input">
                            <option value="">— Kategori Utama (Root) —</option>
                            @foreach($parentCategories as $pc)
                            <option value="{{ $pc->id }}" {{ old('parent_id', $category->parent_id ?? '') == $pc->id ? 'selected' : '' }}>
                                {{ $pc->name }}
                            </option>
                            @endforeach
                        </select>
                        <p class="text-white/30 text-xs mt-1">Kosongkan untuk membuat kategori utama baru</p>
                        @error('parent_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Icon SVG Path</label>
                        <input type="text" name="icon" value="{{ old('icon', $category->icon ?? '') }}" class="form-input" placeholder="M12 18h.01M8 21h8a2 2 0 002-2V5...">
                        <p class="text-white/30 text-xs mt-1">SVG path data tanpa tag. Contoh: M12 18h.01M8 21h8...</p>
                        @error('icon')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if(isset($category) && $category->icon)
                        <div class="mt-2 flex items-center gap-2">
                            <svg class="w-6 h-6 text-[var(--color-cyan-400)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $category->icon }}"/></svg>
                            <span class="text-xs text-white/40">Preview</span>
                        </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" class="form-input">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white/60 text-sm mb-2">Gambar (opsional)</label>
                        <input type="file" name="image" accept="image/*" class="form-input">
                        @error('image')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        @if(isset($category) && $category->image)
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-32 h-32 object-cover rounded-xl">
                        </div>
                        @endif
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded border-white/20 bg-[var(--color-navy-900)] text-[var(--color-cyan-400)] focus:ring-[var(--color-cyan-400)]">
                        <span class="text-white">Aktif</span>
                    </label>
                </div>
            </div>

            {{-- Children list (edit mode only) --}}
            @if(isset($category) && isset($children) && $children->count() > 0)
            <div class="bg-[var(--color-navy-800)] border border-white/5 rounded-2xl p-6">
                <h3 class="font-display font-semibold text-white mb-4">Subkategori ({{ $children->count() }})</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($children as $child)
                    <a href="{{ route('admin.categories.edit', $child) }}" class="flex items-center gap-2 p-3 bg-[var(--color-navy-900)] rounded-xl border border-white/5 hover:border-[var(--color-cyan-400)]/30 transition-all text-sm text-white/80 hover:text-[var(--color-cyan-400)]">
                        @if($child->icon)
                        <svg class="w-4 h-4 flex-shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $child->icon }}"/></svg>
                        @else
                        <span class="w-4 h-4 flex-shrink-0"></span>
                        @endif
                        <span class="truncate">{{ $child->name }}</span>
                        <span class="text-white/30 text-xs ml-auto">({{ $child->products_count }})</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex items-center gap-4">
                <button type="submit" class="btn-primary">
                    {{ isset($category) ? 'Perbarui Kategori' : 'Tambah Kategori' }}
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
