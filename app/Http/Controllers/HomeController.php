<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // NOT: Faz 2'de bu veriler F1RST Motors'tan çekilip DB'den gelecek.
        // Şimdilik şablon yerleşimi için örnek (placeholder) veriler.
        $brands = ['Rolls-Royce', 'Bentley', 'Ferrari', 'Lamborghini', 'McLaren', 'Bugatti', 'Maybach', 'Porsche'];

        $cars = [
            ['brand' => 'ROLLS-ROYCE',      'model' => 'Phantom VIII',   'specs' => '2024 · 1.200 km · V12 6.75L'],
            ['brand' => 'FERRARI',          'model' => 'SF90 Stradale',  'specs' => '2023 · 4.500 km · V8 Hybrid'],
            ['brand' => 'LAMBORGHINI',      'model' => 'Revuelto',       'specs' => '2024 · 800 km · V12 Hybrid'],
            ['brand' => 'BENTLEY',          'model' => 'Continental GT', 'specs' => '2023 · 6.100 km · W12 6.0L'],
            ['brand' => 'McLAREN',          'model' => '765LT Spider',   'specs' => '2022 · 3.900 km · V8 4.0L'],
            ['brand' => 'MERCEDES-MAYBACH', 'model' => 'S 680 4MATIC',   'specs' => '2024 · 2.300 km · V12 6.0L'],
        ];

        $parts = [
            ['name' => 'Karbon Fren Diski Seti', 'car' => 'FERRARI · 488 / F8',     'price' => '$4.850'],
            ['name' => 'LED Far Ünitesi',        'car' => 'ROLLS-ROYCE · GHOST',    'price' => '$7.200'],
            ['name' => 'Alcantara Direksiyon',   'car' => 'LAMBORGHINI · URUS',     'price' => '$2.390'],
            ['name' => 'Titanyum Egzoz Sistemi', 'car' => 'McLAREN · 720S',         'price' => '$11.600'],
        ];

        return view('home', compact('brands', 'cars', 'parts'));
    }

    public function contact(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:160'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        // NOT: Faz 4'te bu mesaj DB'ye kaydedilecek ve/veya e-posta gönderilecek.

        return redirect()->to(url('/#iletisim'))->with('sent_ok', true);
    }
}
