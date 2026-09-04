<?php

namespace App\Services\Shipping;

interface ShippingProviderInterface
{
    public function getRates(string $originCity, string $destinationCity, int $weightGrams): array;

    public function trackShipment(string $trackingNumber): array;

    public function getName(): string;

    public function isAvailable(): bool;
}
