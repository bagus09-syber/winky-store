<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function help()
    {
        return view('help.index');
    }

    public function contact()
    {
        $user = Auth::user();
        $orders = $user ? $user->orders()->whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])->latest()->take(10)->get() : collect();

        return view('help.contact', compact('orders'));
    }

    public function submitTicket(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'order_number' => 'nullable|string|max:50',
        ]);

        $orderId = null;
        if (!empty($validated['order_number'])) {
            $order = Order::where('order_number', $validated['order_number'])->first();
            if ($order) {
                $orderId = $order->id;
            }
        }

        SupportTicket::create([
            'user_id' => Auth::id(),
            'order_id' => $orderId,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'open',
        ]);

        return redirect()->route('help.contact')
            ->with('success', 'Tiket support berhasil dikirim. Kami akan merespon segera.');
    }

    public function myTickets()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('account.tickets', compact('tickets'));
    }
}
