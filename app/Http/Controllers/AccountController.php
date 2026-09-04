<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function dashboard()
    {
        return view('account.dashboard');
    }

    public function profile()
    {
        return view('account.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function security()
    {
        return view('account.security');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama yang kamu masukkan salah.',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function addresses()
    {
        $addresses = Address::where('user_id', Auth::id())->orderBy('is_default', 'desc')->get();
        return view('account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'required|string',
            'is_default' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();

        if (!empty($validated['is_default']) && $validated['is_default']) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        Address::create($validated);

        return back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function editAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        return view('account.edit-address', compact('address'));
    }

    public function updateAddress(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'required|string',
            'is_default' => 'boolean',
        ]);

        if (!empty($validated['is_default']) && $validated['is_default']) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('account.addresses')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroyAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefaultAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Alamat utama berhasil diatur.');
    }

    public function rewards()
    {
        $user = Auth::user();
        $loyaltyService = new \App\Services\LoyaltyService();
        $account = $loyaltyService->getAccountWithBalance($user);

        $rewards = \App\Models\Reward::where('is_active', true)->get();

        $recentTransactions = $user->loyaltyTransactions()
            ->latest('created_at')
            ->take(10)
            ->get();

        return view('account.rewards', compact('account', 'rewards', 'recentTransactions'));
    }

    public function referrals()
    {
        $user = Auth::user();
        $referralService = new \App\Services\ReferralService();
        $referralInfo = $referralService->getReferralStats($user->id);

        $referralCode = ReferralCode::where('user_id', $user->id)->first();

        $referralLink = route('account.referrals') . "?code=" . ($referralCode?->code ?? '');

        $referredInfo = \App\Models\Referral::where('referrer_id', $user->id)
            ->with('referredUser')
            ->get();

        return view('account.referrals', compact('referralInfo', 'referralCode', 'referralLink', 'referredInfo'));
    }
}