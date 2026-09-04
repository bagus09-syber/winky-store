<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_id', 'reason', 'description', 'status', 'admin_notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'requested' => 'Requested',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'received' => 'Received',
            'refunded' => 'Refunded',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'requested' => 'yellow',
            'approved' => 'blue',
            'rejected' => 'red',
            'received' => 'cyan',
            'refunded' => 'green',
            default => 'gray',
        };
    }
}
