<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10,
                'minimum_order' => 100000,
                'maximum_discount' => 50000,
                'starts_at' => now()->subDays(30),
                'expires_at' => now()->addDays(90),
                'usage_limit' => 100,
                'used_count' => 12,
                'is_active' => true,
            ],
            [
                'code' => 'FLAT20K',
                'type' => 'fixed',
                'value' => 20000,
                'minimum_order' => 200000,
                'maximum_discount' => null,
                'starts_at' => now()->subDays(15),
                'expires_at' => now()->addDays(60),
                'usage_limit' => 50,
                'used_count' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'FLASH50',
                'type' => 'percentage',
                'value' => 50,
                'minimum_order' => 500000,
                'maximum_discount' => 250000,
                'starts_at' => now()->subDays(5),
                'expires_at' => now()->addDays(7),
                'usage_limit' => 20,
                'used_count' => 18,
                'is_active' => true,
            ],
            [
                'code' => 'WINKYSTORE',
                'type' => 'fixed',
                'value' => 100000,
                'minimum_order' => 1000000,
                'maximum_discount' => null,
                'starts_at' => now()->subDays(60),
                'expires_at' => now()->addDays(180),
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}
