<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleImage extends Model
{
    protected $guarded = ['id'];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** Harici (http) URL ise olduğu gibi, değilse storage'dan döndürür. */
    public function getUrlAttribute(): string
    {
        return str_starts_with((string) $this->path, 'http')
            ? $this->path
            : asset('storage/' . ltrim((string) $this->path, '/'));
    }
}
