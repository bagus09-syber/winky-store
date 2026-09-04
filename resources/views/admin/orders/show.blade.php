@extends('layouts.admin')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div style="display:flex;flex-direction:column;gap:24px;">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="font-size:13px;color:var(--text-secondary);margin-bottom:4px;">Nomor Pesanan</div>
            <div style="font-family:'Space Grotesk',sans-serif;font-weight:800;font-size:28px;color:var(--text);">{{ $order->order_number }}</div>
        </div>
        @php
            $sc = $order->status_color;
            $statusStyles = [
                'yellow' => 'background:rgba(250,204,21,0.1);color:#facc15;border:1px solid rgba(250,204,21,0.2);',
                'orange' => 'background:rgba(255,152,0,0.1);color:#ffb74d;border:1px solid rgba(255,152,0,0.2);',
                'cyan' => 'background:rgba(0,229,255,0.1);color:var(--cyan);border:1px solid rgba(0,229,255,0.2);',
                'blue' => 'background:rgba(41,121,255,0.1);color:#60a5fa;border:1px solid rgba(41,121,255,0.2);',
                'purple' => 'background:rgba(168,85,247,0.1);color:#c084fc;border:1px solid rgba(168,85,247,0.2);',
                'green' => 'background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);',
                'red' => 'background:rgba(239,68,68,0.1);color:#f87171;border:1px solid rgba(239,68,68,0.2);',
            ];
        @endphp
        <span style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:20px;font-size:14px;font-weight:600;{{ $statusStyles[$sc] ?? '' }}">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $order->status_icon }}"/></svg>
            {{ $order->status_label }}
        </span>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;" class="admin-order-layout">

        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Customer Info --}}
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;">
                <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:var(--text);margin-bottom:16px;">Informasi Customer</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <div style="font-size:12px;color:var(--text-secondary);margin-bottom:2px;">Nama</div>
                        <div style="font-size:14px;color:var(--text);">{{ $order->user->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px;color:var(--text-secondary);margin-bottom:2px;">Email</div>
                        <div style="font-size:14px;color:var(--text);">{{ $order->user->email ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;">
                <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:var(--text);margin-bottom:16px;">Alamat Pengiriman</h3>
                <div style="padding:16px;background:var(--bg-deep);border-radius:12px;border:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                        <span style="font-weight:600;color:var(--text);">{{ $order->recipient_name }}</span>
                        <span style="color:var(--text-secondary);">|</span>
                        <span style="color:var(--text-secondary);font-size:13px;">{{ $order->recipient_phone }}</span>
                    </div>
                    <div style="font-size:14px;color:var(--text-secondary);line-height:1.6;">{{ $order->shipping_address }}</div>
                </div>
            </div>

            {{-- Products --}}
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;">
                <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:var(--text);margin-bottom:16px;">Produk Dipesan</h3>
                <div style="display:flex;flex-direction:column;gap:16px;">
                    @foreach($order->items as $item)
                    <div style="display:flex;gap:16px;padding-bottom:16px;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                        <div style="width:64px;height:64px;border-radius:12px;background:var(--bg-deep);overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                            @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:contain;padding:6px;">
                            @else
                            <img src="{{ asset('images/products/no-image.png') }}" alt="Product" style="width:100%;height:100%;object-fit:contain;padding:6px;">
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

            {{-- Notes --}}
            @if($order->notes)
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;">
                <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:var(--text);margin-bottom:12px;">Catatan</h3>
                <p style="font-size:14px;color:var(--text-secondary);line-height:1.7;">{{ $order->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Payment Summary --}}
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;">
                <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:var(--text);margin-bottom:16px;">Ringkasan Pembayaran</h3>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div class="checkout-total-row">
                        <span>Subtotal</span>
                        <span style="font-weight:600;color:var(--text);">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="checkout-total-row">
                        <span>Ongkos Kirim</span>
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

            {{-- Payment Info --}}
            @if($order->payment)
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;">
                <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:var(--text);margin-bottom:16px;">Informasi Pembayaran</h3>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div class="checkout-total-row">
                        <span>Metode</span>
                        <span style="font-weight:500;color:var(--text);">{{ $order->payment->method_label }}</span>
                    </div>
                    <div class="checkout-total-row" style="align-items:center;">
                        <span>Status</span>
                        @php
                            $ps = $order->payment->status;
                            $psStyles = ['pending' => 'background:rgba(250,204,21,0.1);color:#facc15;border:1px solid rgba(250,204,21,0.2);', 'paid' => 'background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);', 'failed' => 'background:rgba(239,68,68,0.1);color:#f87171;border:1px solid rgba(239,68,68,0.2);'];
                        @endphp
                        <span style="display:inline-flex;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;{{ $psStyles[$ps] ?? '' }}">{{ $order->payment->status_label }}</span>
                    </div>
                    @if($order->payment->transaction_id)
                    <div class="checkout-total-row">
                        <span>ID Transaksi</span>
                        <span style="font-family:monospace;font-size:12px;color:var(--text);">{{ $order->payment->transaction_id }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Update Status --}}
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;">
                <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:var(--text);margin-bottom:16px;">Ubah Status</h3>
                @php $allowed = $validTransitions[$order->status] ?? []; @endphp
                @if(count($allowed) > 0)
                <form action="{{ route('admin.orders.updateStatus', $order->order_number) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        <select name="status" class="form-input form-select">
                            @foreach($allowed as $s)
                            @php $label = match($s) { 'pending' => 'Pesanan Dibuat', 'awaiting_payment' => 'Menunggu Pembayaran', 'paid' => 'Pembayaran Diterima', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'delivered' => 'Diterima', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', default => ucfirst($s) }; @endphp
                            <option value="{{ $s }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full btn-primary" style="padding:12px;border-radius:10px;">
                            Perbarui Status
                        </button>
                    </div>
                </form>
                @else
                <div style="padding:16px;background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;text-align:center;">
                    <div style="font-size:13px;color:var(--text-secondary);">Tidak ada transisi status tersedia.</div>
                </div>
                @endif
            </div>

            {{-- Shipment Management --}}
            @if(in_array($order->status, ['processing', 'shipped']))
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;">
                <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:var(--text);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <svg style="width:18px;height:18px;color:var(--cyan)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    Pengiriman
                </h3>

                @if($order->status === 'shipped' && $order->tracking_number)
                <div style="padding:16px;background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;margin-bottom:16px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:2px;">Kurir</div>
                            <div style="font-size:14px;color:var(--text);font-weight:600;">{{ strtoupper($order->shipping_courier ?? '-') }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:2px;">Service</div>
                            <div style="font-size:14px;color:var(--text);font-weight:600;">{{ strtoupper($order->shipping_service ?? '-') }}</div>
                        </div>
                        <div style="grid-column:span 2;">
                            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:2px;">Nomor Resi</div>
                            <div style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;color:var(--cyan);letter-spacing:1px;">{{ $order->tracking_number }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:2px;">Dikirim</div>
                            <div style="font-size:13px;color:var(--text);">{{ $order->shipped_at?->format('d M Y, H:i') ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:2px;">Estimasi Sampai</div>
                            <div style="font-size:13px;color:var(--text);">{{ $order->estimated_delivery ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                @endif

                @if($order->status === 'processing')
                <form action="{{ route('admin.orders.shipOrder', $order->order_number) }}" method="POST" id="shipForm">
                    @csrf
                    @method('PATCH')

                    <div style="display:flex;flex-direction:column;gap:14px;">
                        @if($errors->has('tracking_number'))
                        <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:10px;padding:12px 16px;">
                            <span style="font-size:13px;color:#f87171;">{{ $errors->first('tracking_number') }}</span>
                        </div>
                        @endif

                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Nomor Resi / Tracking *</label>
                            <input type="text" name="tracking_number" class="form-input" placeholder="Contoh: JNE1234567890" required style="font-family:'Space Grotesk',sans-serif;font-weight:600;letter-spacing:1px;">
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div>
                                <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Kurir</label>
                                <input type="text" name="shipping_courier" class="form-input" value="{{ $order->shipping_courier ?? '' }}" placeholder="JNE, SiCepat, dll">
                            </div>
                            <div>
                                <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Service</label>
                                <input type="text" name="shipping_service" class="form-input" value="{{ $order->shipping_service ?? '' }}" placeholder="REG, YES, dll">
                            </div>
                        </div>

                        <button type="submit" class="w-full btn-primary" style="padding:14px;border-radius:10px;font-size:14px;" onclick="return confirm('Konfirmasi pengiriman pesanan ini?')">
                            🚚 Konfirmasi Pengiriman
                        </button>
                    </div>
                </form>
                @endif
            </div>
            @endif

            <a href="{{ route('admin.orders.index') }}" style="display:block;text-align:center;padding:14px;color:var(--text-secondary);font-size:13px;font-weight:600;text-decoration:none;border:1px solid var(--border);border-radius:12px;transition:all .25s;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)';this.style.color='var(--text)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-secondary)'">
                Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>
</div>

<script>
function handleAdminOrderResize() {
    var w = window.innerWidth;
    var el = document.querySelector('.admin-order-layout');
    if (el) el.style.gridTemplateColumns = w < 900 ? '1fr' : '2fr 1fr';
}
window.addEventListener('resize', handleAdminOrderResize);
handleAdminOrderResize();
</script>
@endsection
