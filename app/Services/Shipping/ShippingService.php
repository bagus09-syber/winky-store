<?php

namespace App\Services\Shipping;

use App\Models\ShippingMethod;
use App\Models\Warehouse;

class ShippingService
{
    protected ShippingProviderInterface $provider;

    public function __construct()
    {
        $driver = config('shipping.driver', 'development');

        $this->provider = match ($driver) {
            default => new DevelopmentShippingProvider(),
        };
    }

    public function getProvider(): ShippingProviderInterface
    {
        return $this->provider;
    }

    public function getRatesForOrder(int $totalWeightGrams): array
    {
        $warehouse = Warehouse::getActive();
        if (!$warehouse) {
            return [];
        }

        $originCity = $warehouse->city;
        $destinationCity = null;

        return $this->provider->getRates($originCity, $destinationCity ?? $originCity, $totalWeightGrams);
    }

    public function calculateShippingCost(int $weightGrams, string $courier, string $service): int
    {
        $rates = $this->getRatesForOrder($weightGrams);

        foreach ($rates as $rate) {
            if (strtolower($rate['courier']) === strtolower($courier)
                && strtolower($rate['service']) === strtolower($service)) {
                return $rate['price'];
            }
        }

        return 0;
    }

    public function getEstimatedDays(string $courier, string $service): ?int
    {
        $method = ShippingMethod::where('courier', $courier)
            ->where('service', $service)
            ->active()
            ->first();

        return $method?->estimated_days;
    }
}
