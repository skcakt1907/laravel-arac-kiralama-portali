<?php

namespace Database\Seeders;

use App\Models\Part;
use App\Models\PartCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PartSeeder extends Seeder
{
    public function run(): void
    {
        // NOT: Örnek katalog — gerçek ürünler admin panelden (Faz 5) girilecek.
        $categories = ['Fren Sistemi', 'Aydınlatma', 'İç Mekan', 'Egzoz', 'Jant & Lastik'];
        $catModels  = [];
        foreach ($categories as $i => $name) {
            $catModels[$name] = PartCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort_order' => $i]
            );
        }

        $parts = [
            ['name' => 'Karbon Fren Diski Seti', 'cat' => 'Fren Sistemi',  'compatible' => 'FERRARI · 488 / F8',   'price' => 4850,  'stock' => 6,  'featured' => true],
            ['name' => 'LED Far Ünitesi',        'cat' => 'Aydınlatma',    'compatible' => 'ROLLS-ROYCE · GHOST',  'price' => 7200,  'stock' => 4,  'featured' => true],
            ['name' => 'Alcantara Direksiyon',   'cat' => 'İç Mekan',      'compatible' => 'LAMBORGHINI · URUS',   'price' => 2390,  'stock' => 9,  'featured' => true],
            ['name' => 'Titanyum Egzoz Sistemi', 'cat' => 'Egzoz',         'compatible' => 'McLAREN · 720S',       'price' => 11600, 'stock' => 3,  'featured' => true],
            ['name' => 'Seramik Fren Balatası',  'cat' => 'Fren Sistemi',  'compatible' => 'BENTLEY · CONTINENTAL','price' => 1850,  'stock' => 12, 'featured' => false],
            ['name' => 'Forged Jant Takımı 22"', 'cat' => 'Jant & Lastik', 'compatible' => 'LAMBORGHINI · URUS',   'price' => 9400,  'stock' => 5,  'featured' => false],
            ['name' => 'Karbon Ayna Kapağı',     'cat' => 'İç Mekan',      'compatible' => 'FERRARI · SF90',       'price' => 1290,  'stock' => 15, 'featured' => false],
            ['name' => 'Sport Egzoz Valfi',      'cat' => 'Egzoz',         'compatible' => 'PORSCHE · 911',        'price' => 2750,  'stock' => 0,  'featured' => false],
        ];

        foreach ($parts as $i => $p) {
            Part::updateOrCreate(
                ['slug' => Str::slug($p['name'])],
                [
                    'part_category_id' => $catModels[$p['cat']]->id,
                    'name'             => $p['name'],
                    'sku'              => 'DMB-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                    'compatible'       => $p['compatible'],
                    'price'            => $p['price'],
                    'currency'         => 'USD',
                    'stock'            => $p['stock'],
                    'short_desc'       => null,
                    'is_published'     => true,
                    'is_featured'      => $p['featured'],
                    'sort_order'       => $i,
                ]
            );
        }
    }
}
