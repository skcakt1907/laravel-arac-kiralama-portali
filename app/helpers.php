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

if (! function_exists('embed_html')) {
    /**
     * Google Harita gibi gömülü iframe kodunu güvenli basar.
     * Yalnızca <iframe> etiketine izin verir; <script>, on*= olay öznitelikleri
     * ve javascript: protokolünü temizler. (map_embed saklı XSS koruması)
     */
    function embed_html(?string $html): string
    {
        if (! $html) {
            return '';
        }

        $clean = strip_tags($html, '<iframe>');                                  // sadece iframe
        $clean = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean); // on*= kaldır
        $clean = preg_replace('/javascript:/i', '', $clean);                     // javascript: kaldır

        return $clean;
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
