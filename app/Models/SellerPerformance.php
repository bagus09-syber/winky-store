<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'total_orders', 'completed_orders', 'cancelled_orders',
        'response_rate', 'on_time_shipping_rate', 'average_rating',
        'return_rate', 'seller_score', 'seller_level', 'last_calculated_at',
    ];

    protected $casts = [
        'total_orders' => 'integer',
        'completed_orders' => 'integer',
        'cancelled_orders' => 'integer',
        'response_rate' => 'decimal:2',
        'on_time_shipping_rate' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'return_rate' => 'decimal:2',
        'seller_score' => 'decimal:2',
        'last_calculated_at' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function getLevelLabelAttribute(): string
    {
        return match ($this->seller_level) {
            'bronze' => '🥉 Bronze Seller',
            'silver' => '🥈 Silver Seller',
            'gold' => '🥇 Gold Seller',
            'platinum' => '💎 Platinum Seller',
            'diamond' => '👑 Diamond Seller',
            default => ucfirst($this->seller_level) . ' Seller',
        };
    }

    public function getLevelColorAttribute(): string
    {
        return match ($this->seller_level) {
            'bronze' => '#cd7f32',
            'silver' => '#c0c0c0',
            'gold' => '#ffd700',
            'platinum' => '#00e5ff',
            'diamond' => '#e040fb',
            default => '#666',
        };
    }

    public static function calculateLevel(float $score): string
    {
        return match (true) {
            $score >= 90 => 'diamond',
            $score >= 75 => 'platinum',
            $score >= 60 => 'gold',
            $score >= 40 => 'silver',
            default => 'bronze',
        };
    }
}
