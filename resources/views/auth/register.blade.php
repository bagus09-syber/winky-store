@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-20">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                <div class="w-12 h-12 rounded-xl glow-cyan flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="gradient-text font-display font-bold text-2xl">WINKY STORE</span>
            </a>
            <h1 class="font-display font-bold text-3xl text-white mb-2">Buat Akun Baru</h1>
            <p class="text-white/50">Daftar untuk mulai berbelanja di WINKY STORE</p>
        </div>

        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
            <div class="flex items-center gap-2 text-red-400 text-sm font-medium mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Terjadi kesalahan
            </div>
            <ul class="text-red-300/80 text-sm list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-[var(--color-navy-800)] border border-white/5 rounded-2xl p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-white/70 mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 bg-[var(--color-navy-900)] border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:border-[var(--color-cyan-400)] focus:ring-1 focus:ring-[var(--color-cyan-400)] transition-colors"
                        placeholder="Masukkan nama lengkap">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-white/70 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-[var(--color-navy-900)] border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:border-[var(--color-cyan-400)] focus:ring-1 focus:ring-[var(--color-cyan-400)] transition-colors"
                        placeholder="nama@email.com">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-white/70 mb-2">Nomor HP</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                        class="w-full px-4 py-3 bg-[var(--color-navy-900)] border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:border-[var(--color-cyan-400)] focus:ring-1 focus:ring-[var(--color-cyan-400)] transition-colors"
                        placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-white/70 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 bg-[var(--color-navy-900)] border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:border-[var(--color-cyan-400)] focus:ring-1 focus:ring-[var(--color-cyan-400)] transition-colors"
                        placeholder="Minimal 8 karakter">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-white/70 mb-2">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 bg-[var(--color-navy-900)] border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:border-[var(--color-cyan-400)] focus:ring-1 focus:ring-[var(--color-cyan-400)] transition-colors"
                        placeholder="Ulangi password">
                </div>

                <button type="submit" class="w-full py-3 btn-primary text-white font-semibold rounded-xl">
                    Daftar
                </button>
            </form>
        </div>

        <p class="text-center text-white/50 text-sm mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-[var(--color-cyan-400)] hover:text-[var(--color-cyan-500)] font-medium transition-colors">Masuk</a>
        </p>
    </div>
</div>
@endsection
