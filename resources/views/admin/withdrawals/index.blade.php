@extends('layouts.admin')
@section('title', 'Penarikan Dana - WINKY STORE')
@section('page-title', 'Penarikan Dana')

@section('content')
<div class="flex flex-wrap gap-3 mb-6">
    <form action="{{ route('admin.withdrawals.index') }}" method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari seller..." class="form-input" style="width:240px;">
        <select name="status" class="form-input form-select" style="width:160px;">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
        <button type="submit" class="btn-secondary text-sm">Filter</button>
    </form>
</div>

@if($withdrawals->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr><th>ID</th><th>Seller</th><th>Jumlah</th><th>Bank</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($withdrawals as $w)
            <tr>
                <td class="text-white/60 text-sm">#{{ $w->id }}</td>
                <td class="text-white font-medium text-sm">{{ $w->store->name }}</td>
                <td class="text-white font-semibold">Rp {{ number_format($w->amount, 0, ',', '.') }}</td>
                <td class="text-white/60 text-sm">{{ $w->bank_name }}</td>
                <td>
                    @php $wc = ['pending'=>'yellow','approved'=>'green','rejected'=>'red','paid'=>'blue']; @endphp
                    <span class="badge badge-{{ $wc[$w->status] ?? 'gray' }}">{{ ucfirst($w->status) }}</span>
                </td>
                <td class="text-white/50 text-sm">{{ $w->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.withdrawals.show', $w->id) }}" class="text-white/60 hover:text-[var(--cyan)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $withdrawals->links() }}</div>
@else
<div class="stat-card text-center py-16">
    <p class="text-white/40">Belum ada penarikan</p>
</div>
@endif
@endsection
