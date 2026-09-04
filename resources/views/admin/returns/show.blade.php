@extends('layouts.admin')
@section('title', 'Return #' . $return->id)
@section('page-title', 'Return Request Details')

@section('content')
<div style="padding:24px;max-width:800px;">

    @if(session('success'))
    <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:12px;padding:16px 20px;margin-bottom:24px;">
        <span style="font-size:13px;color:#4ade80;">{{ session('success') }}</span>
    </div>
    @endif

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:800;color:var(--text);margin:0;">Return #{{ $return->id }}</h1>
            <p style="font-size:13px;color:var(--text-secondary);margin-top:4px;">Order #{{ $return->order->order_number ?? '-' }}</p>
        </div>
        <a href="{{ route('admin.returns.index') }}" style="padding:8px 16px;border-radius:10px;font-size:13px;background:var(--bg-card);border:1px solid var(--border);color:var(--text-secondary);text-decoration:none;">← Back</a>
    </div>

    <div style="display:flex;flex-direction:column;gap:20px;">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;">
            <h3 style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:16px;">Return Info</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div><span style="font-size:12px;color:var(--text-secondary);">Customer</span><br><span style="color:var(--text);">{{ $return->user->name ?? '-' }}</span></div>
                <div><span style="font-size:12px;color:var(--text-secondary);">Status</span><br>
                    @php $sc = $return->status_color; @endphp
                    <span style="padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;background:{{ match($sc) { 'yellow' => 'rgba(250,204,21,0.1)', 'blue' => 'rgba(41,121,255,0.1)', 'red' => 'rgba(239,68,68,0.1)', 'cyan' => 'rgba(0,229,255,0.1)', 'green' => 'rgba(34,197,94,0.1)', default => 'rgba(156,163,175,0.1)' }};color:{{ match($sc) { 'yellow' => '#facc15', 'blue' => '#60a5fa', 'red' => '#f87171', 'cyan' => 'var(--cyan)', 'green' => '#4ade80', default => '#9ca3af' }}};">{{ $return->status_label }}</span>
                </div>
                <div style="grid-column:span 2;"><span style="font-size:12px;color:var(--text-secondary);">Reason</span><br><span style="color:var(--text);font-weight:600;">{{ $return->reason }}</span></div>
                @if($return->description)
                <div style="grid-column:span 2;"><span style="font-size:12px;color:var(--text-secondary);">Description</span><br><span style="color:var(--text-secondary);">{{ $return->description }}</span></div>
                @endif
            </div>
        </div>

        @if($return->order)
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;">
            <h3 style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:16px;">Order Items</h3>
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach($return->order->items as $item)
                <div style="display:flex;gap:12px;align-items:center;padding-bottom:12px;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                    <div style="width:44px;height:44px;border-radius:10px;background:var(--bg-deep);overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                        @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:contain;padding:4px;">
                        @else
                        <img src="{{ asset('images/products/no-image.png') }}" alt="Product" style="width:100%;height:100%;object-fit:contain;padding:4px;">
                        @endif
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:14px;font-weight:600;color:var(--text);">{{ $item->product_name }}</div>
                        <div style="font-size:12px;color:var(--text-secondary);">x{{ $item->quantity }} — Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;">
            <h3 style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:16px;">Update Status</h3>
            <form action="{{ route('admin.returns.update', $return->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Status</label>
                        <select name="status" class="form-input form-select">
                            @foreach(['requested', 'approved', 'rejected', 'received', 'refunded'] as $s)
                            <option value="{{ $s }}" {{ $return->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Admin Notes</label>
                        <textarea name="admin_notes" class="form-input form-textarea" rows="3" placeholder="Catatan admin...">{{ $return->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="w-full btn-primary" style="padding:12px;border-radius:10px;">Update Return</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
