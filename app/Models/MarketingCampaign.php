<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'status',
        'audience',
        'scheduled_at',
        'sent_at',
        'content',
    ];

    protected $casts = [
        'name' => 'string',
        'type' => 'string',
        'status' => 'string',
        'audience' => 'text',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'content' => 'text',
    ];

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeRunning($query)
    {
        return $query->where('status', 'running');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}