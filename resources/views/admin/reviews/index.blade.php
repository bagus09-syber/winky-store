@extends('layouts.admin')
@section('title', 'Reviews')
@section('page-title', 'Review Management')

@section('content')
<div style="padding:24px;max-width:1200px;">

    @if(session('success'))
    <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:12px;padding:16px 20px;margin-bottom:24px;">
        <span style="font-size:13px;color:#4ade80;">{{ session('success') }}</span>
    </div>
    @endif

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <form action="{{ route('admin.reviews.index') }}" method="GET" style="display:flex;gap:8px;flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari review..." class="form-input" style="width:200px;">
            <select name="rating" class="form-input form-select" style="width:120px;">
                <option value="">Semua Rating</option>
                @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star</option>
                @endfor
            </select>
            <select name="approved" class="form-input form-select" style="width:140px;">
                <option value="">Semua Status</option>
                <option value="1" {{ request('approved') === '1' ? 'selected' : '' }}>Approved</option>
                <option value="0" {{ request('approved') === '0' ? 'selected' : '' }}>Pending</option>
            </select>
            <button type="submit" class="btn-primary" style="padding:8px 16px;">Filter</button>
        </form>
    </div>

    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;">
        @if($reviews->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--border);">
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">User</th>
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">Product</th>
                        <th style="padding:14px 16px;text-align:center;font-size:12px;font-weight:600;color:var(--text-secondary);">Rating</th>
                        <th style="padding:14px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text-secondary);">Review</th>
                        <th style="padding:14px 16px;text-align:center;font-size:12px;font-weight:600;color:var(--text-secondary);">Verified</th>
                        <th style="padding:14px 16px;text-align:center;font-size:12px;font-weight:600;color:var(--text-secondary);">Status</th>
                        <th style="padding:14px 16px;text-align:center;font-size:12px;font-weight:600;color:var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:14px 16px;font-size:13px;color:var(--text);">{{ $review->user->name ?? '-' }}</td>
                        <td style="padding:14px 16px;font-size:13px;color:var(--text);max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $review->product->name ?? '-' }}</td>
                        <td style="padding:14px 16px;text-align:center;">
                            <span style="color:#ffc107;font-size:13px;">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                        </td>
                        <td style="padding:14px 16px;font-size:13px;color:var(--text-secondary);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            @if($review->title)<strong style="color:var(--text);">{{ $review->title }}</strong> — @endif
                            {{ Str::limit($review->comment, 80) }}
                        </td>
                        <td style="padding:14px 16px;text-align:center;">
                            @if($review->is_verified_purchase)
                            <span style="color:#4ade80;font-size:12px;">✓ Verified</span>
                            @else
                            <span style="color:var(--text-secondary);font-size:12px;">—</span>
                            @endif
                        </td>
                        <td style="padding:14px 16px;text-align:center;">
                            @if($review->is_approved)
                            <span style="padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:rgba(34,197,94,0.1);color:#4ade80;">Approved</span>
                            @else
                            <span style="padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:rgba(250,204,21,0.1);color:#facc15;">Pending</span>
                            @endif
                        </td>
                        <td style="padding:14px 16px;text-align:center;">
                            <div style="display:flex;gap:6px;justify-content:center;">
                                <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" style="padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;background:rgba(0,229,255,0.1);color:var(--cyan);border:none;cursor:pointer;">{{ $review->is_approved ? 'Hide' : 'Approve' }}</button>
                                </form>
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus review ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;background:rgba(239,68,68,0.1);color:#f87171;border:none;cursor:pointer;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px;">{{ $reviews->links() }}</div>
        @else
        <div style="padding:60px;text-align:center;">
            <p style="font-size:14px;color:var(--text-secondary);">Belum ada review.</p>
        </div>
        @endif
    </div>
</div>
@endsection
