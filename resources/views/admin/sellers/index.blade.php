@extends('layouts.admin')
@section('title', 'Manajemen Seller - WINKY STORE')
@section('page-title', 'Manajemen Seller')

@section('content')
<div class="flex flex-wrap gap-3 mb-6">
    <form action="{{ route('admin.sellers.index') }}" method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari seller..." class="form-input" style="width:240px;">
        <select name="status" class="form-input form-select" style="width:160px;">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <button type="submit" class="btn-secondary text-sm">Filter</button>
    </form>
</div>

@if($sellers->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Toko</th>
                <th>Pemilik</th>
                <th>Lokasi</th>
                <th>Rating</th>
                <th>Produk</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sellers as $seller)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        @if($seller->logo)
                        <img src="{{ asset('storage/' . $seller->logo) }}" style="width:40px;height:40px;object-fit:cover;border-radius:10px;">
                        @else
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,var(--color-blue-600),var(--color-cyan-500));border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;">{{ substr($seller->name, 0, 1) }}</div>
                        @endif
                        <div>
                            <p class="text-white font-medium text-sm">{{ $seller->name }}</p>
                            <p class="text-white/40 text-xs">{{ $seller->slug }}</p>
                        </div>
                    </div>
                </td>
                <td class="text-white/80 text-sm">{{ $seller->user->name }}</td>
                <td class="text-white/60 text-sm">{{ $seller->city }}, {{ $seller->province }}</td>
                <td class="text-yellow-400 font-semibold text-sm">{{ $seller->rating }} ★</td>
                <td class="text-white/60 text-sm">{{ $seller->products()->count() }}</td>
                <td>
                    @php
                    $statusColors = ['pending' => 'yellow', 'active' => 'green', 'suspended' => 'red', 'rejected' => 'red'];
                    @endphp
                    <span class="badge badge-{{ $statusColors[$seller->status] ?? 'gray' }}">{{ ucfirst($seller->status) }}</span>
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.sellers.show', $seller->id) }}" class="text-white/60 hover:text-[var(--cyan)]" title="Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        @if($seller->status === 'pending')
                        <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-white/60 hover:text-emerald-400" title="Approve">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </form>
                        <form action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-white/60 hover:text-red-400" title="Reject">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                        @endif
                        @if($seller->status === 'active')
                        <form action="{{ route('admin.sellers.suspend', $seller->id) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-white/60 hover:text-yellow-400" title="Suspend">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $sellers->links() }}</div>
@else
<div class="stat-card text-center py-16">
    <p class="text-white/40">Belum ada seller</p>
</div>
@endif
@endsection
