<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Brute-force koruması: aynı e-posta+IP için 5 başarısız denemeden sonra 60 sn kilit
        $key = 'admin-login:' . Str::lower($data['email']) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $sec = RateLimiter::availableIn($key);
            return back()->withErrors(['email' => "Çok fazla başarısız deneme. {$sec} sn sonra tekrar deneyin."])->onlyInput('email');
        }

        if (Auth::attempt($data, $request->boolean('remember'))) {
            if (! Auth::user()->is_admin) {
                Auth::logout();
                RateLimiter::hit($key, 60);
                return back()->withErrors(['email' => 'Bu hesabın yönetici yetkisi yok.'])->onlyInput('email');
            }

            RateLimiter::clear($key);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($key, 60);
        return back()->withErrors(['email' => 'E-posta veya parola hatalı.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
