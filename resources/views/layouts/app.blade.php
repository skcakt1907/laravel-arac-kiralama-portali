@php($locale = app()->getLocale())
@php($cartCount = app(\App\Services\Cart::class)->count())
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', config('app.name')) — @lang('site.brand_sub')</title>
<meta name="description" content="@yield('meta_description', __('site.about_text'))">
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
              <a href="{{ url('/#iletisim') }}">@lang('site.cta2')<small>@lang('site.contact_sub')</small></a>
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
      <div class="mark serif">@lang('site.brand_mark')</div>
      <div class="sub">@lang('site.brand_sub')</div>
    </a>

    <div class="nav-links">
      <a href="{{ url('/#hakkimizda') }}">@lang('site.nav_about')</a>
      <a href="{{ url('/#iletisim') }}">@lang('site.nav_contact')</a>
    </div>
  </div>
</nav>

@yield('content')

<footer>
  <div class="f-logo">@lang('site.brand_mark')</div>
  <div>© {{ date('Y') }} {{ config('app.name') }} · Dubai · @lang('site.rights')</div>
</footer>

@stack('scripts')
</body>
</html>
