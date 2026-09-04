@extends('layouts.admin')
@section('title', 'Ticket #' . $ticket->id)
@section('page-title', 'Ticket Details')

@section('content')
<div style="padding:24px;max-width:800px;">

    @if(session('success'))
    <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:12px;padding:16px 20px;margin-bottom:24px;">
        <span style="font-size:13px;color:#4ade80;">{{ session('success') }}</span>
    </div>
    @endif

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:800;color:var(--text);margin:0;">Ticket #{{ $ticket->id }}</h1>
            <p style="font-size:13px;color:var(--text-secondary);margin-top:4px;">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
        </div>
        <a href="{{ route('admin.support.index') }}" style="padding:8px 16px;border-radius:10px;font-size:13px;background:var(--bg-card);border:1px solid var(--border);color:var(--text-secondary);text-decoration:none;">← Back</a>
    </div>

    <div style="display:flex;flex-direction:column;gap:20px;">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;">
            <h3 style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:16px;">Customer Info</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div><span style="font-size:12px;color:var(--text-secondary);">Name</span><br><span style="color:var(--text);">{{ $ticket->name }}</span></div>
                <div><span style="font-size:12px;color:var(--text-secondary);">Email</span><br><span style="color:var(--text);">{{ $ticket->email }}</span></div>
                <div><span style="font-size:12px;color:var(--text-secondary);">Order</span><br><span style="color:var(--text);">{{ $ticket->order->order_number ?? '—' }}</span></div>
                <div><span style="font-size:12px;color:var(--text-secondary);">Status</span><br>
                    @php $sc = $ticket->status_color; @endphp
                    <span style="padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;background:{{ match($sc) { 'yellow' => 'rgba(250,204,21,0.1)', 'blue' => 'rgba(41,121,255,0.1)', 'green' => 'rgba(34,197,94,0.1)', default => 'rgba(156,163,175,0.1)' }};color:{{ match($sc) { 'yellow' => '#facc15', 'blue' => '#60a5fa', 'green' => '#4ade80', default => '#9ca3af' }}};">{{ $ticket->status_label }}</span>
                </div>
            </div>
        </div>

        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;">
            <h3 style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:12px;">Message</h3>
            <div style="padding:16px;background:var(--bg-deep);border-radius:12px;border:1px solid var(--border);">
                <div style="font-weight:600;color:var(--text);margin-bottom:6px;">{{ $ticket->subject }}</div>
                <p style="font-size:14px;color:var(--text-secondary);line-height:1.7;">{{ $ticket->message }}</p>
            </div>
        </div>

        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;">
            <h3 style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:16px;">Reply / Update</h3>
            <form action="{{ route('admin.support.update', $ticket->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Status</label>
                        <select name="status" class="form-input form-select">
                            @foreach(['open', 'in_progress', 'resolved', 'closed'] as $s)
                            <option value="{{ $s }}" {{ $ticket->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Admin Response</label>
                        <textarea name="admin_response" class="form-input form-textarea" rows="4" placeholder="Tulis balasan...">{{ $ticket->admin_response }}</textarea>
                    </div>
                    <button type="submit" class="w-full btn-primary" style="padding:12px;border-radius:10px;">Update Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
