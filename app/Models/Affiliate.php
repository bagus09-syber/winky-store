<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'commission_rate',
        'total_clicks',
        'total_sales',
        'total_earnings',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'status' => 'string',
        'commission_rate' => 'decimal',
        'total_clicks' => 'integer',
        'total_sales' => 'integer',
        'total_earnings' => 'decimal',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function affiliateLinks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AffiliateLink::class);
    }

    public function affiliateClickCounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AffiliateClick::class);
    }

    public function affiliateConversions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AffiliateConversion::class);
    }

    public function affiliateTransactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AffiliateTransaction::class);
    }
}