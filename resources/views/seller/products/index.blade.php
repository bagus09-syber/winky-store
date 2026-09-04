@extends('layouts.seller')
@section('title', 'Produk Saya - WINKY STORE')
@section('page-title', 'Produk Saya')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <form action="{{ route('seller.products.index') }}" method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="form-input" style="width:240px;">
        <select name="category" class="form-input form-select" style="width:180px;">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-input form-select" style="width:140px;">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="btn-secondary text-sm">Filter</button>
    </form>
    <a href="{{ route('seller.products.create') }}" class="btn-primary text-sm">+ Tambah Produk</a>
</div>

@if($products->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>SKU</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width:48px;height:48px;object-fit:cover;border-radius:10px;">
                        @else
                        <div style="width:48px;height:48px;background:var(--color-navy-700);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <svg class="w-5 h-5 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                        <div>
                            <p class="text-white font-medium text-sm">{{ $product->name }}</p>
                            <p class="text-white/40 text-xs">{{ $product->category->name ?? '-' }}</p>
                        </div>
                    </div>
                </td>
                <td class="text-white/60 font-mono text-sm">{{ $product->sku }}</td>
                <td>
                    @if($product->sale_price)
                    <span class="text-white/40 line-through text-xs">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="text-[var(--cyan)] font-semibold block">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                    @else
                    <span class="text-white font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                </td>
                <td>
                    @if($product->stock <= $lowStockThreshold && $product->stock > 0)
                    <span class="badge badge-yellow">{{ $product->stock }} (Rendah)</span>
                    @elseif($product->stock == 0)
                    <span class="badge badge-red">Habis</span>
                    @else
                    <span class="badge badge-green">{{ $product->stock }}</span>
                    @endif
                </td>
                <td>
                    @if($product->is_active)
                    <span class="badge badge-green">Aktif</span>
                    @else
                    <span class="badge badge-gray">Nonaktif</span>
                    @endif
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('seller.products.edit', $product->id) }}" class="text-white/60 hover:text-[var(--cyan)]" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('seller.products.toggle', $product->id) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-white/60 hover:text-yellow-400" title="Toggle Status">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </button>
                        </form>
                        <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-white/60 hover:text-red-400" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $products->links() }}</div>
@else
<div class="stat-card text-center py-16">
    <svg class="w-16 h-16 mx-auto text-white/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    <p class="text-white/40 mb-4">Belum ada produk</p>
    <a href="{{ route('seller.products.create') }}" class="btn-primary text-sm">+ Tambah Produk Pertama</a>
</div>
@endif
@endsection
