@extends('layouts.app')
@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:900px;">

        @if(session('success'))
        <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:14px;padding:20px 24px;margin-bottom:24px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <svg style="width:20px;height:20px;color:#4ade80;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-size:13px;color:#4ade80;">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <div style="text-align:center;margin-bottom:40px;">
            <div style="width:80px;height:80px;margin:0 auto 24px;border-radius:24px;background:linear-gradient(135deg,rgba(34,197,94,0.15),rgba(34,197,94,0.05));display:flex;align-items:center;justify-content:center;">
                <svg style="width:40px;height:40px;color:#4ade80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(24px,3vw,32px);font-weight:800;color:var(--text);margin:0 0 8px;">Payment Successful!</h1>
            <p style="color:var(--text-secondary);font-size:15px;">Thank you for shopping at WINKY STORE</p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;" class="payment-success-layout">

            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="checkout-section">
                    <h3 style="margin-bottom:16px;">Order Information</h3>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div>
                            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:2px;">Order Number</div>
                            <div style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:var(--text);">{{ $order->order_number }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:2px;">Date</div>
                            <div style="font-size:14px;color:var(--text);">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                </div>

                <div class="checkout-section">
                    <h3 style="margin-bottom:16px;">Products Purchased</h3>
                    <div style="display:flex;flex-direction:column;gap:16px;">
                        @foreach($order->items as $item)
                        <div style="display:flex;gap:16px;padding-bottom:16px;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                            <div style="width:64px;height:64px;border-radius:14px;background:var(--bg-deep);overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:contain;padding:6px;" loading="lazy">
                                @else
                                <img src="{{ asset('images/products/no-image.png') }}" alt="Product" style="width:100%;height:100%;object-fit:contain;padding:6px;" loading="lazy">
                                @endif
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:600;font-size:14px;color:var(--text);">{{ $item->product_name }}</div>
                                @if($item->variant_name)
                                <div style="font-size:12px;color:var(--cyan);margin-top:2px;">{{ $item->variant_name }}</div>
                                @endif
                                <div style="font-size:13px;color:var(--text-secondary);margin-top:4px;">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </div>
                            <div style="text-align:right;flex-shrink:0;">
                                <div style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:14px;color:var(--cyan);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div style="position:sticky;top:100px;">
                <div class="checkout-summary">
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;color:var(--text);margin:0 0 20px;padding-bottom:16px;border-bottom:1px solid var(--border);">Payment Summary</h3>

                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:16px;">
                        <div class="checkout-total-row">
                            <span>Method</span>
                            <span style="font-weight:500;color:var(--text);">{{ $order->payment->method_label ?? '-' }}</span>
                        </div>
                        <div class="checkout-total-row" style="align-items:center;">
                            <span>Status</span>
                            <span style="display:inline-flex;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);">Lunas</span>
                        </div>
                        @if($order->payment->transaction_id)
                        <div class="checkout-total-row">
                            <span>Transaction ID</span>
                            <span style="font-family:monospace;font-size:12px;color:var(--text);">{{ $order->payment->transaction_id }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="checkout-total-row">
                        <span>Subtotal</span>
                        <span style="font-weight:600;color:var(--text);">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="checkout-total-row">
                        <span>Shipping</span>
                        <span style="font-weight:600;color:var(--text);">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @if($order->voucher_discount > 0)
                    <div class="checkout-total-row">
                        <span>Voucher ({{ $order->voucher_code }})</span>
                        <span style="font-weight:600;color:#4ade80;">-Rp {{ number_format($order->voucher_discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="checkout-total-row grand">
                        <span>Total Paid</span>
                        <span style="font-family:'Space Grotesk',sans-serif;font-weight:800;font-size:20px;color:var(--cyan);">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:10px;margin-top:24px;">
                        <a href="{{ route('orders.show', $order->order_number) }}" style="display:block;text-align:center;padding:14px;background:linear-gradient(135deg,var(--cyan),var(--blue));color:#000;border-radius:12px;font-size:14px;font-weight:700;text-decoration:none;transition:all .3s;">View Order Details</a>
                        <a href="{{ route('products.index') }}" style="display:block;text-align:center;padding:14px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;color:var(--text-secondary);font-size:14px;font-weight:600;text-decoration:none;transition:all .25s;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)'" onmouseout="this.style.borderColor='var(--border)'">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function handleSuccessResize() {
    var w = window.innerWidth;
    var el = document.querySelector('.payment-success-layout');
    if (el) el.style.gridTemplateColumns = w < 768 ? '1fr' : '1fr 320px';
}
window.addEventListener('resize', handleSuccessResize);
handleSuccessResize();
</script>
@endsection
