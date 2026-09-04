@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')

@section('content')
<div style="display:flex;flex-direction:column;gap:24px;">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <form action="{{ route('admin.orders.index') }}" method="GET" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <div style="position:relative;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..." class="form-input" style="padding-left:36px;width:240px;">
                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <select name="status" class="form-input form-select" style="width:180px;">
                <option value="">All Status</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Menunggu</option>
                <option value="awaiting_payment" {{ request('status')=='awaiting_payment'?'selected':'' }}>Menunggu Pembayaran</option>
                <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Dibayar</option>
                <option value="processing" {{ request('status')=='processing'?'selected':'' }}>Diproses</option>
                <option value="shipped" {{ request('status')=='shipped'?'selected':'' }}>Dikirim</option>
                <option value="delivered" {{ request('status')=='delivered'?'selected':'' }}>Diterima</option>
                <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Selesai</option>
                <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Dibatalkan</option>
            </select>
            <select name="payment_status" class="form-input form-select" style="width:160px;">
                <option value="">All Payments</option>
                <option value="pending" {{ request('payment_status')=='pending'?'selected':'' }}>Menunggu</option>
                <option value="paid" {{ request('payment_status')=='paid'?'selected':'' }}>Lunas</option>
                <option value="failed" {{ request('payment_status')=='failed'?'selected':'' }}>Gagal</option>
                <option value="unpaid" {{ request('payment_status')=='unpaid'?'selected':'' }}>Belum Bayar</option>
            </select>
            <button type="submit" class="btn-secondary" style="display:flex;align-items:center;gap:6px;">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Filter
            </button>
        </form>
    </div>

    <div class="table-container">
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="font-medium text-white">{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? '-' }}</td>
                        <td class="text-[var(--color-cyan-400)]">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            @php
                            $sc = $order->status_color;
                            $statusStyles = [
                                'yellow' => 'background:rgba(250,204,21,0.1);color:#facc15;border:1px solid rgba(250,204,21,0.2);',
                                'orange' => 'background:rgba(255,152,0,0.1);color:#ffb74d;border:1px solid rgba(255,152,0,0.2);',
                                'cyan' => 'background:rgba(0,229,255,0.1);color:var(--cyan);border:1px solid rgba(0,229,255,0.2);',
                                'blue' => 'background:rgba(41,121,255,0.1);color:#60a5fa;border:1px solid rgba(41,121,255,0.2);',
                                'purple' => 'background:rgba(168,85,247,0.1);color:#c084fc;border:1px solid rgba(168,85,247,0.2);',
                                'green' => 'background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);',
                                'red' => 'background:rgba(239,68,68,0.1);color:#f87171;border:1px solid rgba(239,68,68,0.2);',
                            ];
                            @endphp
                            <span style="display:inline-flex;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;{{ $statusStyles[$sc] ?? '' }}">{{ $order->status_label }}</span>
                        </td>
                        <td>
                            @if($order->payment)
                                @if($order->payment->status === 'paid')
                                <span style="display:inline-flex;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);">Lunas</span>
                                @else
                                <span style="display:inline-flex;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:rgba(250,204,21,0.1);color:#facc15;border:1px solid rgba(250,204,21,0.2);">{{ $order->payment->status_label }}</span>
                                @endif
                            @else
                            <span style="display:inline-flex;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:rgba(148,163,184,0.1);color:#94a3b8;border:1px solid rgba(148,163,184,0.2);">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="text-white/50">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->order_number) }}" style="color:var(--cyan);font-size:13px;font-weight:600;text-decoration:none;">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-white/40 py-8">Tidak ada pesanan ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
    <div>
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
