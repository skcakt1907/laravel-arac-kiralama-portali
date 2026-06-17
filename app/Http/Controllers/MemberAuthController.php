<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class MemberAuthController extends Controller
{
    public function showRegister()
    {
        return view('member.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email', 'max:160', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member.account')->with('ok', 'Hesabınız oluşturuldu.');
    }

    public function showLogin()
    {
        return view('member.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Brute-force koruması: aynı e-posta+IP için 5 başarısız denemeden sonra 60 sn kilit
        $key = 'member-login:' . Str::lower($data['email']) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $sec = RateLimiter::availableIn($key);
            return back()->withErrors(['email' => "Çok fazla başarısız deneme. {$sec} sn sonra tekrar deneyin."])->onlyInput('email');
        }

        if (Auth::attempt($data, $request->boolean('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            // yönetici giriş yaptıysa panele yönlendir
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('member.account'));
        }

        RateLimiter::hit($key, 60);
        return back()->withErrors(['email' => 'E-posta veya parola hatalı.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
