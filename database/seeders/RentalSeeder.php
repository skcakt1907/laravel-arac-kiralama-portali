<?php

namespace Database\Seeders;

use App\Models\Rental;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RentalSeeder extends Seeder
{
    public function run(): void
    {
        $rentals = [
            ['brand' => 'Rolls-Royce',  'model' => 'Cullinan',    'year' => 2023, 'daily_price' => 5500, 'seats' => 5, 'engine' => 'V12 6.75L',   'transmission' => 'Otomatik', 'body_type' => 'SUV',   'featured' => true],
            ['brand' => 'Lamborghini',  'model' => 'Urus',        'year' => 2024, 'daily_price' => 3800, 'seats' => 5, 'engine' => 'V8 4.0L',     'transmission' => 'Otomatik', 'body_type' => 'SUV',   'featured' => true],
            ['brand' => 'Ferrari',      'model' => 'Roma',        'year' => 2023, 'daily_price' => 4200, 'seats' => 2, 'engine' => 'V8 3.9L',     'transmission' => 'Otomatik', 'body_type' => 'Coupe', 'featured' => true],
            ['brand' => 'Bentley',      'model' => 'Bentayga',    'year' => 2023, 'daily_price' => 3200, 'seats' => 5, 'engine' => 'W12 6.0L',    'transmission' => 'Otomatik', 'body_type' => 'SUV',   'featured' => false],
            ['brand' => 'Mercedes-Benz','model' => 'G 63 AMG',    'year' => 2024, 'daily_price' => 2900, 'seats' => 5, 'engine' => 'V8 4.0L',     'transmission' => 'Otomatik', 'body_type' => 'SUV',   'featured' => false],
            ['brand' => 'Porsche',      'model' => '911 Carrera', 'year' => 2023, 'daily_price' => 2400, 'seats' => 4, 'engine' => 'Flat-6 3.0L', 'transmission' => 'Otomatik', 'body_type' => 'Coupe', 'featured' => false],
        ];

        foreach ($rentals as $i => $r) {
            Rental::updateOrCreate(
                ['slug' => Str::slug($r['brand'] . '-' . $r['model'] . '-kiralik')],
                [
                    'brand'        => $r['brand'],
                    'model'        => $r['model'],
                    'year'         => $r['year'],
                    'daily_price'  => $r['daily_price'],
                    'currency'     => 'AED',
                    'seats'        => $r['seats'],
                    'engine'       => $r['engine'],
                    'transmission' => $r['transmission'],
                    'body_type'    => $r['body_type'],
                    'is_published' => true,
                    'is_featured'  => $r['featured'],
                    'sort_order'   => $i,
                ]
            );
        }
    }
}
