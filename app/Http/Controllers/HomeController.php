<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use App\Models\Part;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:160'],
            'phone'   => ['nullable', 'string', 'max:40'],
            'vehicle' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        // Mesajı admin e-postasına gönder (Site Ayarları > E-posta; yoksa MAIL_FROM).
        // Mail başarısız olsa bile form akışı bozulmasın.
        $to = setting('email') ?: config('mail.from.address');
        try {
            Mail::to($to)->send(new ContactMessageMail($data));
        } catch (\Throwable $e) {
            Log::warning('İletişim maili gönderilemedi: ' . $e->getMessage());
        }

        return redirect()->to(url('/#iletisim'))->with('sent_ok', true);
    }
}
