<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /** Tek bir site ayarını getirir */
    function setting(string $key, $default = null)
    {
        $val = Setting::get($key, null);

        return ($val === null || $val === '') ? $default : $val;
    }
}

if (! function_exists('setting_l')) {
    /**
     * Çoklu dilli ayar: setting_l('about') -> about_tr / about_en / about_ar
     * Aktif dil boşsa TR'ye, o da boşsa $default'a düşer.
     */
    function setting_l(string $base, $default = null)
    {
        $locale = app()->getLocale();

        return setting("{$base}_{$locale}", setting("{$base}_tr", $default));
    }
}
