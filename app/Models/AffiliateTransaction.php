<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'amount',
        'type',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'affiliate_id' => 'integer',
        'amount' => 'decimal',
        'type' => 'string',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }
}