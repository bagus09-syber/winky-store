<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id', 'product_id', 'reason', 'description',
        'status', 'admin_notes', 'reviewed_by', 'reviewed_at',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'reviewing' => 'Reviewing',
            'resolved' => 'Resolved',
            'rejected' => 'Rejected',
            default => $this->status,
        };
    }

    public function getReasonLabelAttribute(): string
    {
        return match ($this->reason) {
            'counterfeit' => 'Counterfeit / Palsu',
            'prohibited_item' => 'Prohibited Item',
            'misleading' => 'Misleading Info',
            'scam' => 'Scam / Penipuan',
            'inappropriate' => 'Inappropriate Content',
            'other' => 'Other',
            default => $this->reason,
        };
    }
}
