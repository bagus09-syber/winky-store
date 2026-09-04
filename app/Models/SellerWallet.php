<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'available_balance', 'pending_balance', 'escrow_balance',
        'total_earned', 'total_withdrawn',
    ];

    protected $casts = [
        'available_balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'escrow_balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function hasSufficientBalance(float $amount): bool
    {
        return $this->available_balance >= $amount;
    }

    public function addPendingBalance(float $amount): void
    {
        $this->increment('pending_balance', $amount);
    }

    public function movePendingToAvailable(float $amount): void
    {
        $this->decrement('pending_balance', $amount);
        $this->increment('available_balance', $amount);
        $this->increment('total_earned', $amount);
    }

    public function addToEscrow(float $amount): void
    {
        $this->decrement('pending_balance', $amount);
        $this->increment('escrow_balance', $amount);
    }

    public function releaseFromEscrow(float $amount): void
    {
        $this->decrement('escrow_balance', $amount);
        $this->increment('available_balance', $amount);
        $this->increment('total_earned', $amount);
    }

    public function refundEscrow(float $amount): void
    {
        $this->decrement('escrow_balance', $amount);
    }

    public function deductAvailable(float $amount): void
    {
        $this->decrement('available_balance', $amount);
        $this->increment('total_withdrawn', $amount);
    }

    public function refundPending(float $amount): void
    {
        if ($this->pending_balance >= $amount) {
            $this->decrement('pending_balance', $amount);
        }
    }
}
