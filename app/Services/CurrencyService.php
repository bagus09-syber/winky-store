<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CurrencyService
{
    private array $currencies = [
        'IDR' => ['symbol' => 'Rp', 'name' => 'Indonesian Rupiah', 'decimals' => 0, 'position' => 'before'],
        'USD' => ['symbol' => '$', 'name' => 'US Dollar', 'decimals' => 2, 'position' => 'before'],
        'SGD' => ['symbol' => 'S$', 'name' => 'Singapore Dollar', 'decimals' => 2, 'position' => 'before'],
        'MYR' => ['symbol' => 'RM', 'name' => 'Malaysian Ringgit', 'decimals' => 2, 'position' => 'before'],
        'EUR' => ['symbol' => '€', 'name' => 'Euro', 'decimals' => 2, 'position' => 'before'],
    ];

    private array $exchangeRates = [
        'IDR' => 1,
        'USD' => 0.000062,
        'SGD' => 0.000084,
        'MYR' => 0.00029,
        'EUR' => 0.000057,
    ];

    public function getEnabledCurrencies(): array
    {
        return Cache::remember('currency:enabled', 3600, function () {
            $enabled = Config::get('currency.enabled', ['IDR', 'USD']);
            return array_filter($this->currencies, fn($k) => in_array($k, $enabled), ARRAY_FILTER_USE_KEY);
        });
    }

    public function getCurrencyInfo(string $code): ?array
    {
        return $this->currencies[$code] ?? null;
    }

    public function format(float $amount, string $currency = 'IDR'): string
    {
        $info = $this->getCurrencyInfo($currency);
        if (!$info) return number_format($amount, 0, ',', '.');

        $formatted = number_format($amount, $info['decimals'], '.', ',');

        if ($info['position'] === 'before') {
            return $info['symbol'] . ' ' . $formatted;
        }

        return $formatted . ' ' . $info['symbol'];
    }

    public function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) return $amount;

        $fromRate = $this->exchangeRates[$from] ?? null;
        $toRate = $this->exchangeRates[$to] ?? null;

        if (!$fromRate || !$toRate) return $amount;

        $inBase = $amount / $fromRate;
        return $inBase * $toRate;
    }

    public function convertAndFormat(float $amount, string $from, string $to): string
    {
        $converted = $this->convert($amount, $from, $to);
        return $this->format($converted, $to);
    }

    public function getExchangeRate(string $from, string $to): float
    {
        if ($from === $to) return 1;

        $fromRate = $this->exchangeRates[$from] ?? null;
        $toRate = $this->exchangeRates[$to] ?? null;

        if (!$fromRate || !$toRate) return 1;

        return $toRate / $fromRate;
    }

    public function getBaseCurrency(): string
    {
        return Config::get('currency.base', 'IDR');
    }
}
