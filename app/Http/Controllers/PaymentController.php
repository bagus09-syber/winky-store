<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function show(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('items.product', 'payment')
            ->firstOrFail();

        if (!$order->needsPayment()) {
            if ($order->isPaid()) {
                return redirect()->route('payment.success', $order->order_number);
            }
            return redirect()->route('orders.show', $order->order_number)
                ->with('info', 'Pesanan ini tidak memerlukan pembayaran.');
        }

        if ($order->payment && $order->status === 'awaiting_payment') {
            return view('payment.process', compact('order'));
        }

        $paymentMethods = $this->getPaymentMethods();

        return view('payment.show', compact('order', 'paymentMethods'));
    }

    public function process(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$order->needsPayment()) {
            return redirect()->route('orders.show', $order->order_number)
                ->with('info', 'Pesanan ini tidak memerlukan pembayaran.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:qris,bank_transfer,ewallet',
        ]);

        DB::beginTransaction();

        try {
            Payment::where('order_id', $order->id)
                ->where('status', 'pending')
                ->delete();

            $paymentData = $this->paymentService->createPayment($order, $validated['payment_method']);

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $paymentData['transaction_id'],
                'amount' => $order->total,
                'status' => 'pending',
                'payment_type' => $paymentData['payment_type'],
                'va_number' => $paymentData['va_number'] ?? null,
                'billing_code' => $paymentData['billing_code'] ?? null,
                'payment_code' => $paymentData['payment_code'] ?? null,
                'expiry_time' => $paymentData['expiry_time'],
                'raw_response' => $paymentData,
            ]);

            $order->update(['status' => 'awaiting_payment']);

            DB::commit();

            return redirect()->route('payment.show', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran.');
        }
    }

    public function processPayment(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('payment')
            ->firstOrFail();

        if (!$order->payment || $order->payment->status !== 'pending') {
            return redirect()->route('orders.show', $order->order_number)
                ->with('info', 'Pembayaran tidak valid.');
        }

        return view('payment.process', compact('order'));
    }

    public function simulateCallback(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order || !$order->payment || $order->payment->status !== 'pending') {
            return response()->json(['error' => 'Invalid order or payment'], 400);
        }

        $payment = $order->payment;

        $webhookPayload = [
            'transaction_id' => $payment->transaction_id,
            'order_id' => $order->id,
            'status' => 'paid',
            'status_code' => '200',
            'paid_at' => now()->toIso8601String(),
            'amount' => $order->total,
        ];

        $result = $this->paymentService->confirmPayment($payment->transaction_id, $webhookPayload);

        if ($result) {
            return redirect()->route('payment.success', $order->order_number)
                ->with('success', 'Pembayaran berhasil dikonfirmasi!');
        }

        return back()->with('error', 'Gagal mengkonfirmasi pembayaran.');
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();
        $headers = $request->headers->all();

        $result = $this->paymentService->processWebhook($payload, $headers);

        if ($result) {
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error'], 400);
    }

    public function success(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('items.product', 'payment')
            ->firstOrFail();

        return view('payment.success', compact('order'));
    }

    protected function getPaymentMethods(): array
    {
        return [
            'qris' => [
                'name' => 'QRIS',
                'description' => 'Scan QR Code untuk pembayaran instan',
                'icon' => 'qris',
                'enabled' => true,
            ],
            'bank_transfer' => [
                'name' => 'Transfer Bank',
                'description' => 'Transfer melalui ATM, Mobile Banking, atau Internet Banking',
                'icon' => 'bank',
                'enabled' => true,
                'banks' => [
                    ['code' => 'bca', 'name' => 'Bank BCA', 'account' => '8808 0000 1234 5678'],
                    ['code' => 'bni', 'name' => 'Bank BNI', 'account' => '8808 0000 8765 4321'],
                    ['code' => 'mandiri', 'name' => 'Bank Mandiri', 'account' => '8808 0000 1122 3344'],
                ],
            ],
            'ewallet' => [
                'name' => 'E-Wallet',
                'description' => 'Bayar dengan GoPay, OVO, atau Dana',
                'icon' => 'ewallet',
                'enabled' => true,
                'wallets' => [
                    ['code' => 'gopay', 'name' => 'GoPay'],
                    ['code' => 'ovo', 'name' => 'OVO'],
                    ['code' => 'dana', 'name' => 'Dana'],
                ],
            ],
        ];
    }
}
