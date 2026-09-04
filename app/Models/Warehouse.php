<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'contact_name', 'phone', 'province', 'city',
        'district', 'postal_code', 'full_address', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getActive(): ?self
    {
        return static::active()->first();
    }

    public function getFullLocationAttribute(): string
    {
        $parts = array_filter([$this->district, $this->city, $this->province]);
        return implode(', ', $parts);
    }
}
