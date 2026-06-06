<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    /** Tüm ayarları cache'li key=>value dizisi olarak getir */
    public static function allCached(): array
    {
        return Cache::rememberForever('settings.all', fn () => static::pluck('value', 'key')->all());
    }

    public static function get(string $key, $default = null)
    {
        $all = static::allCached();

        return $all[$key] ?? $default;
    }

    public static function put(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings.all');
    }

    public static function putMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('settings.all');
    }
}
