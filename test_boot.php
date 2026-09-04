<?php
require 'vendor/autoload.php';

// Test all the key classes
$classes = [
    'App\Models\User',
    'App\Models\Product', 
    'App\Http\Controllers\Admin\AdminController',
    'App\Http\Controllers\ProductController',
    'App\Services\LoyaltyService',
    'App\Services\ReferralService',
    'App\Services\AffiliateService',
    'App\Services\PromotionService',
    'App\Services\PersonalizationService',
    'App\Services\AbandonedCartService',
    'App\Services\MarketingCampaignService',
];

foreach ($classes as $class) {
    echo $class . ': ' . (class_exists($class) ? 'YES' : 'NO') . PHP_EOL;
}