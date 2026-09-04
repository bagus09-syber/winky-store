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
            <h1 class="font-display font-bold text-3xl text-white mb-2">Selamat Datang Kembali</h1>
            <p class="text-white/50">Masuk ke akun kamu untuk melanjutkan belanja</p>
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
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-white/70 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 bg-[var(--color-navy-900)] border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:border-[var(--color-cyan-400)] focus:ring-1 focus:ring-[var(--color-cyan-400)] transition-colors"
                        placeholder="nama@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-white/70 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 bg-[var(--color-navy-900)] border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:border-[var(--color-cyan-400)] focus:ring-1 focus:ring-[var(--color-cyan-400)] transition-colors"
                        placeholder="Masukkan password">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-[var(--color-navy-900)] text-[var(--color-cyan-400)] focus:ring-[var(--color-cyan-400)] focus:ring-offset-0">
                        <span class="text-sm text-white/60">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-[var(--color-cyan-400)] hover:text-[var(--color-cyan-500)] transition-colors">Lupa password?</a>
                </div>

                <button type="submit" class="w-full py-3 btn-primary text-white font-semibold rounded-xl">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-white/50 text-sm mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-[var(--color-cyan-400)] hover:text-[var(--color-cyan-500)] font-medium transition-colors">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection
