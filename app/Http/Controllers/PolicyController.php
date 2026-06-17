<?php

namespace App\Http\Controllers;

class PolicyController extends Controller
{
    /** Geçerli politika/sözleşme sayfaları (lang dosyalarındaki policy.php anahtarlarıyla aynı) */
    public const SLUGS = [
        'gizlilik',
        'mesafeli-satis',
        'iptal-iade',
        'teslimat',
        'cerez-politikasi',
        'kullanim-kosullari',
    ];

    public function show(string $slug)
    {
        abort_unless(in_array($slug, self::SLUGS, true), 404);

        $doc = __('policy.' . $slug);   // ['title' => ..., 'sections' => [...]]

        return view('pages.policy', compact('slug', 'doc'));
    }
}
