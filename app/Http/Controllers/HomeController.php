<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $brands = ['Rolls-Royce', 'Bentley', 'Ferrari', 'Lamborghini', 'McLaren', 'Bugatti', 'Maybach', 'Porsche'];

        // Ana sayfa vitrini: öne çıkan araçlar (DB'den). Yoksa son eklenenler.
        $cars = Vehicle::published()
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        if ($cars->isEmpty()) {
            $cars = Vehicle::published()->latest()->take(6)->get();
        }

        // NOT: Parçalar Faz 3'te DB'den gelecek; şimdilik örnek.
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
            'phone'   => ['nullable', 'string', 'max:40'],
            'vehicle' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        // NOT: Faz 4'te bu mesaj DB'ye kaydedilecek ve/veya e-posta gönderilecek.

        return redirect()->to(url('/#iletisim'))->with('sent_ok', true);
    }
}
