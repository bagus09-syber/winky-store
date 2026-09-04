@extends('layouts.account')

@section('account-content')
<div class="acct-header">
    <h1 class="acct-title">My Profile</h1>
    <p class="acct-subtitle">Manage your personal information</p>
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
    {{-- Profile Header --}}
    <div class="acct-profile-head">
        <div class="acct-profile-avatar">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div>
            <h2 class="acct-profile-name">{{ Auth::user()->name }}</h2>
            <p class="acct-profile-email">{{ Auth::user()->email }}</p>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('account.profile.update') }}" class="acct-form">
        @csrf
        @method('PUT')

        <div class="acct-form-grid">
            <div class="acct-field">
                <label class="acct-label" for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required class="acct-input" placeholder="Enter your full name">
            </div>

            <div class="acct-field">
                <label class="acct-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="acct-input" placeholder="Enter your email">
            </div>

            <div class="acct-field">
                <label class="acct-label" for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" required class="acct-input" placeholder="Enter your phone number">
            </div>
        </div>

        <div class="acct-form-actions">
            <a href="{{ route('account.dashboard') }}" class="btn-acct-secondary">Cancel</a>
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
