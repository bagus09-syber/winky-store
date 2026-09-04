<?php

namespace App\Services\Payment\Providers;

use App\Models\Order;
use App\Models\Payment;

class MidtransPaymentProvider implements PaymentProviderInterface
{
    public function createPayment(Order $order, string $method): array
    {
        return [
            'transaction_id' => 'MID-' . strtoupper(uniqid()) . '-' . $order->id,
            'amount' => $order->total,
            'payment_method' => $method,
            'status' => 'pending',
            'expiry_time' => now()->addHours(24)->toIso8601String(),
            'payment_type' => $method,
            'redirect_url' => config('services.midtrans.redirect_url', '#'),
            'snap_token' => 'MID-SNAP-' . strtoupper(uniqid()),
        ];
    }

    public function getPaymentStatus(Payment $payment): string
    {
        // In production: call Midtrans API
        return $payment->status;
    }

    public function verifyWebhook(array $payload, array $headers): bool
    {
        $signatureKey = $payload['signature_key'] ?? '';
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $serverKey = config('services.midtrans.server_key', '');

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }

    public function processWebhook(array $payload): ?array
    {
        $transactionId = $payload['transaction_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        if (!$transactionId || !$statusCode) return null;

        $statusMap = [
            'capture' => 'paid',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny' => 'failed',
            'cancel' => 'failed',
            'expire' => 'expired',
            'refund' => 'refunded',
        ];

        $status = $statusMap[$transactionStatus] ?? 'pending';

        return [
            'transaction_id' => $transactionId,
            'status' => $status,
            'paid_at' => $payload['settlement_time'] ?? $payload['payment_time'] ?? now()->toIso8601String(),
            'raw' => $payload,
        ];
    }

    public function cancelPayment(Payment $payment): bool
    {
        // In production: call Midtrans API
        $payment->update(['status' => 'failed']);
        return true;
    }

    public function refundPayment(Payment $payment, float $amount, string $reason): array
    {
        // In production: call Midtrans API
        return [
            'refund_id' => 'MID-REF-' . strtoupper(uniqid()),
            'amount' => $amount,
            'status' => 'refunded',
            'reason' => $reason,
        ];
    }

    public function getProviderName(): string
    {
        return 'midtrans';
    }

    public function isAvailable(): bool
    {
        return !empty(config('services.midtrans.server_key'));
    }
}
