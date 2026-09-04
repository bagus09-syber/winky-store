<?php

namespace App\Services\Payment\Providers;

use App\Models\Order;
use App\Models\Payment;

interface PaymentProviderInterface
{
    public function createPayment(Order $order, string $method): array;

    public function getPaymentStatus(Payment $payment): string;

    public function verifyWebhook(array $payload, array $headers): bool;

    public function processWebhook(array $payload): ?array;

    public function cancelPayment(Payment $payment): bool;

    public function refundPayment(Payment $payment, float $amount, string $reason): array;

    public function getProviderName(): string;

    public function isAvailable(): bool;
}
