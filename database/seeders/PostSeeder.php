<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'tr' => ['Lüks Otomobil Dünyasında Yeni Dönem', 'Demirbey Holding ile lüks otomotivde yeni bir döneme adım atın.', "Demirbey Holding Luxury Automotive olarak, dünyanın en prestijli markalarını sizlerle buluşturuyoruz. Koleksiyonumuzdaki her araç, titizlikle seçilmiş ve uzman ekibimizce incelenmiştir.\n\nDubai merkezli operasyonlarımızla, ithalattan satışa, kiralamadan yedek parçaya kadar geniş bir hizmet yelpazesi sunuyoruz."],
                'en' => ['A New Era in the Luxury Car World', 'Step into a new era of luxury automotive with Demirbey Holding.', "At Demirbey Holding Luxury Automotive, we bring the world's most prestigious marques to you. Every car in our collection is meticulously selected and inspected by our experts.\n\nFrom our Dubai-based operations, we offer a wide range of services from import to sale, rental to spare parts."],
                'ar' => ['عصر جديد في عالم السيارات الفاخرة', 'ادخل عصراً جديداً من السيارات الفاخرة مع دميربي القابضة.', "في دميربي القابضة للسيارات الفاخرة، نقدم لكم أرقى العلامات العالمية. كل سيارة في مجموعتنا مختارة بعناية ومفحوصة من قبل خبرائنا."],
            ],
            [
                'tr' => ['Aracınız İçin Orijinal Yedek Parça', 'Lüks araçlar için orijinal yedek parçanın önemi.', "Lüks bir aracın performansını ve değerini korumak için orijinal yedek parça kullanmak şarttır. Demirbey mağazasında, dünya genelinden orijinal parçalara güvenli ödeme ile ulaşabilirsiniz."],
                'en' => ['Genuine Spare Parts for Your Car', 'The importance of genuine spare parts for luxury vehicles.', "Using genuine spare parts is essential to preserve the performance and value of a luxury car. At the Demirbey store, you can access genuine parts from around the world with secure payment."],
                'ar' => ['قطع غيار أصلية لسيارتك', 'أهمية قطع الغيار الأصلية للسيارات الفاخرة.', "استخدام قطع الغيار الأصلية ضروري للحفاظ على أداء وقيمة السيارة الفاخرة. في متجر دميربي يمكنك الحصول على قطع أصلية مع دفع آمن."],
            ],
        ];

        foreach ($posts as $i => $p) {
            Post::updateOrCreate(
                ['slug' => Str::slug($p['tr'][0])],
                [
                    'title_tr' => $p['tr'][0], 'excerpt_tr' => $p['tr'][1], 'body_tr' => $p['tr'][2],
                    'title_en' => $p['en'][0], 'excerpt_en' => $p['en'][1], 'body_en' => $p['en'][2],
                    'title_ar' => $p['ar'][0], 'excerpt_ar' => $p['ar'][1], 'body_ar' => $p['ar'][2],
                    'is_published' => true,
                    'published_at' => now()->subDays(($i + 1) * 3),
                ]
            );
        }
    }
}
