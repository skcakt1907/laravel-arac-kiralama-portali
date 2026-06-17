@php($locale = app()->getLocale())
@php($cartCount = app(\App\Services\Cart::class)->count())
@php($compareCount = count(session('compare', [])))
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', config('app.name')) — @lang('site.brand_sub')</title>
<meta name="description" content="@yield('meta_description', Str::limit(strip_tags(setting_l('about', __('site.about_text'))), 160))">
<link rel="canonical" href="{{ url()->current() }}">
@if (setting('favicon'))<link rel="icon" href="{{ asset('storage/' . setting('favicon')) }}">@endif

{{-- Open Graph / Twitter --}}
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="@yield('title', config('app.name'))">
<meta property="og:description" content="@yield('meta_description', Str::limit(strip_tags(setting_l('about', __('site.about_text'))), 160))">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:locale" content="{{ $locale === 'ar' ? 'ar_AE' : ($locale === 'en' ? 'en_US' : 'tr_TR') }}">
<meta property="og:image" content="@yield('og_image', asset('img/og-default.jpg'))">
<meta name="twitter:card" content="summary_large_image">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Jost:wght@300;400;500&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}">
@stack('head')
</head>
<body>

<div class="topbar">
  <span>@lang('site.location')</span>
  <div class="lang" style="gap:14px;align-items:center">
    @auth
      <a href="{{ route('member.account') }}">@lang('site.my_account')</a>
      <form method="POST" action="{{ route('member.logout') }}" style="display:inline">@csrf<button type="submit" style="background:none;border:none;color:inherit;font:inherit;letter-spacing:.18em;cursor:pointer;padding:0">@lang('site.logout')</button></form>
    @else
      <a href="{{ route('member.login') }}">@lang('site.login')</a>
      <a href="{{ route('member.register') }}">@lang('site.register')</a>
    @endauth
    <a href="{{ route('compare.index') }}">@lang('site.compare') ({{ $compareCount }})</a>
    <a href="{{ route('cart.index') }}">@lang('site.cart') ({{ $cartCount }})</a>
    <span style="display:flex;gap:4px">
      @foreach (['tr' => 'TR', 'en' => 'EN', 'ar' => 'AR'] as $code => $label)
        <a href="{{ route('lang.switch', $code) }}" class="{{ $locale === $code ? 'active' : '' }}">{{ $label }}</a>
      @endforeach
    </span>
  </div>
</div>

<nav>
  <div class="container nav-grid">
    <button class="hamburger" type="button" aria-label="Menü" onclick="document.getElementById('mobileMenu').classList.toggle('open')">☰</button>
    <div class="nav-links left">
      {{-- ARAÇLAR mega menü --}}
      <div class="nav-item">
        <a href="{{ route('vehicles.index') }}">@lang('site.nav_collection') <span class="caret">▼</span></a>
        <div class="mega">
          <div class="mega-inner">
            <div class="mega-quick">
              <div class="mega-title">@lang('site.col_eye')</div>
              <a href="{{ route('vehicles.index') }}">@lang('site.all_vehicles')<small>@lang('site.all_vehicles_sub')</small></a>
              <a href="{{ route('vehicles.index', ['sort' => 'new']) }}">@lang('site.newest')<small>@lang('site.newest_sub')</small></a>
              <a href="{{ route('vehicles.index', ['sold' => 1]) }}">@lang('site.sold_cars')</a>
              <a href="{{ route('compare.index') }}">@lang('site.compare')</a>
              <a href="{{ route('sell.create') }}">@lang('site.nav_sell')</a>
            </div>
            <div>
              <div class="mega-title">@lang('site.brands')</div>
              <div class="brand-grid">
                @foreach ($navBrands as $b)
                  @php($logo = 'img/brands/' . \Illuminate\Support\Str::slug($b->brand) . '.png')
                  <a class="brand-link" href="{{ route('vehicles.index', ['brand' => $b->brand]) }}">
                    <span class="bl-name">
                      @if (file_exists(public_path($logo)))
                        <img class="brand-logo" src="{{ asset($logo) }}" alt="{{ $b->brand }}" loading="lazy">
                      @else
                        <span class="brand-logo mono">{{ mb_substr($b->brand, 0, 1) }}</span>
                      @endif
                      {{ $b->brand }}
                    </span>
                    <span class="cnt">{{ $b->c }}</span>
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- MAĞAZA mega menü --}}
      <div class="nav-item">
        <a href="{{ route('shop.index') }}">@lang('site.nav_parts') <span class="caret">▼</span></a>
        <div class="mega mega-sm">
          <div class="mega-inner">
            <div>
              <div class="mega-title">@lang('site.categories')</div>
              <div class="brand-grid">
                <a class="brand-link" href="{{ route('shop.index') }}"><span>@lang('site.all')</span></a>
                @foreach ($navCategories as $cat)
                  <a class="brand-link" href="{{ route('shop.index', ['category' => $cat->slug]) }}">
                    <span>{{ $cat->name }}</span>
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <a class="logo" href="{{ url('/') }}">
      @if (setting('logo'))
        <img class="logo-img" src="{{ asset('storage/' . setting('logo')) }}" alt="{{ config('app.name') }}">
      @else
        <div class="mark serif">@lang('site.brand_mark')</div>
        <div class="sub">@lang('site.brand_sub')</div>
      @endif
    </a>

    <div class="nav-links">
      <a href="{{ route('rentals.index') }}">@lang('site.nav_rentals')</a>
      <a href="{{ route('blog.index') }}">@lang('site.nav_blog')</a>
      <a href="{{ route('pages.about') }}">@lang('site.nav_about')</a>
      <a href="{{ route('pages.contact') }}">@lang('site.nav_contact')</a>
    </div>

    <span class="hamburger" style="visibility:hidden" aria-hidden="true">☰</span>
  </div>

  {{-- Mobil menü --}}
  <div class="mobile-menu" id="mobileMenu">
    <a href="{{ route('vehicles.index') }}">@lang('site.nav_collection')</a>
    <a href="{{ route('rentals.index') }}">@lang('site.nav_rentals')</a>
    <a href="{{ route('shop.index') }}">@lang('site.nav_parts')</a>
    <a href="{{ route('blog.index') }}">@lang('site.nav_blog')</a>
    <a href="{{ route('sell.create') }}">@lang('site.nav_sell')</a>
    <a href="{{ route('compare.index') }}">@lang('site.compare') ({{ $compareCount }})</a>
    <a href="{{ route('pages.about') }}">@lang('site.nav_about')</a>
    <a href="{{ route('pages.contact') }}">@lang('site.nav_contact')</a>
    <a href="{{ route('cart.index') }}">@lang('site.cart') ({{ $cartCount }})</a>
  </div>
