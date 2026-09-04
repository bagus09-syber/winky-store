<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateConversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_link_id',
        'order_id',
        'commission',
        'status',
        'confirmed_at',
    ];

    protected $casts = [
        'affiliate_link_id' => 'integer',
        'order_id' => 'integer',
        'commission' => 'decimal',
        'status' => 'string',
        'confirmed_at' => 'datetime',
    ];

    public function affiliateLink(): BelongsTo
    {
        return $this->belongsTo(AffiliateLink::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}