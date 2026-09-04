@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <form action="{{ route('admin.categories.index') }}" method="GET" class="flex items-center gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="form-input w-56">
        <select name="parent_id" class="form-input w-48" onchange="this.form.submit()">
            <option value="">Semua Kategori Utama</option>
            @foreach($parentCategories as $pc)
            <option value="{{ $pc->id }}" {{ request('parent_id') == $pc->id ? 'selected' : '' }}>{{ $pc->name }}</option>
            @endforeach
        </select>
        @if(request('parent_id'))
        <a href="{{ route('admin.categories.index', ['show_all' => 1]) }}" class="btn-secondary text-xs">Tampilkan Semua</a>
        @endif
        <button type="submit" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>
    </form>
    <a href="{{ route('admin.categories.create') }}" class="btn-primary inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kategori
    </a>
</div>

<div class="table-container">
    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Parent</th>
                    <th>Slug</th>
                    <th>Icon</th>
                    <th>Sub</th>
                    <th>Produk</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[var(--color-navy-700)] overflow-hidden flex-shrink-0 flex items-center justify-center">
                                @if($category->icon)
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $category->icon }}"/></svg>
                                @elseif($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                @else
                                <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                @endif
                            </div>
                            <div>
                                <span class="font-medium text-white">{{ $category->name }}</span>
                                @if(!$category->parent_id && $category->children_count > 0)
                                <div class="text-xs text-white/40 mt-0.5">{{ $category->children_count }} subkategori</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($category->parent)
                        <a href="{{ route('admin.categories.index', ['parent_id' => $category->parent_id]) }}" class="text-[var(--color-cyan-400)] hover:underline text-sm">{{ $category->parent->name }}</a>
                        @else
                        <span class="text-white/30 text-sm">—</span>
                        @endif
                    </td>
                    <td class="font-mono text-sm text-white/60">{{ $category->slug }}</td>
                    <td>
                        @if($category->icon)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $category->icon }}"/></svg>
                            <span class="text-xs text-white/30">SVG</span>
                        </div>
                        @else
                        <span class="text-white/20 text-xs">—</span>
                        @endif
                    </td>
                    <td>{{ $category->children_count ?? 0 }}</td>
                    <td>{{ $category->products_count }} produk</td>
                    <td>
                        <span class="badge {{ $category->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-[var(--color-cyan-400)] hover:underline text-sm">Edit</a>
                            @if(!$category->parent_id)
                            <a href="{{ route('admin.categories.index', ['parent_id' => $category->id]) }}" class="text-blue-400 hover:underline text-sm">Lihat Sub</a>
                            @endif
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin hapus? Anak kategori akan dipindahkan ke parent.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:underline text-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-white/40 py-8">Tidak ada kategori ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($categories->hasPages())
<div class="mt-6">
    {{ $categories->links() }}
</div>
@endif
@endsection
