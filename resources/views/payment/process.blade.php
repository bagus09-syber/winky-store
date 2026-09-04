@extends('layouts.app')
@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:900px;">

        <div style="margin-bottom:12px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;font-size:13px;color:var(--text-secondary);">
                <a href="{{ route('home') }}" style="color:inherit;text-decoration:none;">Home</a>
                <span>/</span>
                <a href="{{ route('account.orders') }}" style="color:inherit;text-decoration:none;">Orders</a>
                <span>/</span>
                <span style="color:var(--text);">{{ $order->order_number }}</span>
            </div>
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(24px,3vw,32px);font-weight:800;color:var(--text);margin:0;">Payment Details</h1>
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
                    <h3><span class="sec-num">1</span> Order Information</h3>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                        <div>
                            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:4px;">Order Number</div>
                            <div style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:var(--text);">{{ $order->order_number }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:4px;">Order Date</div>
                            <div style="font-size:14px;color:var(--text);">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:4px;">Payment Method</div>
                            <div style="font-size:14px;color:var(--text);font-weight:600;">{{ $order->payment->method_label ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:4px;">Transaction ID</div>
                            <div style="font-family:monospace;font-size:13px;color:var(--text);">{{ $order->payment->transaction_id ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="checkout-section">
                    <h3><span class="sec-num">2</span> Payment Instructions</h3>

                    @if($order->payment->payment_method === 'bank_transfer')
                    <div style="padding:20px;background:var(--bg-deep);border:1px solid var(--border);border-radius:14px;">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                            <div style="width:44px;height:44px;border-radius:12px;background:rgba(41,121,255,0.1);display:flex;align-items:center;justify-content:center;">
                                <svg style="width:22px;height:22px;color:var(--blue)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <div style="font-weight:700;color:var(--text);">Bank Transfer</div>
                                <div style="font-size:12px;color:var(--text-secondary);">Transfer via ATM, Mobile Banking, or Internet Banking</div>
                            </div>
                        </div>
                        <div style="padding:16px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;margin-bottom:16px;">
                            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:6px;">Virtual Account Number</div>
                            <div style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:800;color:var(--cyan);letter-spacing:2px;" id="va-number">{{ $order->payment->va_number }}</div>
                        </div>
                        <div style="display:flex;gap:10px;">
                            <button onclick="copyText('{{ $order->payment->va_number }}')" style="flex:1;padding:12px;background:var(--bg-card);border:1px solid var(--border);border-radius:10px;color:var(--text);font-size:13px;font-weight:600;cursor:pointer;transition:all .25s;display:flex;align-items:center;justify-content:center;gap:8px;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)'" onmouseout="this.style.borderColor='var(--border)'">
                                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                Copy VA Number
                            </button>
                        </div>
                    </div>

                    @elseif($order->payment->payment_method === 'qris')
                    <div style="padding:20px;background:var(--bg-deep);border:1px solid var(--border);border-radius:14px;text-align:center;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:20px;">
                            <div style="width:44px;height:44px;border-radius:12px;background:rgba(0,229,255,0.1);display:flex;align-items:center;justify-content:center;">
                                <svg style="width:22px;height:22px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </div>
                            <div style="text-align:left;">
                                <div style="font-weight:700;color:var(--text);">QRIS</div>
                                <div style="font-size:12px;color:var(--text-secondary);">Scan QR Code for instant payment</div>
                            </div>
                        </div>
                        <div style="background:#fff;padding:24px;border-radius:16px;display:inline-block;margin-bottom:16px;">
                            <div style="width:180px;height:180px;background:repeating-conic-gradient(#333 0% 25%, #fff 0% 50%) 50%/12px 12px;display:flex;align-items:center;justify-content:center;border-radius:8px;">
                                <span style="background:#fff;padding:4px 8px;font-size:10px;color:#333;font-weight:700;border-radius:4px;">QR CODE</span>
                            </div>
                        </div>
                        <div style="font-size:13px;color:var(--text-secondary);">Payment Code: <span style="font-weight:700;color:var(--text);">{{ $order->payment->payment_code }}</span></div>
                    </div>

                    @elseif($order->payment->payment_method === 'ewallet')
                    <div style="padding:20px;background:var(--bg-deep);border:1px solid var(--border);border-radius:14px;">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                            <div style="width:44px;height:44px;border-radius:12px;background:rgba(224,64,251,0.1);display:flex;align-items:center;justify-content:center;">
                                <svg style="width:22px;height:22px;color:var(--magenta)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div style="font-weight:700;color:var(--text);">E-Wallet</div>
                                <div style="font-size:12px;color:var(--text-secondary);">Payment code for your e-wallet app</div>
                            </div>
                        </div>
                        <div style="padding:16px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;margin-bottom:16px;">
                            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:6px;">Payment Code</div>
                            <div style="font-family:'Space Grotesk',sans-serif;font-size:20px;font-weight:800;color:var(--cyan);letter-spacing:2px;">{{ $order->payment->payment_code }}</div>
                        </div>
                        <button onclick="copyText('{{ $order->payment->payment_code }}')" style="width:100%;padding:12px;background:var(--bg-card);border:1px solid var(--border);border-radius:10px;color:var(--text);font-size:13px;font-weight:600;cursor:pointer;transition:all .25s;display:flex;align-items:center;justify-content:center;gap:8px;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)'" onmouseout="this.style.borderColor='var(--border)'">
                            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            Copy Payment Code
                        </button>
                    </div>
                    @endif

                    <div style="margin-top:16px;padding:14px;background:rgba(255,193,7,0.06);border:1px solid rgba(255,193,7,0.15);border-radius:12px;">
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#ffc107;font-weight:600;">
                            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Complete payment within 24 hours
                        </div>
                        <div style="font-size:12px;color:var(--text-secondary);margin-top:4px;">Expires: {{ \Carbon\Carbon::parse($order->payment->expiry_time)->format('d M Y, H:i') }}</div>
                    </div>
                </div>

                <div style="display:flex;gap:12px;margin-top:20px;">
                    <a href="{{ route('orders.show', $order->order_number) }}" style="flex:1;padding:14px;text-align:center;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;color:var(--text);font-size:14px;font-weight:600;text-decoration:none;transition:all .25s;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)'" onmouseout="this.style.borderColor='var(--border)'">View Order</a>
                    <form method="POST" action="{{ route('payment.callback', $order->order_number) }}" style="flex:1;">
                        @csrf
                        <button type="submit" style="width:100%;padding:14px;background:linear-gradient(135deg,var(--cyan),var(--blue));color:#000;border:none;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;transition:all .3s;">Simulate Payment</button>
                    </form>
                </div>
                <div style="margin-top:8px;padding:10px;background:rgba(0,229,255,0.05);border:1px solid rgba(0,229,255,0.15);border-radius:8px;font-size:11px;color:var(--text-secondary);text-align:center;">⚠ Development mode: Button akan memproses pembayaran secara simulasi</div>
            </div>

            {{-- Order Summary --}}
            <div style="position:sticky;top:100px;">
                <div class="checkout-summary">
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;color:var(--text);margin:0 0 20px;padding-bottom:16px;border-bottom:1px solid var(--border);">Order Summary</h3>

                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:16px;">
                        @foreach($order->items as $item)
                        <div style="display:flex;gap:12px;align-items:center;">
                            <div style="width:48px;height:48px;border-radius:10px;background:var(--bg-deep);overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
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
function copyText(text) {
    navigator.clipboard.writeText(text).then(function() {
        if (typeof showToast === 'function') showToast('Copied to clipboard!', 'success');
    });
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
