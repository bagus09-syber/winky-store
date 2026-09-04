@extends('layouts.app')
@section('title', 'Ulasan ' . $store->name . ' - WINKY STORE')

@section('content')
<section style="padding:100px 0 60px;min-height:100vh;">
    <div style="max-width:800px;margin:0 auto;padding:0 24px;">
        <div style="margin-bottom:24px;">
            <a href="{{ route('store.show', $store->slug) }}" style="color:var(--cyan);font-size:13px;text-decoration:none;">← Kembali ke Toko</a>
        </div>

        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:700;color:var(--text);margin-bottom:8px;">Ulasan {{ $store->name }}</h1>
        <p style="color:var(--text-secondary);font-size:14px;margin-bottom:32px;">⭐ {{ $avgRating }} dari 5 · {{ $reviewsCount }} ulasan</p>

        @if($reviews->count() > 0)
        <div class="space-y-4">
            @foreach($reviews as $review)
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:24px;">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,var(--blue),var(--cyan));border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;">{{ substr($review->user->name, 0, 1) }}</div>
                        <div>
                            <p style="font-weight:600;font-size:14px;color:var(--text);">{{ $review->user->name }}</p>
                            <p style="font-size:11px;color:var(--text-secondary);">{{ $review->created_at->format('d M Y') }} · {{ $review->product->name }}</p>
                        </div>
                    </div>
                    <span style="color:#fbbf24;font-size:13px;">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                </div>
                @if($review->title)
                <p style="font-weight:600;font-size:14px;color:var(--text);margin-bottom:4px;">{{ $review->title }}</p>
                @endif
                <p style="font-size:13px;color:var(--text-secondary);line-height:1.6;">{{ $review->comment }}</p>
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $reviews->links() }}</div>
        @else
        <div style="text-align:center;padding:60px 0;">
            <p style="color:var(--text-secondary);">Belum ada ulasan</p>
        </div>
        @endif
    </div>
</section>
@endsection
