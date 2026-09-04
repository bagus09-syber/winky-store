<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::firstOrCreate(
            ['is_active' => true],
            [
                'name' => 'WINKY STORE Warehouse',
                'contact_name' => 'WINKY STORE',
                'phone' => '081234567890',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'postal_code' => '12190',
                'full_address' => 'Jl. Senopati No. 10, RT 01/RW 02, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12190',
                'is_active' => true,
            ]
        );

        $methods = [
            ['courier' => 'JNE', 'service' => 'OKE', 'description' => 'JNE OKE - Reguler Ekonomis', 'estimated_days' => 5, 'base_price' => 8000, 'price_per_kg' => 4000, 'min_weight' => 0, 'max_weight' => 30000],
            ['courier' => 'JNE', 'service' => 'REG', 'description' => 'JNE REG - Reguler', 'estimated_days' => 3, 'base_price' => 10000, 'price_per_kg' => 6000, 'min_weight' => 0, 'max_weight' => 30000],
            ['courier' => 'JNE', 'service' => 'YES', 'description' => 'JNE YES - Yes Express', 'estimated_days' => 1, 'base_price' => 25000, 'price_per_kg' => 12000, 'min_weight' => 0, 'max_weight' => 30000],
            ['courier' => 'J&T Express', 'service' => 'EZ', 'description' => 'J&T EZ - Ekonomi', 'estimated_days' => 4, 'base_price' => 8000, 'price_per_kg' => 4000, 'min_weight' => 0, 'max_weight' => 30000],
            ['courier' => 'J&T Express', 'service' => 'REG', 'description' => 'J&T REG - Reguler', 'estimated_days' => 3, 'base_price' => 10000, 'price_per_kg' => 5000, 'min_weight' => 0, 'max_weight' => 30000],
            ['courier' => 'SiCepat', 'service' => 'BEST', 'description' => 'SiCepat BEST - Banyak Diskon', 'estimated_days' => 4, 'base_price' => 7000, 'price_per_kg' => 4000, 'min_weight' => 0, 'max_weight' => 30000],
            ['courier' => 'SiCepat', 'service' => 'REG', 'description' => 'SiCepat REG - Reguler', 'estimated_days' => 3, 'base_price' => 10000, 'price_per_kg' => 5000, 'min_weight' => 0, 'max_weight' => 30000],
            ['courier' => 'SiCepat', 'service' => 'HALU', 'description' => 'SiCepat HALU - Halu Express', 'estimated_days' => 1, 'base_price' => 20000, 'price_per_kg' => 10000, 'min_weight' => 0, 'max_weight' => 30000],
            ['courier' => 'AnterAja', 'service' => 'REG', 'description' => 'AnterAja Regular', 'estimated_days' => 3, 'base_price' => 10000, 'price_per_kg' => 5000, 'min_weight' => 0, 'max_weight' => 30000],
            ['courier' => 'AnterAja', 'service' => 'SAME', 'description' => 'AnterAja Same Day', 'estimated_days' => 1, 'base_price' => 25000, 'price_per_kg' => 12000, 'min_weight' => 0, 'max_weight' => 10000],
            ['courier' => 'Ninja Xpress', 'service' => 'REG', 'description' => 'Ninja Xpress Regular', 'estimated_days' => 3, 'base_price' => 10000, 'price_per_kg' => 5000, 'min_weight' => 0, 'max_weight' => 30000],
            ['courier' => 'POS Indonesia', 'service' => 'REG', 'description' => 'POS Reguler', 'estimated_days' => 4, 'base_price' => 9000, 'price_per_kg' => 4000, 'min_weight' => 0, 'max_weight' => 30000],
            ['courier' => 'POS Indonesia', 'service' => 'KILAT', 'description' => 'POS Kilat Khusus', 'estimated_days' => 2, 'base_price' => 15000, 'price_per_kg' => 8000, 'min_weight' => 0, 'max_weight' => 30000],
        ];

        foreach ($methods as $method) {
            ShippingMethod::firstOrCreate(
                ['courier' => $method['courier'], 'service' => $method['service']],
                $method
            );
        }
    }
}
