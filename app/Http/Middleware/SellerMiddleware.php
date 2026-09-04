<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->store) {
            abort(403, 'Anda belum memiliki toko. Silakan daftar sebagai seller terlebih dahulu.');
        }

        if ($user->store->status !== 'active') {
            abort(403, 'Toko Anda belum aktif. Status: ' . ucfirst($user->store->status));
        }

        return $next($request);
    }
}
