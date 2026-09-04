@extends('layouts.account')

@section('account-content')
<div class="acct-header">
    <div class="acct-header-row">
        <div>
            <h1 class="acct-title">My Addresses</h1>
            <p class="acct-subtitle">Manage your shipping addresses</p>
        </div>
        <button onclick="document.getElementById('add-address-modal').classList.remove('hidden')" class="btn-acct-primary">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Address
        </button>
    </div>
</div>

@if ($addresses->isEmpty())
<div class="acct-empty" style="max-width:480px;margin:0 auto;">
    <div class="acct-empty-icon">
        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </div>
    <h3 class="acct-empty-title">No addresses saved</h3>
    <p class="acct-empty-text">Add your first shipping address for faster checkout</p>
    <button onclick="document.getElementById('add-address-modal').classList.remove('hidden')" class="btn-acct-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add First Address
    </button>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
    @foreach ($addresses as $address)
    <div class="acct-address {{ $address->is_default ? 'is-default' : '' }}">
        @if ($address->is_default)
        <span class="acct-address-badge">Default</span>
        @endif

        <div class="acct-address-name">{{ $address->recipient_name }}</div>
        <div class="acct-address-phone">{{ $address->phone }}</div>
        <div class="acct-address-text">
            {{ $address->address }}<br>
            {{ $address->district ? $address->district . ', ' : '' }}{{ $address->city }}, {{ $address->province }} {{ $address->postal_code ? '- ' . $address->postal_code : '' }}
        </div>

        <div class="acct-address-actions">
            @if (!$address->is_default)
            <form method="POST" action="{{ route('account.addresses.setDefault', $address) }}">
                @csrf
                @method('PUT')
                <button type="submit" style="color:var(--cyan);">Set as Default</button>
            </form>
            @endif
            <a href="{{ route('account.addresses.edit', $address) }}" style="color:var(--text-secondary);transition:color .2s;" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--text-secondary)'">Edit</a>
            <form method="POST" action="{{ route('account.addresses.destroy', $address) }}" onsubmit="return confirm('Delete this address?')">
                @csrf
                @method('DELETE')
                <button type="submit" style="color:#f87171;">Delete</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Add Address Modal --}}
<div id="add-address-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);">
    <div style="width:100%;max-width:560px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:32px;max-height:90vh;overflow-y:auto;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
            <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:20px;color:var(--text);">New Address</h3>
            <button onclick="document.getElementById('add-address-modal').classList.add('hidden')" style="width:36px;height:36px;border-radius:12px;border:1px solid var(--border);background:transparent;color:var(--text-secondary);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;" onmouseover="this.style.borderColor='var(--border-light)';this.style.color='var(--text)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-secondary)'">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('account.addresses.store') }}" style="display:flex;flex-direction:column;gap:16px;">
            @csrf

            <div class="acct-form-grid">
                <div class="acct-field">
                    <label class="acct-label">Recipient Name</label>
                    <input type="text" name="recipient_name" required class="acct-input" placeholder="Full name">
                </div>
                <div class="acct-field">
                    <label class="acct-label">Phone Number</label>
                    <input type="text" name="phone" required class="acct-input" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div class="acct-form-grid">
                <div class="acct-field">
                    <label class="acct-label">Province</label>
                    <input type="text" name="province" required class="acct-input" placeholder="Province">
                </div>
                <div class="acct-field">
                    <label class="acct-label">City</label>
                    <input type="text" name="city" required class="acct-input" placeholder="City">
                </div>
            </div>

            <div class="acct-form-grid">
                <div class="acct-field">
                    <label class="acct-label">District</label>
                    <input type="text" name="district" class="acct-input" placeholder="Optional">
                </div>
                <div class="acct-field">
                    <label class="acct-label">Postal Code</label>
                    <input type="text" name="postal_code" class="acct-input" placeholder="Optional">
                </div>
            </div>

            <div class="acct-field">
                <label class="acct-label">Full Address</label>
                <textarea name="address" rows="3" required class="acct-input acct-textarea" placeholder="Street, house number, RT/RW, etc."></textarea>
            </div>

            <label class="acct-checkbox-row">
                <input type="checkbox" name="is_default" value="1">
                <span class="acct-checkbox-label">Set as default address</span>
            </label>

            <div style="display:flex;gap:12px;margin-top:8px;">
                <button type="button" onclick="document.getElementById('add-address-modal').classList.add('hidden')" class="btn-acct-secondary" style="flex:1;justify-content:center;">Cancel</button>
                <button type="submit" class="btn-acct-primary" style="flex:1;justify-content:center;">Save Address</button>
            </div>
        </form>
    </div>
</div>

<style>
@media(max-width:768px){
    div[style*="grid-template-columns:repeat(2"]{ grid-template-columns: 1fr !important; }
}
</style>
@endsection
