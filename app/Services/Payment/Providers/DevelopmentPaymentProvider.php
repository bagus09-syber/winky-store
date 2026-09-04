<?php

namespace App\Services\Payment\Providers;

use App\Models\Order;
use App\Models\Payment;

class DevelopmentPaymentProvider implements PaymentProviderInterface
{
    public function createPayment(Order $order, string $method): array
    {
        $transactionId = 'DEV-' . strtoupper(uniqid()) . '-' . $order->id;
        $expiry = now()->addHours(24);

        $data = [
            'transaction_id' => $transactionId,
            'amount' => $order->total,
            'payment_method' => $method,
            'status' => 'pending',
            'expiry_time' => $expiry->toIso8601String(),
            'payment_type' => $method,
        ];

        switch ($method) {
            case 'qris':
                $data['qr_string'] = '00020101021226690016COM.WINKY.STORE0118ID12345678901234560215WINKY' . $order->id . '0303IDR51440016ID.CO.WINKY.WWW0215ID12345678901234560303IDR5204123453033605802ID5404' . number_format($order->total, 0, ',', '.');
                $data['billing_code'] = 'QR' . str_pad($order->id, 10, '0', STR_PAD_LEFT);
                break;

            case 'bank_transfer':
                $bankPrefixes = ['BCA' => '8808', 'BNI' => '8818', 'Mandiri' => '8828'];
                $bank = $data['payment_type'] ?? 'BCA';
                $prefix = $bankPrefixes[$bank] ?? '8808';
                $data['va_number'] = $prefix . str_pad($order->id, 10, '0', STR_PAD_LEFT);
                $data['bank_name'] = $bank;
                break;

            case 'ewallet':
                $data['payment_code'] = 'EW' . str_pad($order->id, 10, '0', STR_PAD_LEFT);
                $data['reference'] = 'REF-' . strtoupper(uniqid());
                break;
        }

        return $data;
    }

    public function getPaymentStatus(Payment $payment): string
    {
        if ($payment->status === 'paid') return 'paid';
        if ($payment->status === 'failed') return 'failed';
        if ($payment->expiry_time && $payment->expiry_time->isPast()) return 'expired';
        return 'pending';
    }

    public function verifyWebhook(array $payload, array $headers): bool
    {
        // Development mode: accept all webhooks for testing
        // Production: implement proper signature verification
        // Signature should be verified using provider's secret key
        // Contoh: hash_hmac('sha256', json_encode($payload), $this->getWebhookSecret())
        
        // For development, we log that this is running in dev mode
        // dan filter sensitive data dari payload sebelum log
        return true;
    }

    public function processWebhook(array $payload): ?array
    {
        $transactionId = $payload['transaction_id'] ?? null;
        $status = $payload['status'] ?? null;

        if (!$transactionId || !$status) return null;

        return [
            'transaction_id' => $transactionId,
            'status' => $status,
            'paid_at' => $payload['paid_at'] ?? now()->toIso8601String(),
            'raw' => $payload,
        ];
    }

    public function cancelPayment(Payment $payment): bool
    {
        $payment->update(['status' => 'failed']);
        return true;
    }

    public function refundPayment(Payment $payment, float $amount, string $reason): array
    {
        return [
            'refund_id' => 'REF-DEV-' . strtoupper(uniqid()),
            'amount' => $amount,
            'status' => 'refunded',
            'reason' => $reason,
        ];
    }

    public function getProviderName(): string
    {
        return 'development';
    }

    public function isAvailable(): bool
    {
        return true;
    }
}
