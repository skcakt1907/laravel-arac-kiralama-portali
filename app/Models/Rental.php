<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'daily_price'  => 'decimal:2',
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getHasImageAttribute(): bool
    {
        return ! empty($this->cover_image);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->cover_image)) {
            return null;
        }

        return str_starts_with($this->cover_image, 'http')
            ? $this->cover_image
            : asset('storage/' . $this->cover_image);
    }

    public function getDailyPriceFormattedAttribute(): string
    {
        return Part::formatMoney($this->daily_price, $this->currency);
    }
}
