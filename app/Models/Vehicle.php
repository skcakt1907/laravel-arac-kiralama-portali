<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Sadece yayında olan araçlar */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /** "2024 · 1.200 km · V12" gibi tek satır özet */
    public function getSpecsLineAttribute(): string
    {
        $parts = array_filter([
            $this->year,
            $this->mileage_km ? number_format($this->mileage_km, 0, ',', '.') . ' km' : null,
            $this->engine,
        ]);

        return implode(' · ', $parts);
    }
}
