<?php

namespace App\Services\Shipping;

use App\Models\ShippingMethod;

class DevelopmentShippingProvider implements ShippingProviderInterface
{
    public function getRates(string $originCity, string $destinationCity, int $weightGrams): array
    {
        $methods = ShippingMethod::active()
            ->where('min_weight', '<=', $weightGrams)
            ->where('max_weight', '>=', $weightGrams)
            ->get();

        $rates = [];
        foreach ($methods as $method) {
            $rates[] = [
                'courier' => $method->courier,
                'service' => $method->service,
                'description' => $method->description ?? $method->courier . ' ' . $method->service,
                'price' => $method->calculatePrice($weightGrams),
                'estimated_days' => $method->estimated_days,
                'estimated_date' => now()->addDays($method->estimated_days)->format('d M Y'),
            ];
        }

        return $rates;
    }

    public function trackShipment(string $trackingNumber): array
    {
        return [
            'status' => 'development',
            'message' => 'Tracking detail dari kurir belum terhubung. Mode development aktif.',
            'history' => [],
        ];
    }

    public function getName(): string
    {
        return 'Development';
    }

    public function isAvailable(): bool
    {
        return true;
    }
}
