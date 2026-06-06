<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total'    => 'decimal:2',
        'paid_at'  => 'datetime',
    ];

    public const STATUS_PENDING   = 'pending';
    public const STATUS_PAID      = 'paid';
    public const STATUS_SHIPPED   = 'shipped';
    public const STATUS_CANCELLED = 'cancelled';

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName(): string
    {
        return 'order_no';
    }

    /** Benzersiz sipariş numarası: DMB-XXXXXX */
    public static function generateOrderNo(): string
    {
        do {
            $no = 'DMB-' . strtoupper(Str::random(8));
        } while (static::where('order_no', $no)->exists());

        return $no;
    }

    public function getTotalFormattedAttribute(): string
    {
        return Part::formatMoney($this->total, $this->currency);
    }
}
