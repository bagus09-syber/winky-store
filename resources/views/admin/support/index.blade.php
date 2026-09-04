@extends('layouts.admin')
@section('title', 'Support Tickets')
@section('page-title', 'Support Tickets')

@section('content')
<div style="padding:24px;max-width:1200px;">

    @if(session('success'))
    <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:12px;padding:16px 20px;margin-bottom:24px;">
        <span style="font-size:13px;color:#4ade80;">{{ session('success') }}</span>
    </div>
    @endif

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <form action="{{ route('admin.support.index') }}" method="GET" style="display:flex;gap:8px;flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tiket..." class="form-input" style="width:200px;">
            <select name="status" class="form-input form-select" style="width:140px;">
                <option value="">Semua Status</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            <button type="submit" class="btn-primary" style="padding:8px 16px;">Filter</button>
        </form>
    </div>

    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;">
        @if($tickets->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--border);">
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">ID</th>
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">Name</th>
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">Subject</th>
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">Order</th>
                        <th style="padding:14px 16px;text-align:center;font-size:12px;font-weight:600;color:var(--text-secondary);">Status</th>
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">Date</th>
                        <th style="padding:14px 16px;text-align:center;font-size:12px;font-weight:600;color:var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:14px 16px;font-size:13px;color:var(--text-secondary);">#{{ $ticket->id }}</td>
                        <td style="padding:14px 16px;font-size:13px;color:var(--text);">{{ $ticket->name }}</td>
                        <td style="padding:14px 16px;font-size:13px;color:var(--text);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $ticket->subject }}</td>
                        <td style="padding:14px 16px;font-size:13px;color:var(--text-secondary);">{{ $ticket->order->order_number ?? '—' }}</td>
                        <td style="padding:14px 16px;text-align:center;">
                            @php
                                $sc = $ticket->status_color;
                                $ss = ['yellow' => 'rgba(250,204,21,0.1);color:#facc15;border:1px solid rgba(250,204,21,0.2);', 'blue' => 'rgba(41,121,255,0.1);color:#60a5fa;border:1px solid rgba(41,121,255,0.2);', 'green' => 'rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);', 'gray' => 'rgba(156,163,175,0.1);color:#9ca3af;border:1px solid rgba(156,163,175,0.2);'];
                            @endphp
                            <span style="padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:{{ $ss[$sc] ?? $ss['gray'] }}">{{ $ticket->status_label }}</span>
                        </td>
                        <td style="padding:14px 16px;font-size:13px;color:var(--text-secondary);">{{ $ticket->created_at->format('d M Y') }}</td>
                        <td style="padding:14px 16px;text-align:center;">
                            <a href="{{ route('admin.support.show', $ticket->id) }}" style="padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;background:rgba(0,229,255,0.1);color:var(--cyan);text-decoration:none;">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px;">{{ $tickets->links() }}</div>
        @else
        <div style="padding:60px;text-align:center;">
            <p style="font-size:14px;color:var(--text-secondary);">Belum ada tiket support.</p>
        </div>
        @endif
    </div>
</div>
@endsection
