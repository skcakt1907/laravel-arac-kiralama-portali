<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /** Yönetilebilir ayar anahtarları */
    private const KEYS = [
        'about_tr', 'about_en', 'about_ar',
        'address_tr', 'address_en', 'address_ar',
        'hours_tr', 'hours_en', 'hours_ar',
        'phone', 'email', 'whatsapp', 'instagram', 'facebook', 'twitter', 'map_embed',
        'stat_delivered', 'stat_brands', 'stat_years',
    ];

    public function edit()
    {
        $s = Setting::allCached();

        return view('admin.settings.index', ['s' => $s]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'about_tr' => ['nullable', 'string'],
            'about_en' => ['nullable', 'string'],
            'about_ar' => ['nullable', 'string'],
            'address_tr' => ['nullable', 'string', 'max:255'],
            'address_en' => ['nullable', 'string', 'max:255'],
            'address_ar' => ['nullable', 'string', 'max:255'],
            'hours_tr' => ['nullable', 'string', 'max:120'],
            'hours_en' => ['nullable', 'string', 'max:120'],
            'hours_ar' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:60'],
            'email' => ['nullable', 'email', 'max:160'],
            'whatsapp' => ['nullable', 'string', 'max:60'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'map_embed' => ['nullable', 'string', 'max:4000'],
            'stat_delivered' => ['nullable', 'string', 'max:20'],
            'stat_brands' => ['nullable', 'string', 'max:20'],
            'stat_years' => ['nullable', 'string', 'max:20'],
        ]);

        $pairs = [];
        foreach (self::KEYS as $k) {
            $pairs[$k] = $data[$k] ?? '';
        }
        Setting::putMany($pairs);

        return back()->with('ok', 'Site ayarları kaydedildi.');
    }
}
