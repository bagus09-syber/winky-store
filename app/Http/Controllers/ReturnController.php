<?php

namespace App\Http\Controllers;

use App\Models\ReturnRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function create(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->canRequestReturn()) {
            return back()->with('error', 'Pesanan ini tidak bisa diajukan return.');
        }

        return view('account.return-create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->canRequestReturn()) {
            return back()->with('error', 'Pesanan ini tidak bisa diajukan return.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        ReturnRequest::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'status' => 'requested',
        ]);

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Request return berhasil diajukan.');
    }

    public function myReturns()
    {
        $returns = ReturnRequest::where('user_id', Auth::id())
            ->with('order')
            ->latest()
            ->paginate(10);

        return view('account.returns', compact('returns'));
    }
}
