@extends('layouts.account')

@section('account-content')
<div class="acct-header">
    <h1 class="acct-title">Security</h1>
    <p class="acct-subtitle">Manage your password and account security</p>
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
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:28px;padding-bottom:24px;border-bottom:1px solid var(--border);">
        <div style="width:52px;height:52px;border-radius:16px;background:rgba(168,85,247,0.1);display:flex;align-items:center;justify-content:center;color:#c084fc;flex-shrink:0;">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <div>
            <h2 style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:18px;color:var(--text);">Change Password</h2>
            <p style="font-size:13px;color:var(--text-secondary);margin-top:2px;">Update your password regularly to keep your account secure</p>
        </div>
    </div>

    <form method="POST" action="{{ route('account.password.update') }}" class="acct-form">
        @csrf
        @method('PUT')

        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="acct-field">
                <label class="acct-label" for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required class="acct-input" placeholder="Enter your current password">
            </div>

            <div style="height:1px;background:var(--border);margin:4px 0;"></div>

            <div class="acct-field">
                <label class="acct-label" for="password">New Password</label>
                <input type="password" id="password" name="password" required class="acct-input" placeholder="Minimum 8 characters">
            </div>

            <div class="acct-field">
                <label class="acct-label" for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="acct-input" placeholder="Re-enter your new password">
            </div>
        </div>

        <div class="acct-form-actions">
            <a href="{{ route('account.dashboard') }}" class="btn-acct-secondary">Cancel</a>
            <button type="submit" class="btn-acct-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Update Password
            </button>
        </div>
    </form>
</div>
@endsection
