@php($locale = app()->getLocale())
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
  <div class="lang">
    @foreach (['tr' => 'TR', 'en' => 'EN', 'ar' => 'AR'] as $code => $label)
      <a href="{{ route('lang.switch', $code) }}" class="{{ $locale === $code ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
  </div>
</div>

<nav>
  <div class="container nav-grid">
    <div class="nav-links left">
      <a href="{{ route('vehicles.index') }}">@lang('site.nav_collection')</a>
      <a href="{{ url('/#parca') }}">@lang('site.nav_parts')</a>
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
