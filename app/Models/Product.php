<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'brand_id', 'store_id', 'name', 'slug', 'sku',
        'short_description', 'description', 'price', 'sale_price', 'stock',
        'weight', 'length', 'width', 'height',
        'image', 'is_featured', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'weight' => 'integer',
        'length' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->latest();
    }

    public function allReviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getRatingDistributionAttribute(): array
    {
        $counts = $this->reviews()
            ->selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $total = array_sum($counts);
        $dist = [];
        for ($i = 5; $i >= 1; $i--) {
            $c = $counts[$i] ?? 0;
            $dist[$i] = [
                'count' => $c,
                'percent' => $total > 0 ? round(($c / $total) * 100) : 0,
            ];
        }
        return $dist;
    }

    public function canBeReviewedBy(?User $user): bool
    {
        if (!$user) return false;
        return Order::where('user_id', $user->id)
            ->whereHas('items', fn($q) => $q->where('product_id', $this->id))
            ->whereIn('status', ['delivered', 'completed'])
            ->exists();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getEffectivePrice(): string
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercent(): ?int
    {
        if (!$this->sale_price || $this->sale_price >= $this->price) {
            return null;
        }
        return (int) round((1 - $this->sale_price / $this->price) * 100);
    }

    public function getWeightDisplayAttribute(): string
    {
        if ($this->weight >= 1000) {
            return number_format($this->weight / 1000, 1) . ' kg';
        }
        return $this->weight . ' g';
    }

    public function getDimensionsDisplayAttribute(): ?string
    {
        if (!$this->length && !$this->width && !$this->height) {
            return null;
        }
        return implode(' × ', array_filter([
            $this->length . ' cm',
            $this->width . ' cm',
            $this->height . ' cm',
        ]));
    }
}
