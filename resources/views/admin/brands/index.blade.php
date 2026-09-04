@extends('layouts.admin')

@section('title', 'Brand')
@section('page-title', 'Manajemen Brand')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <form action="{{ route('admin.brands.index') }}" method="GET" class="flex items-center gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari brand..." class="form-input w-64">
        <button type="submit" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>
    </form>
    <a href="{{ route('admin.brands.create') }}" class="btn-primary inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Brand
    </a>
</div>

<div class="table-container">
    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Slug</th>
                    <th>Jumlah Produk</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[var(--color-navy-700)] overflow-hidden flex-shrink-0">
                                @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <span class="font-medium text-white">{{ $brand->name }}</span>
                        </div>
                    </td>
                    <td class="font-mono text-sm text-white/60">{{ $brand->slug }}</td>
                    <td>{{ $brand->products_count }} produk</td>
                    <td>
                        <span class="badge {{ $brand->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $brand->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="text-[var(--color-cyan-400)] hover:underline text-sm">Edit</a>
                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Yakin hapus brand ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:underline text-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-white/40 py-8">Tidak ada brand ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($brands->hasPages())
<div class="mt-6">
    {{ $brands->links() }}
</div>
@endif
@endsection
