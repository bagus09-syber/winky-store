<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentWebhook;
use App\Services\Payment\Providers\PaymentProviderInterface;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    protected PaymentProviderInterface $provider;

    public function __construct()
    {
        $driver = config('payment.driver', 'development');
        $this->provider = $this->resolveProvider($driver);
    }

    protected function resolveProvider(string $driver): PaymentProviderInterface
    {
        return match ($driver) {
            'midtrans' => new Providers\MidtransPaymentProvider(),
            default => new Providers\DevelopmentPaymentProvider(),
        };
    }

    public function createPayment(Order $order, string $method): array
    {
        return $this->provider->createPayment($order, $method);
    }

    public function confirmPayment(string $transactionId, array $data): bool
    {
        $existingWebhook = PaymentWebhook::where('transaction_id', $transactionId)
            ->where('status', 'processed')
            ->first();

        if ($existingWebhook) {
            return true;
        }

        $payment = Payment::where('transaction_id', $transactionId)->first();
        if (!$payment) return false;

        $order = $payment->order;
        if (!$order) return false;

        if ($order->isPaid()) return true;

        try {
            DB::beginTransaction();

            $payment->update([
                'status' => $data['status'],
                'raw_response' => array_merge($payment->raw_response ?? [], $data['raw'] ?? []),
            ]);

            if ($data['status'] === 'paid') {
                $order->update([
                    'status' => 'paid',
                ]);

                $this->createWebhookLog($payment->transaction_id, 'payment.paid', $data, 'processed');

                if ($order->store_id) {
                    $wallet = \App\Models\Store::getOrCreateWallet(\App\Models\Store::find($order->store_id));
                    if ($wallet && $order->seller_earning > 0) {
                        \App\Models\SellerTransaction::create([
                            'store_id' => $order->store_id,
                            'order_id' => $order->id,
                            'type' => 'sale',
                            'amount' => $order->seller_earning,
                            'balance_after' => $wallet->fresh()->pending_balance,
                            'description' => "Pembayaran pesanan #{$order->order_number} diterima",
                        ]);
                    }
                }

                \App\Models\InAppNotification::create([
                    'user_id' => $order->user_id,
                    'type' => 'payment_success',
                    'data' => [
                        'title' => 'Pembayaran Berhasil',
                        'message' => "Pembayaran untuk pesanan #{$order->order_number} telah diterima.",
                        'url' => route('orders.show', $order->order_number),
                    ],
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->createWebhookLog($transactionId, 'payment.error', $data, 'failed', $e->getMessage());
            return false;
        }
    }

    public function cancelExpiredPayments(): int
    {
        $expired = Payment::where('status', 'pending')
            ->where('expiry_time', '<', now())
            ->get();

        $count = 0;
        foreach ($expired as $payment) {
            $this->provider->cancelPayment($payment);
            $payment->order->update(['status' => 'cancelled']);
            $count++;
        }

        return $count;
    }

    public function processWebhook(array $payload, array $headers = []): bool
    {
        if (!$this->provider->verifyWebhook($payload, $headers)) {
            $this->createWebhookLog('unknown', 'webhook.signature_failed', $payload, 'failed', 'Signature verification failed');
            return false;
        }

        $result = $this->provider->processWebhook($payload);
        if (!$result) return false;

        $existing = PaymentWebhook::where('transaction_id', $result['transaction_id'])
            ->where('status', 'processed')
            ->first();

        if ($existing) {
            $this->createWebhookLog($result['transaction_id'], 'webhook.duplicate', $payload, 'ignored');
            return true;
        }

        return $this->confirmPayment($result['transaction_id'], $result);
    }

    protected function createWebhookLog(string $transactionId, string $eventType, array $payload, string $status, ?string $reason = null): void
    {
        // Filter sensitive data from payload before saving to database/logs
        $filteredPayload = $this->filterSensitiveWebhookPayload($payload);

        PaymentWebhook::create([
            'provider' => $this->provider->getProviderName(),
            'event_type' => $eventType,
            'transaction_id' => $transactionId,
            'payload' => $filteredPayload,
            'status' => $status,
            'failure_reason' => $reason,
            'processed_at' => $status === 'processed' ? now() : null,
        ]);
    }

    /**
     * Filter sensitive values from webhook payload.
     *
     * @param array $payload
     *
     * @return array
     */
    protected function filterSensitiveWebhookPayload(array $payload): array
    {
        $sensitiveKeys = [
            'card_number',
            'cvv',
            'expiry',
            'expire',
            'ccv',
            'billing_address',
            'full_name',
            'phone',
            'email',
            'amount',
            'secret',
            'key',
            'api_key',
            'api_secret',
        ];

        return $this->maskSensitiveValues($payload, $sensitiveKeys);
    }

    /**
     * Mask sensitive values in array based on sensitive keys list.
     *
     * @param array $data
     * @param array $sensitiveKeys
     *
     * @return array
     */
    protected function maskSensitiveValues(array $data, array $sensitiveKeys): array
    {
        foreach ($data as $key => &$value) {
            $lowerKey = strtolower($key);

            foreach ($sensitiveKeys as $sensitiveKey) {
                if (strpos($lowerKey, $sensitiveKey) !== false) {
                    if (is_string($value)) {
                        $value = '[REDACTED]';
                    } elseif (is_array($value)) {
                        $value = array_map(function ($item) {
                            return is_string($item) ? '[REDACTED]' : $item;
                        }, $value);
                    } elseif (is_object($value)) {
                        $value = '[REDACTED]';
                    }
                    break;
                }
            }

            if (is_array($value)) {
                $value = $this->maskSensitiveValues($value, $sensitiveKeys);
            }
        }

        return $data;
    }

    public function getProvider(): PaymentProviderInterface
    {
        return $this->provider;
    }
}
