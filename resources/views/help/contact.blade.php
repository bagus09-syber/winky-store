@extends('layouts.app')
@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:700px;">

        <div style="text-align:center;margin-bottom:40px;">
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(28px,4vw,36px);font-weight:800;color:var(--text);margin:0 0 8px;">Contact Support</h1>
            <p style="font-size:15px;color:var(--text-secondary);">Kirim pesan dan kami akan merespon segera</p>
        </div>

        @if(session('success'))
        <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:14px;padding:16px 20px;margin-bottom:24px;">
            <span style="font-size:13px;color:#4ade80;">{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:14px;padding:16px 20px;margin-bottom:24px;">
            @foreach($errors->all() as $error)
            <div style="font-size:13px;color:#f87171;padding:2px 0;">{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:28px;">
            <form action="{{ route('help.submitTicket') }}" method="POST">
                @csrf
                <div style="display:flex;flex-direction:column;gap:18px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Nama *</label>
                            <input type="text" name="name" value="{{ Auth::user()->name ?? old('name') }}" class="form-input" required>
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Email *</label>
                            <input type="email" name="email" value="{{ Auth::user()->email ?? old('email') }}" class="form-input" required>
                        </div>
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Subjek *</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="form-input" placeholder="Perihal pesan kamu" required>
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Nomor Pesanan (opsional)</label>
                        <input type="text" name="order_number" value="{{ old('order_number') }}" class="form-input" placeholder="WKY-XXXXXXXX-XXXX">
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Pesan *</label>
                        <textarea name="message" class="form-input form-textarea" rows="5" placeholder="Jelaskan masalah atau pertanyaan kamu..." required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="w-full btn-primary" style="padding:14px;border-radius:12px;">
                        Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
