<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    /** Aktif dile göre alan; boşsa TR'ye düşer */
    public function l(string $field): ?string
    {
        $locale = app()->getLocale();

        return $this->{"{$field}_{$locale}"} ?: $this->{"{$field}_tr"};
    }

    public function getTitleAttribute(): string
    {
        return (string) ($this->l('title') ?: 'Başlıksız');
    }

    public function getExcerptAttribute(): ?string
    {
        return $this->l('excerpt');
    }

    public function getBodyAttribute(): ?string
    {
        return $this->l('body');
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
}
