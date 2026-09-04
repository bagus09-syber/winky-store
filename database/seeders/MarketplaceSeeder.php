<?php

namespace Database\Seeders;

use App\Models\MarketplaceSetting;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@winky.store')->first();

        if ($admin && !$admin->store) {
            $store = Store::createWinkyOfficial($admin);
            Store::getOrCreateWallet($store);
        }

        MarketplaceSetting::set('commission_type', 'percentage');
        MarketplaceSetting::set('commission_value', '5');
        MarketplaceSetting::set('min_withdrawal', '50000');
        MarketplaceSetting::set('low_stock_threshold', '5');
    }
}
