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
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
                    <h1 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:800;color:var(--text);margin:0;">Notifikasi</h1>
                    @if($notifications->count() > 0)
                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                        @csrf
                        <button type="submit" style="padding:8px 16px;border-radius:10px;font-size:13px;font-weight:600;background:var(--bg-card);border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;">Tandai semua dibaca</button>
                    </form>
                    @endif
                </div>

                @if($notifications->count() > 0)
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach($notifications as $notif)
                    <div style="display:flex;align-items:flex-start;gap:14px;padding:16px 20px;border-radius:14px;background:{{ $notif->isRead() ? 'var(--bg-card)' : 'rgba(0,229,255,0.03)' }};border:1px solid {{ $notif->isRead() ? 'var(--border)' : 'rgba(0,229,255,0.15)' }};{{ !$notif->isRead() ? 'border-left:3px solid var(--cyan);' : '' }}">
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(0,229,255,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg style="width:18px;height:18px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $notif->icon }}"/></svg>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:14px;font-weight:600;color:var(--text);">{{ $notif->data['title'] ?? 'Notifikasi' }}</div>
                            <div style="font-size:13px;color:var(--text-secondary);margin-top:2px;">{{ $notif->data['message'] ?? '' }}</div>
                            <div style="font-size:11px;color:var(--text-secondary);margin-top:6px;opacity:0.6;">{{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;">
                            @if(!$notif->isRead())
                            <form action="{{ route('notifications.markRead', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="padding:4px 10px;border-radius:6px;font-size:11px;background:rgba(0,229,255,0.1);color:var(--cyan);border:none;cursor:pointer;">Baca</button>
                            </form>
                            @endif
                            <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding:4px 10px;border-radius:6px;font-size:11px;background:rgba(239,68,68,0.1);color:#f87171;border:none;cursor:pointer;">Hapus</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div style="margin-top:24px;">{{ $notifications->links() }}</div>
                @else
                <div style="text-align:center;padding:60px 20px;background:var(--bg-card);border:1px solid var(--border);border-radius:16px;">
                    <svg style="width:48px;height:48px;color:var(--text-secondary);margin:0 auto 16px;opacity:0.4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <p style="font-size:15px;color:var(--text-secondary);font-weight:600;">Belum ada notifikasi</p>
                    <p style="font-size:13px;color:var(--text-secondary);margin-top:4px;opacity:0.6;">Notifikasi akan muncul di sini</p>
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
