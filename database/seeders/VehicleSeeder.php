<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // NOT: Bu örnek veriler, F1RST Motors scraper'ı (Faz 2 son adım)
        // bağlanana kadar şablonun gerçek veriyle çalıştığını göstermek için.
        $vehicles = [
            ['brand' => 'Rolls-Royce',      'model' => 'Phantom VIII',   'year' => 2024, 'mileage_km' => 1200,  'engine' => 'V12 6.75L',   'fuel' => 'Benzin',  'transmission' => 'Otomatik', 'body_type' => 'Sedan', 'color' => 'Siyah',   'featured' => true],
            ['brand' => 'Ferrari',          'model' => 'SF90 Stradale',  'year' => 2023, 'mileage_km' => 4500,  'engine' => 'V8 Hybrid',   'fuel' => 'Hibrit',  'transmission' => 'Otomatik', 'body_type' => 'Coupe', 'color' => 'Kırmızı', 'featured' => true],
            ['brand' => 'Lamborghini',      'model' => 'Revuelto',       'year' => 2024, 'mileage_km' => 800,   'engine' => 'V12 Hybrid',  'fuel' => 'Hibrit',  'transmission' => 'Otomatik', 'body_type' => 'Coupe', 'color' => 'Sarı',    'featured' => true],
            ['brand' => 'Bentley',          'model' => 'Continental GT', 'year' => 2023, 'mileage_km' => 6100,  'engine' => 'W12 6.0L',    'fuel' => 'Benzin',  'transmission' => 'Otomatik', 'body_type' => 'Coupe', 'color' => 'Lacivert','featured' => true],
            ['brand' => 'McLaren',          'model' => '765LT Spider',   'year' => 2022, 'mileage_km' => 3900,  'engine' => 'V8 4.0L',     'fuel' => 'Benzin',  'transmission' => 'Otomatik', 'body_type' => 'Cabrio','color' => 'Turuncu', 'featured' => true],
            ['brand' => 'Mercedes-Maybach', 'model' => 'S 680 4MATIC',   'year' => 2024, 'mileage_km' => 2300,  'engine' => 'V12 6.0L',    'fuel' => 'Benzin',  'transmission' => 'Otomatik', 'body_type' => 'Sedan', 'color' => 'Siyah',   'featured' => true],
            ['brand' => 'Bugatti',          'model' => 'Chiron Sport',   'year' => 2021, 'mileage_km' => 1500,  'engine' => 'W16 8.0L',    'fuel' => 'Benzin',  'transmission' => 'Otomatik', 'body_type' => 'Coupe', 'color' => 'Mavi',    'featured' => false],
            ['brand' => 'Porsche',          'model' => '911 Turbo S',    'year' => 2023, 'mileage_km' => 5200,  'engine' => 'Flat-6 3.7L', 'fuel' => 'Benzin',  'transmission' => 'Otomatik', 'body_type' => 'Coupe', 'color' => 'Gümüş',   'featured' => false],
        ];

        foreach ($vehicles as $i => $v) {
            Vehicle::updateOrCreate(
                ['slug' => Str::slug($v['brand'] . ' ' . $v['model'])],
                [
                    'brand'        => $v['brand'],
                    'model'        => $v['model'],
                    'year'         => $v['year'],
                    'mileage_km'   => $v['mileage_km'],
                    'engine'       => $v['engine'],
                    'fuel'         => $v['fuel'],
                    'transmission' => $v['transmission'],
                    'body_type'    => $v['body_type'],
                    'color'        => $v['color'],
                    'description'  => null,
                    'is_published' => true,
                    'is_featured'  => $v['featured'],
                    'sort_order'   => $i,
                ]
            );
        }
    }
}
