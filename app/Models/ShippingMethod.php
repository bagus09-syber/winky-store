<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'courier', 'service', 'description', 'estimated_days',
        'base_price', 'price_per_kg', 'min_weight', 'max_weight', 'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'estimated_days' => 'integer',
        'min_weight' => 'integer',
        'max_weight' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function calculatePrice(int $weightGrams): int
    {
        $weightKg = max(1, ceil($weightGrams / 1000));
        return (int) ($this->base_price + ($this->price_per_kg * $weightKg));
    }

    public function getEstimatedDateAttribute(): string
    {
        return now()->addDays($this->estimated_days)->format('d M Y');
    }
}
