<?php

namespace App\Http\Controllers;

use App\Models\Part;
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

        // Ana sayfa parça vitrini: öne çıkan parçalar (DB'den)
        $parts = Part::published()
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        if ($parts->isEmpty()) {
            $parts = Part::published()->latest()->take(4)->get();
        }

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
