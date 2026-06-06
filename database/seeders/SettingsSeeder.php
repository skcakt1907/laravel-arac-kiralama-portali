<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // çoklu dilli içerik (lang dosyalarındaki mevcut metinlerden)
            'about_tr' => __('site.about_text', [], 'tr'),
            'about_en' => __('site.about_text', [], 'en'),
            'about_ar' => __('site.about_text', [], 'ar'),

            'address_tr' => 'Sheikh Zayed Road, Dubai, UAE',
            'address_en' => 'Sheikh Zayed Road, Dubai, UAE',
            'address_ar' => 'شارع الشيخ زايد، دبي، الإمارات',

            'hours_tr' => 'Pzt – Cmt · 09:00 – 21:00',
            'hours_en' => 'Mon – Sat · 09:00 – 21:00',
            'hours_ar' => 'الإثنين – السبت · 09:00 – 21:00',

            // iletişim (tek değer)
            'phone'     => '+971 — — — — —',
            'email'     => 'info@ornek-kiralama.com',
            'whatsapp'  => '',
            'instagram' => '',
            'facebook'  => '',
            'twitter'   => '',
            'map_embed' => '',

            // istatistikler
            'stat_delivered' => '250+',
            'stat_brands'    => '30+',
            'stat_years'     => '15',
        ];

        foreach ($defaults as $key => $value) {
            // sadece yoksa ekle (mevcut admin değişikliklerini ezmesin)
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
