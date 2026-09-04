@extends('layouts.admin')
@section('title', 'Detail Penarikan - WINKY STORE')
@section('page-title', 'Detail Penarikan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="stat-card">
        <h3 class="text-white font-semibold mb-4">Detail Penarikan</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-white/50">ID</span><span class="text-white">#{{ $withdrawal->id }}</span></div>
            <div class="flex justify-between"><span class="text-white/50">Seller</span><span class="text-white">{{ $withdrawal->store->name }}</span></div>
            <div class="flex justify-between"><span class="text-white/50">Jumlah</span><span class="text-white font-semibold">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span class="text-white/50">Bank</span><span class="text-white">{{ $withdrawal->bank_name }}</span></div>
            <div class="flex justify-between"><span class="text-white/50">Nama Rekening</span><span class="text-white">{{ $withdrawal->account_name }}</span></div>
            <div class="flex justify-between"><span class="text-white/50">Nomor Rekening</span><span class="text-white font-mono">{{ $withdrawal->account_number }}</span></div>
            <div class="flex justify-between"><span class="text-white/50">Status</span>
                @php $wc = ['pending'=>'yellow','approved'=>'green','rejected'=>'red','paid'=>'blue']; @endphp
                <span class="badge badge-{{ $wc[$withdrawal->status] ?? 'gray' }}">{{ ucfirst($withdrawal->status) }}</span>
            </div>
            <div class="flex justify-between"><span class="text-white/50">Tanggal</span><span class="text-white">{{ $withdrawal->created_at->format('d M Y H:i') }}</span></div>
            @if($withdrawal->admin_notes)
            <div class="flex justify-between"><span class="text-white/50">Catatan</span><span class="text-white">{{ $withdrawal->admin_notes }}</span></div>
            @endif
            @if($withdrawal->processed_at)
            <div class="flex justify-between"><span class="text-white/50">Diproses</span><span class="text-white">{{ $withdrawal->processed_at->format('d M Y H:i') }}</span></div>
            @endif
        </div>
    </div>

    <div class="stat-card">
        <h3 class="text-white font-semibold mb-4">Aksi</h3>
        @if($withdrawal->status === 'pending')
        <div class="space-y-3">
            <form action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" method="POST" onsubmit="return confirm('Setujui penarikan ini?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn-primary w-full">Setujui Penarikan</button>
            </form>
            <form action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" method="POST" onsubmit="return confirm('Tolak penarikan ini?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn-secondary w-full text-red-400 border-red-500/20">Tolak Penarikan</button>
            </form>
        </div>
        @elseif($withdrawal->status === 'approved')
        <form action="{{ route('admin.withdrawals.pay', $withdrawal->id) }}" method="POST" onsubmit="return confirm('Tandai sebagai sudah dibayar?')">
            @csrf @method('PATCH')
            <button type="submit" class="btn-primary w-full">Tandai Sudah Dibayar</button>
        </form>
        @else
        <p class="text-white/40 text-sm">Tidak ada aksi yang tersedia</p>
        @endif

        <div class="mt-6">
            <a href="{{ route('admin.withdrawals.index') }}" class="btn-secondary w-full text-center">Kembali</a>
        </div>
    </div>
</div>
@endsection
