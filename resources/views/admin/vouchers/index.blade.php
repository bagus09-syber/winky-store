@extends('layouts.admin')

@section('title', 'Voucher')
@section('page-title', 'Manajemen Voucher')

@section('content')
<div style="display:flex;flex-direction:column;gap:24px;">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <form action="{{ route('admin.vouchers.index') }}" method="GET" style="display:flex;align-items:center;gap:10px;">
            <div style="position:relative;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vouchers..." class="form-input" style="padding-left:36px;width:240px;">
                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="btn-secondary" style="display:flex;align-items:center;gap:6px;">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Filter
            </button>
        </form>
        <a href="{{ route('admin.vouchers.create') }}" class="btn-primary" style="display:flex;align-items:center;gap:8px;padding:10px 20px;text-decoration:none;">
            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Voucher
        </a>
    </div>

    <div class="table-container">
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tipe</th>
                        <th>Nilai</th>
                        <th>Min. Order</th>
                        <th>Penggunaan</th>
                        <th>Berlaku</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $voucher)
                    <tr>
                        <td class="font-medium text-white" style="font-family:monospace;">{{ $voucher->code }}</td>
                        <td>{{ $voucher->type === 'percentage' ? 'Persen' : 'Nominal' }}</td>
                        <td class="text-[var(--color-cyan-400)]">
                            @if($voucher->type === 'percentage')
                            {{ $voucher->value }}%
                            @else
                            Rp {{ number_format($voucher->value, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>Rp {{ number_format($voucher->minimum_order, 0, ',', '.') }}</td>
                        <td>{{ $voucher->used_count }}{{ $voucher->usage_limit ? ' / ' . $voucher->usage_limit : '' }}</td>
                        <td class="text-white/50 text-sm">
                            {{ $voucher->starts_at ? $voucher->starts_at->format('d M Y') : '-' }} — {{ $voucher->expires_at ? $voucher->expires_at->format('d M Y') : '∞' }}
                        </td>
                        <td>
                            <form action="{{ route('admin.vouchers.toggle', $voucher) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="display:inline-flex;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;border:none;
                                    @if($voucher->is_active) background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);
                                    @else background:rgba(148,163,184,0.1);color:#94a3b8;border:1px solid rgba(148,163,184,0.2); @endif">
                                    {{ $voucher->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div style="display:flex;gap:8px;">
                                <a href="{{ route('admin.vouchers.edit', $voucher) }}" style="color:var(--cyan);font-size:13px;font-weight:600;text-decoration:none;">Edit</a>
                                <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Delete this voucher?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color:#f87171;font-size:13px;font-weight:600;cursor:pointer;background:none;border:none;padding:0;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-white/40 py-8">Tidak ada voucher ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($vouchers->hasPages())
    <div>
        {{ $vouchers->links() }}
    </div>
    @endif
</div>
@endsection
