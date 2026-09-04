<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\SellerWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SellerRegistrationController extends Controller
{
    public function showRegister()
    {
        if (Auth::user()->store) {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.register');
    }

    public function register(Request $request)
    {
        $user = Auth::user();

        if ($user->store) {
            return redirect()->route('seller.dashboard');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'required|string|max:500',
        ]);

        $slug = Str::slug($validated['name']);
        $existing = Store::where('slug', $slug)->first();
        if ($existing) {
            $slug = $slug . '-' . Str::random(5);
        }

        $store = Store::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'phone' => $validated['phone'] ?? $user->phone,
            'email' => $validated['email'] ?? $user->email,
            'province' => $validated['province'],
            'city' => $validated['city'],
            'district' => $validated['district'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'address' => $validated['address'],
            'status' => 'pending',
        ]);

        SellerWallet::create(['store_id' => $store->id]);

        return redirect()->route('seller.dashboard')
            ->with('success', 'Toko berhasil didaftarkan! Menunggu persetujuan admin.');
    }
}
