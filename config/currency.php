<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base Currency
    |--------------------------------------------------------------------------
    |
    | The base currency used for all product prices in the database.
    | All prices are stored in this currency.
    |
    */

    'base' => env('CURRENCY_BASE', 'IDR'),

    /*
    |--------------------------------------------------------------------------
    | Enabled Currencies
    |--------------------------------------------------------------------------
    |
    | List of currency codes that customers can choose from.
    |
    */

    'enabled' => ['IDR', 'USD', 'SGD', 'MYR', 'EUR'],

    /*
    |--------------------------------------------------------------------------
    | Exchange Rates (Base: IDR)
    |--------------------------------------------------------------------------
    |
    | Exchange rates relative to the base currency.
    | These are simplified rates for development.
    | In production, use an exchange rate API.
    |
    */

    'rates' => [
        'IDR' => 1,
        'USD' => 0.000062,
        'SGD' => 0.000084,
        'MYR' => 0.00029,
        'EUR' => 0.000057,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache TTL (seconds)
    |--------------------------------------------------------------------------
    |
    | How long to cache exchange rates.
    |
    */

    'cache_ttl' => 3600,

];
