<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getCommissionType(): string
    {
        return static::get('commission_type', 'percentage');
    }

    public static function getCommissionValue(): float
    {
        return (float) static::get('commission_value', '5');
    }

    public static function getMinWithdrawal(): float
    {
        return (float) static::get('min_withdrawal', '50000');
    }

    public static function getLowStockThreshold(): int
    {
        return (int) static::get('low_stock_threshold', '5');
    }

    public static function calculateCommission(float $amount): float
    {
        $type = static::getCommissionType();
        $value = static::getCommissionValue();

        if ($type === 'percentage') {
            return round($amount * $value / 100, 2);
        }

        return min($value, $amount);
    }
}
