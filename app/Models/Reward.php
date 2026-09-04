<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'cost_points',
        'discount_type',
        'discount_value',
        'max_redemptions',
        'redemptions',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'cost_points' => 'integer',
        'discount_type' => 'string',
        'discount_value' => 'decimal',
        'max_redemptions' => 'integer',
        'redemptions' => 'integer',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function redemptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RewardRedemption::class);
    }
}