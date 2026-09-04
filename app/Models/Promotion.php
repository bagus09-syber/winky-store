<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'discount_type',
        'discount_value',
        'start_at',
        'end_at',
        'status',
        'priority',
    ];

    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
        'type' => 'string',
        'discount_type' => 'string',
        'discount_value' => 'decimal',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'status' => 'string',
        'priority' => 'integer',
    ];

    public function promotionProducts(): HasMany
    {
        return $this->hasMany(PromotionProduct::class);
    }

    public function promotionCategories(): HasMany
    {
        return $this->hasMany(PromotionCategory::class);
    }
}