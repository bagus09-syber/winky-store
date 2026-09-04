@extends('layouts.admin')
@section('title', 'Return Requests')
@section('page-title', 'Return Requests')

@section('content')
<div style="padding:24px;max-width:1200px;">

    @if(session('success'))
    <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:12px;padding:16px 20px;margin-bottom:24px;">
        <span style="font-size:13px;color:#4ade80;">{{ session('success') }}</span>
    </div>
    @endif

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <form action="{{ route('admin.returns.index') }}" method="GET" style="display:flex;gap:8px;flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="form-input" style="width:200px;">
            <select name="status" class="form-input form-select" style="width:140px;">
                <option value="">Semua Status</option>
                @foreach(['requested', 'approved', 'rejected', 'received', 'refunded'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary" style="padding:8px 16px;">Filter</button>
        </form>
    </div>

    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;">
        @if($returns->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--border);">
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">ID</th>
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">User</th>
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">Order</th>
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">Reason</th>
                        <th style="padding:14px 16px;text-align:center;font-size:12px;font-weight:600;color:var(--text-secondary);">Status</th>
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">Date</th>
                        <th style="padding:14px 16px;text-align:center;font-size:12px;font-weight:600;color:var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $return)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:14px 16px;font-size:13px;color:var(--text-secondary);">#{{ $return->id }}</td>
                        <td style="padding:14px 16px;font-size:13px;color:var(--text);">{{ $return->user->name ?? '-' }}</td>
                        <td style="padding:14px 16px;font-size:13px;color:var(--text);">{{ $return->order->order_number ?? '-' }}</td>
                        <td style="padding:14px 16px;font-size:13px;color:var(--text-secondary);max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $return->reason }}</td>
                        <td style="padding:14px 16px;text-align:center;">
                            @php $sc = $return->status_color; @endphp
                            <span style="padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:{{ match($sc) { 'yellow' => 'rgba(250,204,21,0.1)', 'blue' => 'rgba(41,121,255,0.1)', 'red' => 'rgba(239,68,68,0.1)', 'cyan' => 'rgba(0,229,255,0.1)', 'green' => 'rgba(34,197,94,0.1)', default => 'rgba(156,163,175,0.1)' }};color:{{ match($sc) { 'yellow' => '#facc15', 'blue' => '#60a5fa', 'red' => '#f87171', 'cyan' => 'var(--cyan)', 'green' => '#4ade80', default => '#9ca3af' }}};">{{ $return->status_label }}</span>
                        </td>
                        <td style="padding:14px 16px;font-size:13px;color:var(--text-secondary);">{{ $return->created_at->format('d M Y') }}</td>
                        <td style="padding:14px 16px;text-align:center;">
                            <a href="{{ route('admin.returns.show', $return->id) }}" style="padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;background:rgba(0,229,255,0.1);color:var(--cyan);text-decoration:none;">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px;">{{ $returns->links() }}</div>
        @else
        <div style="padding:60px;text-align:center;">
            <p style="font-size:14px;color:var(--text-secondary);">Belum ada return request.</p>
        </div>
        @endif
    </div>
</div>
@endsection
