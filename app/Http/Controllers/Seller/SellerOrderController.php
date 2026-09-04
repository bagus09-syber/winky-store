<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    public function index(Request $request)
    {
        $store = Auth::user()->store;
        $query = Order::where('store_id', $store->id)->with('user');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->status) {
            $query->where('seller_status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $store = Auth::user()->store;

        if ($order->store_id !== $store->id) {
            abort(403);
        }

        $order->load(['user', 'items.product', 'payment', 'address']);

        return view('seller.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $store = Auth::user()->store;

        if ($order->store_id !== $store->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:processing,shipped,delivered,completed,cancelled',
        ]);

        $validTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ];

        $currentStatus = $order->seller_status;
        $allowed = $validTransitions[$currentStatus] ?? [];

        if (!in_array($validated['status'], $allowed)) {
            return back()->with('error', 'Transisi status tidak valid.');
        }

        $updateData = ['seller_status' => $validated['status']];

        if ($validated['status'] === 'shipped') {
            $updateData['seller_shipped_at'] = now();
        }

        $order->update($updateData);

        if ($validated['status'] === 'completed') {
            $wallet = $order->store->wallet;
            if ($wallet) {
                $wallet->movePendingToAvailable((float) $order->seller_earning);
                $wallet->store->increment('total_sales', $order->items->sum('quantity'));

                \App\Models\SellerTransaction::create([
                    'store_id' => $order->store_id,
                    'order_id' => $order->id,
                    'type' => 'sale',
                    'amount' => $order->seller_earning,
                    'balance_after' => $wallet->fresh()->available_balance,
                    'description' => "Pesanan #{$order->order_number} selesai",
                ]);
            }
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
