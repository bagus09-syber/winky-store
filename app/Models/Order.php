<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'store_id', 'address_id',
        'recipient_name', 'recipient_phone', 'shipping_address',
        'subtotal', 'shipping_cost', 'shipping_courier', 'shipping_service',
        'shipping_estimated_days', 'tracking_number', 'shipped_at', 'delivered_at',
        'discount', 'voucher_code', 'voucher_discount', 'total', 'status', 'notes',
        'seller_courier', 'seller_service', 'seller_tracking_number', 'seller_shipped_at',
        'seller_subtotal', 'commission_amount', 'seller_earning', 'seller_status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'shipping_estimated_days' => 'integer',
        'discount' => 'decimal:2',
        'voucher_discount' => 'decimal:2',
        'total' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'seller_shipped_at' => 'datetime',
        'seller_subtotal' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'seller_earning' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latest();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function returnRequest(): HasOne
    {
        return $this->hasOne(ReturnRequest::class);
    }

    public function canBeReviewed(): bool
    {
        return in_array($this->status, ['delivered', 'completed']);
    }

    public function canRequestReturn(): bool
    {
        return in_array($this->status, ['delivered', 'completed'])
            && !$this->returnRequest;
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = self::where('order_number', 'like', "WKY-{$date}-%")
            ->orderByDesc('order_number')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "WKY-{$date}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pesanan Dibuat',
            'awaiting_payment' => 'Menunggu Pembayaran',
            'paid' => 'Pembayaran Diterima',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Diterima',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'awaiting_payment' => 'orange',
            'paid' => 'cyan',
            'processing' => 'blue',
            'shipped' => 'purple',
            'delivered' => 'green',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'awaiting_payment' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
            'paid' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'processing' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            'shipped' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0',
            'delivered' => 'M5 13l4 4L19 7',
            'completed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'cancelled' => 'M6 18L18 6M6 6l12 12',
            default => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        };
    }

    public static function getValidTransitions(): array
    {
        return [
            'pending' => ['awaiting_payment', 'cancelled'],
            'awaiting_payment' => ['paid', 'cancelled'],
            'paid' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ];
    }

    public function canTransitionTo(string $status): bool
    {
        $valid = self::getValidTransitions();
        return in_array($status, $valid[$this->status] ?? []);
    }

    public function transitionTo(string $status): bool
    {
        if (!$this->canTransitionTo($status)) return false;

        $this->update(['status' => $status]);
        return true;
    }

    public function needsPayment(): bool
    {
        return in_array($this->status, ['pending', 'awaiting_payment'])
            && (!$this->payment || $this->payment->status !== 'paid');
    }

    public function isPaid(): bool
    {
        return $this->payment && $this->payment->status === 'paid';
    }

    public function hasTracking(): bool
    {
        return !empty($this->tracking_number);
    }

    public function getEstimatedDeliveryAttribute(): ?string
    {
        if (!$this->shipping_estimated_days) return null;
        $date = $this->created_at ? $this->created_at->addDays($this->shipping_estimated_days) : now()->addDays($this->shipping_estimated_days);
        return $date->format('d M Y');
    }

    public function getShippingLabelAttribute(): string
    {
        if (!$this->shipping_courier) return '-';
        $label = strtoupper($this->shipping_courier);
        if ($this->shipping_service) {
            $label .= ' ' . strtoupper($this->shipping_service);
        }
        return $label;
    }

    public function getTotalWeightAttribute(): int
    {
        return $this->items->sum(function ($item) {
            $weight = $item->product ? $item->product->weight : 500;
            return $weight * $item->quantity;
        });
    }

    public function getTimelineStepsAttribute(): array
    {
        $steps = [
            'pending' => [
                'label' => 'Pesanan Dibuat',
                'description' => 'Pesanan berhasil dibuat',
            ],
            'awaiting_payment' => [
                'label' => 'Menunggu Pembayaran',
                'description' => 'Menunggu pembayaran dari Anda',
            ],
            'paid' => [
                'label' => 'Pembayaran Diterima',
                'description' => 'Pembayaran telah dikonfirmasi',
            ],
            'processing' => [
                'label' => 'Pesanan Diproses',
                'description' => 'Pesanan sedang disiapkan',
            ],
            'shipped' => [
                'label' => 'Pesanan Dikirim',
                'description' => $this->shipping_courier
                    ? 'Dikirim via ' . strtoupper($this->shipping_courier) . ($this->tracking_number ? ' - Resi: ' . $this->tracking_number : '')
                    : 'Pesanan sedang dalam perjalanan',
            ],
            'delivered' => [
                'label' => 'Pesanan Sampai',
                'description' => 'Pesanan telah sampai',
            ],
            'completed' => [
                'label' => 'Pesanan Selesai',
                'description' => 'Pesanan telah selesai',
            ],
        ];

        return $steps;
    }
}
