@extends('layouts.app')
@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:900px;">

        @if(session('error'))
        <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:14px;padding:20px 24px;margin-bottom:24px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <svg style="width:20px;height:20px;color:#f87171;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-size:13px;color:#fca5a5;">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <div style="margin-bottom:12px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;font-size:13px;color:var(--text-secondary);">
                <a href="{{ route('home') }}" style="color:inherit;text-decoration:none;">Home</a>
                <span>/</span>
                <a href="{{ route('account.orders') }}" style="color:inherit;text-decoration:none;">Orders</a>
                <span>/</span>
                <span style="color:var(--text);">{{ $order->order_number }}</span>
            </div>
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(24px,3vw,32px);font-weight:800;color:var(--text);margin:0;">Choose Payment Method</h1>
        </div>

        <div class="checkout-stepper" style="margin-bottom:40px;">
            <div class="step done">
                <div class="step-num">✓</div>
                <div class="step-label">Cart</div>
            </div>
            <div class="step-line done"></div>
            <div class="step done">
                <div class="step-num">✓</div>
                <div class="step-label">Checkout</div>
            </div>
            <div class="step-line active"></div>
            <div class="step active">
                <div class="step-num">3</div>
                <div class="step-label">Payment</div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 360px;gap:32px;align-items:start;" class="payment-layout">

            <div>
                <div class="checkout-section">
                    <h3><span class="sec-num">1</span> Select Payment Method</h3>

                    <form action="{{ route('payment.process', $order->order_number) }}" method="POST" id="payment-form">
                        @csrf
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            @foreach($paymentMethods as $key => $method)
                            @if($method['enabled'])
                            <label style="display:block;cursor:pointer;">
                                <input type="radio" name="payment_method" value="{{ $key }}" class="hidden" {{ old('payment_method') == $key ? 'checked' : '' }} required onchange="selectPaymentMethod('{{ $key }}')">
                                <div class="address-card {{ old('payment_method') == $key ? 'selected' : '' }}" id="pm-{{ $key }}">
                                    <div class="radio-dot"></div>
                                    <div style="display:flex;align-items:center;gap:14px;">
                                        <div style="width:48px;height:48px;border-radius:12px;background:var(--bg-deep);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            @if($key === 'qris')
                                            <svg style="width:24px;height:24px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                            @elseif($key === 'bank_transfer')
                                            <svg style="width:24px;height:24px;color:var(--blue)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            @elseif($key === 'ewallet')
                                            <svg style="width:24px;height:24px;color:var(--magenta)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            @endif
                                        </div>
                                        <div style="flex:1;">
                                            <div style="font-size:15px;font-weight:700;color:var(--text);">{{ $method['name'] }}</div>
                                            <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">{{ $method['description'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            @endif
                            @endforeach
                        </div>

                        @error('payment_method')
                        <div style="margin-top:10px;font-size:13px;color:#f87171;">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="pay-btn" style="margin-top:24px;" id="pay-btn">
                            <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Continue to Payment
                        </button>
                    </form>
                    <div style="margin-top:16px;padding:10px;background:rgba(0,229,255,0.05);border:1px solid rgba(0,229,255,0.15);border-radius:8px;font-size:11px;color:var(--text-secondary);text-align:center;">⚠ Development mode: Payment gateway tersedia untuk simulasi</div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div style="position:sticky;top:100px;">
                <div class="checkout-summary">
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;color:var(--text);margin:0 0 20px;padding-bottom:16px;border-bottom:1px solid var(--border);">Order Summary</h3>

                    <div style="font-size:12px;color:var(--text-secondary);margin-bottom:12px;">
                        <span>{{ $order->order_number }}</span> · <span>{{ $order->created_at->format('d M Y') }}</span>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:16px;">
                        @foreach($order->items as $item)
                        <div style="display:flex;gap:12px;align-items:center;">
                            <div style="width:44px;height:44px;border-radius:10px;background:var(--bg-deep);overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:contain;padding:4px;" loading="lazy">
                                @else
                                <img src="{{ asset('images/products/no-image.png') }}" alt="Product" style="width:100%;height:100%;object-fit:contain;padding:4px;" loading="lazy">
                                @endif
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:13px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->product_name }}</div>
                                <div style="font-size:11px;color:var(--text-secondary);">x{{ $item->quantity }}</div>
                            </div>
                            <div style="font-family:'Space Grotesk',sans-serif;font-size:13px;font-weight:600;color:var(--text);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                        </div>
                        @endforeach
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
                        <span>Total</span>
                        <span style="font-family:'Space Grotesk',sans-serif;font-weight:800;font-size:20px;color:var(--cyan);">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function selectPaymentMethod(method) {
    document.querySelectorAll('.address-card').forEach(function(c) { c.classList.remove('selected'); });
    var card = document.getElementById('pm-' + method);
    if (card) card.classList.add('selected');
}
function handlePaymentResize() {
    var w = window.innerWidth;
    var el = document.querySelector('.payment-layout');
    if (el) el.style.gridTemplateColumns = w < 768 ? '1fr' : '1fr 360px';
}
window.addEventListener('resize', handlePaymentResize);
handlePaymentResize();
</script>
@endsection
