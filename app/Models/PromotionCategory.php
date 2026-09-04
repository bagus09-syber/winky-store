<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'category_id',
    ];

    protected $casts = [
        'promotion_id' => 'integer',
        'category_id' => 'integer',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}