</nav>

@yield('content')

<footer>
  <div class="f-logo">@lang('site.brand_mark')</div>
  <nav class="f-legal">
    <a href="{{ route('policy.show', 'gizlilik') }}">@lang('site.f_privacy')</a>
    <a href="{{ route('policy.show', 'mesafeli-satis') }}">@lang('site.f_distance')</a>
    <a href="{{ route('policy.show', 'iptal-iade') }}">@lang('site.f_refund')</a>
    <a href="{{ route('policy.show', 'teslimat') }}">@lang('site.f_delivery')</a>
    <a href="{{ route('policy.show', 'cerez-politikasi') }}">@lang('site.f_cookies')</a>
    <a href="{{ route('policy.show', 'kullanim-kosullari') }}">@lang('site.f_terms')</a>
  </nav>
  <div>© {{ date('Y') }} {{ config('app.name') }} · Dubai · @lang('site.rights')</div>
</footer>

{{-- Sabit WhatsApp butonu — numara Site Ayarları'ndan (boşsa görünmez) --}}
@if (setting('whatsapp'))
  @php($waNum = preg_replace('/\D/', '', setting('whatsapp')))
  <a class="wa-float" href="https://wa.me/{{ $waNum }}?text={{ rawurlencode(__('site.wa_text')) }}"
     target="_blank" rel="noopener" aria-label="@lang('site.wa_label')" title="@lang('site.wa_label')">
    <svg viewBox="0 0 32 32" width="30" height="30" fill="currentColor" aria-hidden="true">
      <path d="M16.04 3.2c-7.1 0-12.86 5.76-12.86 12.86 0 2.27.6 4.48 1.73 6.43L3.1 28.8l6.5-1.7a12.8 12.8 0 0 0 6.43 1.64h.01c7.1 0 12.86-5.76 12.86-12.86S23.14 3.2 16.04 3.2zm0 23.5h-.01c-1.98 0-3.92-.53-5.6-1.53l-.4-.24-3.86 1.01 1.03-3.76-.26-.39a10.6 10.6 0 0 1-1.63-5.66c0-5.9 4.8-10.7 10.72-10.7 2.86 0 5.55 1.12 7.57 3.14a10.62 10.62 0 0 1 3.13 7.57c0 5.9-4.8 10.7-10.72 10.7zm5.88-8.02c-.32-.16-1.9-.94-2.2-1.05-.3-.11-.51-.16-.73.16-.21.32-.83 1.05-1.02 1.26-.19.21-.37.24-.69.08-.32-.16-1.36-.5-2.59-1.6-.96-.85-1.6-1.9-1.79-2.22-.19-.32-.02-.49.14-.65.14-.14.32-.37.48-.56.16-.19.21-.32.32-.53.11-.21.05-.4-.03-.56-.08-.16-.73-1.76-1-2.4-.26-.63-.53-.55-.73-.56l-.62-.01c-.21 0-.56.08-.85.4-.29.32-1.12 1.1-1.12 2.66 0 1.57 1.14 3.08 1.3 3.3.16.21 2.24 3.42 5.43 4.8.76.33 1.35.52 1.81.67.76.24 1.45.21 2 .13.61-.09 1.9-.78 2.17-1.53.27-.75.27-1.4.19-1.53-.08-.13-.29-.21-.61-.37z"/>
    </svg>
  </a>
@endif

@stack('scripts')
</body>
</html>
