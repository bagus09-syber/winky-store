<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'base_rate', 'per_kg_rate',
        'estimated_days_min', 'estimated_days_max', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'base_rate' => 'decimal:2',
        'per_kg_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function countries(): HasMany
    {
        return $this->hasMany(ShippingZoneCountry::class);
    }

    public function isAvailableForCountry(string $countryCode): bool
    {
        return $this->is_active && $this->countries()->where('country_code', $countryCode)->exists();
    }

    public function calculateRate(float $weightKg): float
    {
        return $this->base_rate + ($this->per_kg_rate * $weightKg);
    }

    public static function getZoneForCountry(string $countryCode): ?self
    {
        if ($countryCode === 'ID') {
            return self::where('code', 'domestic')->where('is_active', true)->first();
        }

        return self::where('is_active', true)
            ->whereHas('countries', fn($q) => $q->where('country_code', $countryCode))
            ->first();
    }
}
