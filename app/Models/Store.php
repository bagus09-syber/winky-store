<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'slug', 'description', 'logo', 'banner',
        'phone', 'email', 'province', 'city', 'district', 'postal_code',
        'address', 'status', 'is_verified', 'rating', 'total_sales',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'rating' => 'decimal:1',
        'total_sales' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(SellerWallet::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(SellerTransaction::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($store) {
            if (empty($store->slug)) {
                $store->slug = Str::slug($store->name);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getActiveProductsCount(): int
    {
        return $this->products()->where('is_active', true)->count();
    }

    public function getAvgRatingAttribute(): float
    {
        $avg = $this->reviews()
            ->where('is_approved', true)
            ->avg('rating');
        return round($avg ?? 0, 1);
    }

    public function getVerifiedBadgeAttribute(): string
    {
        return $this->is_verified ? '✓ Verified Store' : '';
    }

    public static function getOrCreateWallet(Store $store): SellerWallet
    {
        return $store->wallet()->firstOrCreate(['store_id' => $store->id]);
    }

    public static function createWinkyOfficial(User $user): self
    {
        return static::create([
            'user_id' => $user->id,
            'name' => 'WINKY Official',
            'slug' => 'winky-official',
            'description' => 'Toko resmi WINKY STORE. Menjamin keaslian dan kualitas produk.',
            'phone' => $user->phone,
            'email' => $user->email,
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => '12190',
            'address' => 'Jl. WINKY Store No. 1, Jakarta Selatan',
            'status' => 'active',
            'is_verified' => true,
            'rating' => 5.0,
            'total_sales' => 0,
        ]);
    }
}
