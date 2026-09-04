<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'query', 'results_count', 'session_id', 'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getPopularSearches(int $limit = 10): array
    {
        return static::where('created_at', '>=', now()->subDays(30))
            ->select('query', \DB::raw('COUNT(*) as search_count'))
            ->groupBy('query')
            ->orderByDesc('search_count')
            ->take($limit)
            ->pluck('search_count', 'query')
            ->toArray();
    }

    public static function getTrendingSearches(int $limit = 10): array
    {
        return static::where('created_at', '>=', now()->subDays(7))
            ->select('query', \DB::raw('COUNT(*) as search_count'))
            ->groupBy('query')
            ->orderByDesc('search_count')
            ->take($limit)
            ->pluck('search_count', 'query')
            ->toArray();
    }
}
