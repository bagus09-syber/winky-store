@extends('layouts.app')
@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:1100px;">

        @if(session('success'))
        <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:14px;padding:20px 24px;margin-bottom:24px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <svg style="width:20px;height:20px;color:#4ade80;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-size:13px;color:#4ade80;">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:32px;">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;font-size:13px;color:var(--text-secondary);">
                    <a href="{{ route('home') }}" style="color:inherit;text-decoration:none;">Home</a>
                    <span>/</span>
                    <a href="{{ route('account.orders') }}" style="color:inherit;text-decoration:none;">Orders</a>
                    <span>/</span>
                    <span style="color:var(--text);">{{ $order->order_number }}</span>
                </div>
                <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(24px,3vw,32px);font-weight:800;color:var(--text);margin:0;">Order Details</h1>
            </div>
            <a href="{{ route('account.orders') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:var(--bg-card);border:1px solid var(--border);border-radius:10px;color:var(--text-secondary);font-size:13px;font-weight:600;text-decoration:none;transition:all .25s;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)';this.style.color='var(--text)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-secondary)'">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Orders
            </a>
        </div>

        <div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start;" class="order-detail-layout">

            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Order Header --}}
                <div class="checkout-section">
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                        <div>
                            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:4px;">Order Number</div>
                            <div style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:20px;color:var(--text);">{{ $order->order_number }}</div>
                        </div>
                        @php
                            $statusStyles = [
                                'pending' => 'background:rgba(250,204,21,0.1);color:#facc15;border:1px solid rgba(250,204,21,0.2);',
                                'awaiting_payment' => 'background:rgba(255,152,0,0.1);color:#ffb74d;border:1px solid rgba(255,152,0,0.2);',
                                'paid' => 'background:rgba(0,229,255,0.1);color:var(--cyan);border:1px solid rgba(0,229,255,0.2);',
                                'processing' => 'background:rgba(41,121,255,0.1);color:#60a5fa;border:1px solid rgba(41,121,255,0.2);',
                                'shipped' => 'background:rgba(168,85,247,0.1);color:#c084fc;border:1px solid rgba(168,85,247,0.2);',
                                'delivered' => 'background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);',
                                'completed' => 'background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);',
                                'cancelled' => 'background:rgba(239,68,68,0.1);color:#f87171;border:1px solid rgba(239,68,68,0.2);',
                            ];
                        @endphp
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:20px;font-size:13px;font-weight:600;{{ $statusStyles[$order->status] ?? '' }}">
                            <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $order->status_icon }}"/></svg>
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);display:flex;gap:24px;flex-wrap:wrap;">
                        <div>
                            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:2px;">Order Date</div>
                            <div style="font-size:14px;color:var(--text);">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:2px;">Last Updated</div>
                            <div style="font-size:14px;color:var(--text);">{{ $order->updated_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Status Timeline --}}
                @if($order->status !== 'cancelled')
                @php
                    $steps = $order->timeline_steps;
                    $timelineOrder = ['pending','awaiting_payment','paid','processing','shipped','delivered','completed'];
                    $currentIdx = array_search($order->status, $timelineOrder);
                    if($currentIdx === false) $currentIdx = -1;
                @endphp
                <div class="checkout-section">
                    <h3 style="margin-bottom:24px;">Order Tracking</h3>
                    <div style="display:flex;flex-direction:column;gap:0;">
                        @foreach($timelineOrder as $i => $status)
                        @if(isset($steps[$status]))
                        @php
                            $state = 'pending';
                            if($i < $currentIdx) $state = 'done';
                            elseif($i == $currentIdx) $state = 'active';
                        @endphp
                        <div style="display:flex;gap:16px;position:relative;">
                            <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0;width:32px;">
                                <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                                    @if($state === 'done') background:linear-gradient(135deg,var(--cyan),var(--blue));color:#000;
                                    @elseif($state === 'active') background:rgba(0,229,255,0.15);border:2px solid var(--cyan);color:var(--cyan);
                                    @else background:var(--bg-deep);border:1px solid var(--border);color:var(--text-secondary); @endif">
                                    @if($state === 'done')
                                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $order->status_icon }}"/></svg>
                                    @endif
                                </div>
                                @if(!$loop->last)
                                <div style="width:2px;flex:1;min-height:24px;margin:4px 0;
                                    @if($state === 'done') background:linear-gradient(180deg,var(--cyan),var(--blue));
                                    @else background:var(--border); @endif"></div>
                                @endif
                            </div>
                            <div style="padding-bottom:20px;">
                                <div style="font-size:14px;font-weight:600;color:{{ $state === 'pending' ? 'var(--text-secondary)' : 'var(--text)' }};">{{ $steps[$status]['label'] }}</div>
                                <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">{{ $steps[$status]['description'] }}</div>
                                @if($state === 'done')
                                <div style="font-size:11px;color:var(--cyan);margin-top:4px;">Completed</div>
                                @elseif($state === 'active')
                                <div style="font-size:11px;color:var(--cyan);margin-top:4px;">In Progress</div>
                                @endif
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @else
                <div class="checkout-section">
                    <div style="display:flex;align-items:center;gap:16px;padding:24px;background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.15);border-radius:16px;">
                        <div style="width:48px;height:48px;border-radius:14px;background:rgba(239,68,68,0.1);display:flex;align-items:center;justify-content:center;color:#f87171;flex-shrink:0;">
                            <svg style="width:22px;height:22px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:16px;color:#f87171;">Order Cancelled</div>
                            <div style="font-size:13px;color:var(--text-secondary);margin-top:2px;">This order has been cancelled.</div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Products --}}
                <div class="checkout-section">
                    <h3 style="margin-bottom:20px;">Products Ordered</h3>
                    <div style="display:flex;flex-direction:column;gap:16px;">
                        @foreach($order->items as $item)
                        <div style="display:flex;gap:16px;padding-bottom:16px;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                            <div style="width:72px;height:72px;border-radius:14px;flex-shrink:0;background:var(--bg-deep);overflow:hidden;display:flex;align-items:center;justify-content:center;">
                                @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:contain;padding:6px;" loading="lazy">
                                @else
                                <img src="{{ asset('images/products/no-image.png') }}" alt="Product" style="width:100%;height:100%;object-fit:contain;padding:6px;" loading="lazy">
                                @endif
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:600;font-size:14px;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->product_name }}</div>
                                @if($item->variant_name)
                                <div style="font-size:12px;color:var(--cyan);margin-top:3px;">{{ $item->variant_name }}</div>
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

                {{-- Shipping Address --}}
                <div class="checkout-section">
                    <h3 style="margin-bottom:16px;">Shipping Address</h3>
                    <div style="padding:16px;background:var(--bg-deep);border-radius:14px;border:1px solid var(--border);">
                        <div style="font-weight:600;color:var(--text);margin-bottom:4px;">{{ $order->recipient_name }}</div>
                        <div style="font-size:13px;color:var(--text-secondary);margin-bottom:8px;">{{ $order->recipient_phone }}</div>
                        <div style="font-size:14px;color:var(--text-secondary);line-height:1.6;">{{ $order->shipping_address }}</div>
                    </div>
                </div>

                {{-- Shipping Info --}}
                @if($order->shipping_courier)
                <div class="checkout-section">
                    <h3 style="margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                        <svg style="width:18px;height:18px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        Shipping Information
                    </h3>
                    <div style="padding:16px;background:var(--bg-deep);border-radius:14px;border:1px solid var(--border);">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                            <div>
                                <div style="font-size:11px;color:var(--text-secondary);margin-bottom:3px;">Courier</div>
                                <div style="font-size:14px;color:var(--text);font-weight:600;">{{ strtoupper($order->shipping_courier) }}</div>
                            </div>
                            <div>
                                <div style="font-size:11px;color:var(--text-secondary);margin-bottom:3px;">Service</div>
                                <div style="font-size:14px;color:var(--text);font-weight:600;">{{ strtoupper($order->shipping_service ?? '-') }}</div>
                            </div>
                            @if($order->tracking_number)
                            <div style="grid-column:span 2;">
                                <div style="font-size:11px;color:var(--text-secondary);margin-bottom:3px;">Tracking Number</div>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <span style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;color:var(--cyan);letter-spacing:1px;">{{ $order->tracking_number }}</span>
                                    <button type="button" onclick="copyTracking()" style="padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;background:rgba(0,229,255,0.1);color:var(--cyan);border:1px solid rgba(0,229,255,0.2);cursor:pointer;transition:all .2s;" onmouseover="this.style.background='rgba(0,229,255,0.2)'" onmouseout="this.style.background='rgba(0,229,255,0.1)'">Copy</button>
                                </div>
                            </div>
                            @endif
                            @if($order->shipped_at)
                            <div>
                                <div style="font-size:11px;color:var(--text-secondary);margin-bottom:3px;">Shipped At</div>
                                <div style="font-size:13px;color:var(--text);">{{ $order->shipped_at->format('d M Y, H:i') }}</div>
                            </div>
                            @endif
                            @if($order->estimated_delivery)
                            <div>
                                <div style="font-size:11px;color:var(--text-secondary);margin-bottom:3px;">Estimated Delivery</div>
                                <div style="font-size:13px;color:var(--text);font-weight:600;">{{ $order->estimated_delivery }}</div>
                            </div>
                            @endif
                        </div>
                        @if($order->status === 'shipped' && !$order->tracking_number)
                        <div style="margin-top:12px;padding:10px;background:rgba(251,191,36,0.06);border:1px solid rgba(251,191,36,0.15);border-radius:8px;">
                            <p style="font-size:12px;color:#fbbf24;">Tracking detail dari kurir belum terhubung.</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($order->notes)
                <div class="checkout-section">
                    <h3 style="margin-bottom:12px;">Order Notes</h3>
                    <p style="font-size:14px;color:var(--text-secondary);line-height:1.7;">{{ $order->notes }}</p>
                </div>
                @endif
            </div>

            {{-- Right Column --}}
            <div style="position:sticky;top:100px;">
                <div class="checkout-summary">
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;color:var(--text);margin:0 0 20px;padding-bottom:16px;border-bottom:1px solid var(--border);">Payment Summary</h3>
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        <div class="checkout-total-row">
                            <span>Subtotal</span>
                            <span style="font-weight:600;color:var(--text);">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="checkout-total-row">
                            <span>Shipping ({{ $order->shipping_label }})</span>
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

                @if($order->payment)
                <div class="checkout-section" style="margin-top:16px;">
                    <h3 style="margin-bottom:16px;">Payment Info</h3>
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        <div class="checkout-total-row">
                            <span>Method</span>
                            <span style="font-weight:500;color:var(--text);">{{ $order->payment->method_label }}</span>
                        </div>
                        <div class="checkout-total-row" style="align-items:center;">
                            <span>Status</span>
                            @php
                                $ps = $order->payment->status;
                                $psColors = ['pending' => 'background:rgba(250,204,21,0.1);color:#facc15;border:1px solid rgba(250,204,21,0.2);', 'paid' => 'background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);', 'failed' => 'background:rgba(239,68,68,0.1);color:#f87171;border:1px solid rgba(239,68,68,0.2);'];
                            @endphp
                            <span style="display:inline-flex;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;{{ $psColors[$ps] ?? '' }}">{{ $order->payment->status_label }}</span>
                        </div>
                        @if($order->payment->transaction_id)
                        <div class="checkout-total-row">
                            <span>Transaction ID</span>
                            <span style="font-family:monospace;font-size:12px;color:var(--text);">{{ $order->payment->transaction_id }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($order->needsPayment())
                <a href="{{ route('payment.show', $order->order_number) }}" class="pay-btn" style="width:100%;justify-content:center;margin-top:16px;padding:16px;">
                    <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Pay Now
                </a>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
function handleOrderResize() {
    var w = window.innerWidth;
    var el = document.querySelector('.order-detail-layout');
    if (el) el.style.gridTemplateColumns = w < 900 ? '1fr' : '1fr 360px';
}
window.addEventListener('resize', handleOrderResize);
handleOrderResize();

function copyTracking() {
    var num = '{{ $order->tracking_number }}';
    if (num && navigator.clipboard) {
        navigator.clipboard.writeText(num).then(function() {
            var btn = event.target;
            var orig = btn.textContent;
            btn.textContent = 'Copied!';
            btn.style.background = 'rgba(34,197,94,0.2)';
            btn.style.color = '#4ade80';
            setTimeout(function() { btn.textContent = orig; btn.style.background = ''; btn.style.color = ''; }, 1500);
        });
    }
}
</script>
@endsection
