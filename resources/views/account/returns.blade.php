@extends('layouts.app')
@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:1100px;">
        @include('account.partials.sidebar')

        <div style="display:grid;grid-template-columns:240px 1fr;gap:32px;" class="account-layout">
            <div class="account-sidebar-desktop">
                @include('account.partials.sidebar-menu')
            </div>

            <div>
                <h1 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:800;color:var(--text);margin-bottom:28px;">Return Requests</h1>

                @if($returns->count() > 0)
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($returns as $return)
                    <div style="padding:18px 20px;border-radius:14px;background:var(--bg-card);border:1px solid var(--border);">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                            <div>
                                <span style="font-weight:600;color:var(--text);font-size:14px;">Order #{{ $return->order->order_number ?? '-' }}</span>
                            </div>
                            @php
                                $sc = $return->status_color;
                                $ss = ['yellow' => 'rgba(250,204,21,0.1);color:#facc15;border:1px solid rgba(250,204,21,0.2);', 'blue' => 'rgba(41,121,255,0.1);color:#60a5fa;border:1px solid rgba(41,121,255,0.2);', 'red' => 'rgba(239,68,68,0.1);color:#f87171;border:1px solid rgba(239,68,68,0.2);', 'cyan' => 'rgba(0,229,255,0.1);color:var(--cyan);border:1px solid rgba(0,229,255,0.2);', 'green' => 'rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);'];
                            @endphp
                            <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:{{ $ss[$sc] ?? $ss['yellow'] }}">{{ $return->status_label }}</span>
                        </div>
                        <div style="font-size:13px;color:var(--text-secondary);margin-bottom:4px;"><strong>Alasan:</strong> {{ $return->reason }}</div>
                        @if($return->description)
                        <div style="font-size:13px;color:var(--text-secondary);margin-bottom:6px;">{{ Str::limit($return->description, 150) }}</div>
                        @endif
                        @if($return->admin_notes)
                        <div style="padding:10px;background:rgba(0,229,255,0.04);border:1px solid rgba(0,229,255,0.1);border-radius:8px;margin-top:8px;">
                            <div style="font-size:11px;color:var(--cyan);font-weight:600;margin-bottom:2px;">Admin Note:</div>
                            <div style="font-size:12px;color:var(--text-secondary);">{{ $return->admin_notes }}</div>
                        </div>
                        @endif
                        <div style="font-size:11px;color:var(--text-secondary);opacity:0.6;margin-top:8px;">{{ $return->created_at->format('d M Y') }}</div>
                    </div>
                    @endforeach
                </div>
                <div style="margin-top:24px;">{{ $returns->links() }}</div>
                @else
                <div style="text-align:center;padding:60px 20px;background:var(--bg-card);border:1px solid var(--border);border-radius:16px;">
                    <p style="font-size:15px;color:var(--text-secondary);">Belum ada request return.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
@media (max-width: 768px) {
    .account-layout { grid-template-columns: 1fr !important; }
    .account-sidebar-desktop { display: none; }
}
</style>
@endsection
