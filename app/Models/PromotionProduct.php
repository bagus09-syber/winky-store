<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'product_id',
        'discount_value',
    ];

    protected $casts = [
        'promotion_id' => 'integer',
        'product_id' => 'integer',
        'discount_value' => 'decimal',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}