@extends('layouts.app')
@section('content')
<section style="padding-top:100px;padding-bottom:80px;min-height:100vh;">
    <div class="container" style="max-width:1100px;">

        <div style="margin-bottom:32px;">
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:clamp(24px,3vw,32px);font-weight:800;color:var(--text);margin:0;">Checkout</h1>
            <p style="font-size:14px;color:var(--text-secondary);margin-top:4px;">Selesaikan pesanan kamu</p>
        </div>

        @if($errors->any())
        <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:14px;padding:16px 20px;margin-bottom:24px;">
            <ul style="margin:0;padding:0;list-style:none;">
                @foreach($errors->all() as $error)
                <li style="font-size:13px;color:#f87171;padding:2px 0;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 380px;gap:28px;" class="checkout-layout">

                <div style="display:flex;flex-direction:column;gap:20px;">

                    {{-- STEP 1: Delivery Address --}}
                    <div class="checkout-section" id="step-address">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                            <div class="sec-num" style="width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,var(--cyan),var(--blue));display:flex;align-items:center;justify-content:center;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:14px;color:#000;flex-shrink:0;">1</div>
                            <h3 style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;color:var(--text);margin:0;">Alamat Pengiriman</h3>
                        </div>

                        <input type="hidden" name="address_id" id="addressId" value="{{ $defaultAddress?->id }}">

                        @if($addresses->isEmpty())
                        <div style="text-align:center;padding:32px 20px;">
                            <svg style="width:40px;height:40px;color:var(--text-secondary);margin:0 auto 12px;opacity:0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p style="font-size:14px;color:var(--text-secondary);margin-bottom:12px;">Belum ada alamat tersimpan</p>
                            <a href="{{ route('account.addresses') }}" style="font-size:13px;color:var(--cyan);text-decoration:none;">+ Tambah Alamat</a>
                        </div>
                        @else
                        <div style="display:flex;flex-direction:column;gap:10px;" id="addressList">
                            @foreach($addresses as $address)
                            <div class="address-card {{ $address->is_default ? 'selected' : '' }}" data-id="{{ $address->id }}" onclick="selectAddress(this, {{ $address->id }})" style="padding:16px;border:1px solid {{ $address->is_default ? 'rgba(0,229,255,0.4)' : 'var(--border)' }};border-radius:12px;cursor:pointer;transition:all .25s;background:{{ $address->is_default ? 'rgba(0,229,255,0.04)' : 'var(--bg-deep)' }};">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                                    <div style="font-weight:600;color:var(--text);font-size:14px;">{{ $address->recipient_name }}</div>
                                    @if($address->is_default)
                                    <span style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:rgba(0,229,255,0.1);color:var(--cyan);">Utama</span>
                                    @endif
                                </div>
                                <div style="font-size:13px;color:var(--text-secondary);line-height:1.5;">{{ $address->phone }}<br>{{ $address->address }}, {{ $address->district ? $address->district . ', ' : '' }}{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</div>
                            </div>
                            @endforeach
                        </div>

                        <div style="margin-top:12px;display:flex;gap:8px;">
                            <input type="text" name="recipient_name" value="{{ old('recipient_name', $defaultAddress?->recipient_name ?? Auth::user()->name) }}" placeholder="Nama Penerima *" class="form-input" style="flex:1;" required>
                            <input type="text" name="recipient_phone" value="{{ old('recipient_phone', $defaultAddress?->phone ?? Auth::user()->phone) }}" placeholder="Telepon *" class="form-input" style="flex:1;" required>
                        </div>
                        <textarea name="shipping_address" id="shippingAddress" class="form-input form-textarea" rows="2" placeholder="Detail Alamat (Jl, RT/RW, No, dll) *" style="margin-top:8px;" required>{{ old('shipping_address', $defaultAddress?->address . ', ' . $defaultAddress->district . ', ' . $defaultAddress->city . ', ' . $defaultAddress->province . ' ' . $defaultAddress->postal_code) }}</textarea>
                        @endif
                    </div>

                    {{-- STEP 2: Shipping Method --}}
                    <div class="checkout-section" id="step-shipping">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                            <div class="sec-num" style="width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,var(--cyan),var(--blue));display:flex;align-items:center;justify-content:center;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:14px;color:#000;flex-shrink:0;">2</div>
                            <h3 style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;color:var(--text);margin:0;">Metode Pengiriman</h3>
                            <span style="font-size:12px;color:var(--text-secondary);margin-left:auto;">Berat: {{ number_format($totalWeight / 1000, 1) }} kg</span>
                        </div>

                        @if(empty($shippingRates))
                        <div style="text-align:center;padding:32px 20px;">
                            <svg style="width:40px;height:40px;color:var(--text-secondary);margin:0 auto 12px;opacity:0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <p style="font-size:14px;color:var(--text-secondary);">Tidak ada metode pengiriman tersedia.</p>
                        </div>
                        @else
                        <div style="display:flex;flex-direction:column;gap:8px;" id="shippingRates">
                            @foreach($shippingRates as $rate)
                            <label style="display:flex;align-items:center;gap:14px;padding:14px 16px;border:1px solid var(--border);border-radius:12px;cursor:pointer;transition:all .25s;background:var(--bg-deep);" class="shipping-option" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)'" onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='var(--border)'">
                                <input type="radio" name="shipping_courier" value="{{ $rate['courier'] }}" data-service="{{ $rate['service'] }}" data-price="{{ $rate['price'] }}" data-days="{{ $rate['estimated_days'] }}" data-date="{{ $rate['estimated_date'] }}" onchange="selectShipping(this)" style="accent-color:var(--cyan);width:18px;height:18px;" required>
                                <div style="flex:1;min-width:0;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;">
                                        <div>
                                            <span style="font-weight:600;font-size:14px;color:var(--text);">{{ $rate['courier'] }}</span>
                                            <span style="font-size:13px;color:var(--text-secondary);margin-left:6px;">{{ $rate['service'] }}</span>
                                        </div>
                                        <div style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:14px;color:var(--cyan);">Rp {{ number_format($rate['price'], 0, ',', '.') }}</div>
                                    </div>
                                    <div style="font-size:12px;color:var(--text-secondary);margin-top:3px;">
                                        Estimasi tiba: {{ $rate['estimated_date'] }} ({{ $rate['estimated_days'] }} hari)
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <input type="hidden" name="shipping_service" id="selectedService" value="{{ $shippingRates[0]['service'] ?? '' }}">
                        <input type="hidden" name="shipping_cost" id="selectedCost" value="{{ $shippingRates[0]['price'] ?? 0 }}">
                        @endif
                    </div>

                    {{-- STEP 3: Voucher --}}
                    <div class="checkout-section">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                            <div class="sec-num" style="width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,var(--magenta),var(--blue));display:flex;align-items:center;justify-content:center;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:14px;color:#fff;flex-shrink:0;">3</div>
                            <h3 style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;color:var(--text);margin:0;">Voucher / Promo</h3>
                        </div>

                        <div style="display:flex;gap:8px;">
                            <input type="text" name="voucher_code" id="voucherCode" value="{{ old('voucher_code') }}" placeholder="Masukkan kode voucher" class="form-input" style="flex:1;text-transform:uppercase;" maxlength="50">
                            <button type="button" onclick="applyVoucher()" style="padding:10px 20px;background:var(--bg-deep);border:1px solid var(--border);border-radius:10px;color:var(--text);font-size:13px;font-weight:600;cursor:pointer;transition:all .25s;white-space:nowrap;" onmouseover="this.style.borderColor='rgba(0,229,255,0.3)'" onmouseout="this.style.borderColor='var(--border)'">Terapkan</button>
                        </div>
                        <div id="voucherMessage" style="margin-top:8px;font-size:12px;display:none;"></div>
                        <input type="hidden" name="voucher_discount" id="voucherDiscount" value="0">
                    </div>

                    {{-- Notes --}}
                    <div class="checkout-section">
                        <h3 style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;color:var(--text);margin:0 0 12px;">Catatan Pesanan</h3>
                        <textarea name="notes" class="form-input form-textarea" rows="2" placeholder="Catatan untuk penjual (opsional)">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- ORDER SUMMARY --}}
                <div style="position:sticky;top:100px;" class="checkout-sidebar">
                    <div class="checkout-summary">
                        <h3 style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;color:var(--text);margin:0 0 20px;padding-bottom:16px;border-bottom:1px solid var(--border);">Ringkasan Pesanan</h3>

                        <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:20px;max-height:240px;overflow-y:auto;">
                            @foreach($cart->items as $item)
                            <div style="display:flex;gap:10px;align-items:center;">
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
                                <div style="font-family:'Space Grotesk',sans-serif;font-size:13px;font-weight:600;color:var(--text);flex-shrink:0;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </div>
                            @endforeach
                        </div>

                        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
                            <div class="checkout-total-row">
                                <span>Subtotal</span>
                                <span style="font-weight:600;color:var(--text);" id="summarySubtotal">Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</span>
                            </div>
                            <div class="checkout-total-row">
                                <span>Pengiriman</span>
                                <span style="font-weight:600;color:var(--text);" id="summaryShipping">Rp {{ number_format($shippingRates[0]['price'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="checkout-total-row" id="summaryVoucherRow" style="display:{{ old('voucher_code') ? 'flex' : 'none' }};">
                                <span>Voucher</span>
                                <span style="font-weight:600;color:#4ade80;" id="summaryVoucher">-Rp 0</span>
                            </div>
                        </div>

                        <div class="checkout-total-row grand">
                            <span>Total</span>
                            <span style="font-family:'Space Grotesk',sans-serif;font-weight:800;font-size:22px;color:var(--cyan);" id="summaryTotal">Rp {{ number_format($cart->items->sum('subtotal') + ($shippingRates[0]['price'] ?? 0), 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="w-full btn-primary" style="margin-top:20px;padding:16px;border-radius:12px;font-size:15px;{{ empty($shippingRates) ? 'opacity:0.5;cursor:not-allowed;' : '' }}" {{ empty($shippingRates) ? 'disabled' : '' }}>
                            Buat Pesanan
                        </button>

                        <p style="font-size:11px;color:var(--text-secondary);text-align:center;margin-top:12px;">Dengan membuat pesanan, kamu menyetujui syarat & ketentuan yang berlaku.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
let subtotal = {{ $cart->items->sum('subtotal') }};
let shippingCost = {{ $shippingRates[0]['price'] ?? 0 }};
let voucherDiscount = 0;

function selectAddress(el, id) {
    document.querySelectorAll('.address-card').forEach(function(c) {
        c.style.borderColor = 'var(--border)';
        c.style.background = 'var(--bg-deep)';
    });
    el.style.borderColor = 'rgba(0,229,255,0.4)';
    el.style.background = 'rgba(0,229,255,0.04)';
    document.getElementById('addressId').value = id;
}

function selectShipping(input) {
    var card = input.closest('.shipping-option');
    document.querySelectorAll('.shipping-option').forEach(function(c) {
        c.style.borderColor = 'var(--border)';
        c.style.background = 'var(--bg-deep)';
    });
    card.style.borderColor = 'rgba(0,229,255,0.4)';
    card.style.background = 'rgba(0,229,255,0.04)';

    shippingCost = parseInt(input.dataset.price);
    document.getElementById('selectedService').value = input.dataset.service;
    document.getElementById('selectedCost').value = shippingCost;
    updateSummary();
}

function updateSummary() {
    document.getElementById('summaryShipping').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
    document.getElementById('summarySubtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('summaryVoucher').textContent = '-Rp ' + voucherDiscount.toLocaleString('id-ID');
    document.getElementById('summaryVoucherRow').style.display = voucherDiscount > 0 ? 'flex' : 'none';
    document.getElementById('summaryTotal').textContent = 'Rp ' + (subtotal + shippingCost - voucherDiscount).toLocaleString('id-ID');
}

function applyVoucher() {
    var code = document.getElementById('voucherCode').value.trim();
    if (!code) return;

    var msg = document.getElementById('voucherMessage');
    msg.style.display = 'block';
    msg.style.color = 'var(--text-secondary)';
    msg.textContent = 'Memeriksa voucher...';

    fetch('{{ route("api.validateVoucher") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ code: code, subtotal: subtotal })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.valid) {
            voucherDiscount = data.discount;
            msg.style.color = '#4ade80';
            msg.textContent = 'Voucher diterapkan! Diskon: Rp ' + voucherDiscount.toLocaleString('id-ID');
            document.getElementById('voucherDiscount').value = voucherDiscount;
        } else {
            voucherDiscount = 0;
            msg.style.color = '#f87171';
            msg.textContent = data.message || 'Voucher tidak valid.';
            document.getElementById('voucherDiscount').value = 0;
        }
        updateSummary();
    })
    .catch(function() {
        msg.style.color = '#f87171';
        msg.textContent = 'Gagal memeriksa voucher.';
    });
}

function handleCheckoutResize() {
    var w = window.innerWidth;
    var el = document.querySelector('.checkout-layout');
    if (el) el.style.gridTemplateColumns = w < 768 ? '1fr' : '1fr 380px';
}
window.addEventListener('resize', handleCheckoutResize);
handleCheckoutResize();
</script>
@endsection
