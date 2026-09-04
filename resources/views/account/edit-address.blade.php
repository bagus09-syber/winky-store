@extends('layouts.account')

@section('account-content')
<div class="acct-header">
    <div class="acct-header-row">
        <div>
            <h1 class="acct-title">Edit Address</h1>
            <p class="acct-subtitle">Update your shipping address information</p>
        </div>
        <a href="{{ route('account.addresses') }}" class="btn-acct-secondary" style="align-self:flex-start;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>
</div>

@if ($errors->any())
<div class="acct-alert acct-alert-error">
    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div>
        @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
</div>
@endif

<div class="acct-card">
    <form method="POST" action="{{ route('account.addresses.update', $address) }}" class="acct-form">
        @csrf
        @method('PUT')

        <div class="acct-form-grid">
            <div class="acct-field">
                <label class="acct-label">Recipient Name</label>
                <input type="text" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" required class="acct-input" placeholder="Full name">
            </div>
            <div class="acct-field">
                <label class="acct-label">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $address->phone) }}" required class="acct-input" placeholder="08xxxxxxxxxx">
            </div>
        </div>

        <div class="acct-form-grid" style="margin-top:20px;">
            <div class="acct-field">
                <label class="acct-label">Province</label>
                <input type="text" name="province" value="{{ old('province', $address->province) }}" required class="acct-input" placeholder="Province">
            </div>
            <div class="acct-field">
                <label class="acct-label">City</label>
                <input type="text" name="city" value="{{ old('city', $address->city) }}" required class="acct-input" placeholder="City">
            </div>
        </div>

        <div class="acct-form-grid" style="margin-top:20px;">
            <div class="acct-field">
                <label class="acct-label">District</label>
                <input type="text" name="district" value="{{ old('district', $address->district) }}" class="acct-input" placeholder="Optional">
            </div>
            <div class="acct-field">
                <label class="acct-label">Postal Code</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" class="acct-input" placeholder="Optional">
            </div>
        </div>

        <div class="acct-field" style="margin-top:20px;">
            <label class="acct-label">Full Address</label>
            <textarea name="address" rows="3" required class="acct-input acct-textarea" placeholder="Street, house number, RT/RW, etc.">{{ old('address', $address->address) }}</textarea>
        </div>

        <label class="acct-checkbox-row" style="margin-top:20px;">
            <input type="checkbox" name="is_default" value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
            <span class="acct-checkbox-label">Set as default address</span>
        </label>

        <div class="acct-form-actions">
            <a href="{{ route('account.addresses') }}" class="btn-acct-secondary">Cancel</a>
            <button type="submit" class="btn-acct-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
