<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Part extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'price'        => 'decimal:2',
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PartCategory::class, 'part_category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    /** "$4.850" gibi biçimli fiyat */
    public function getPriceFormattedAttribute(): string
    {
        return self::formatMoney($this->price, $this->currency);
    }

    public static function formatMoney($amount, string $currency = 'USD'): string
    {
        $symbols = ['USD' => '$', 'EUR' => '€', 'TRY' => '₺', 'AED' => 'AED ', 'GBP' => '£'];
        $symbol  = $symbols[$currency] ?? ($currency . ' ');

        return $symbol . number_format((float) $amount, 0, ',', '.');
    }
}
