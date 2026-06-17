@php($locale = app()->getLocale())
@php($tt = $t[$locale] ?? $t['tr'])
@php($btn = ['tr' => 'Ana Sayfaya Dön', 'en' => 'Back to Home', 'ar' => 'العودة إلى الرئيسية'][$locale] ?? 'Ana Sayfaya Dön')
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $code }} · {{ config('app.name') }}</title>
@if (function_exists('setting') && setting('favicon'))<link rel="icon" href="{{ asset('storage/' . setting('favicon')) }}">@endif
<link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Jost:wght@300;400;500&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:#0e0d0a;color:#e6e0d2;font-family:'Jost',system-ui,sans-serif;min-height:100vh;
    display:flex;align-items:center;justify-content:center;text-align:center;padding:24px;
    background-image:radial-gradient(60% 50% at 50% 0%,rgba(200,164,77,.10),transparent 70%)}
  .err{max-width:560px}
  .logo-img{max-height:54px;max-width:220px;margin:0 auto 6px;display:block}
  .mark{font-family:'Marcellus',serif;font-size:30px;letter-spacing:.32em;color:#e9d6a0}
  .sub{font-size:9px;letter-spacing:.5em;color:#c8a44d;text-transform:uppercase;margin-top:4px}
  .code{font-family:'Marcellus',serif;font-size:104px;line-height:1;color:#c8a44d;margin:34px 0 6px;
    text-shadow:0 2px 30px rgba(200,164,77,.25)}
  h1{font-family:'Marcellus',serif;font-weight:400;font-size:26px;color:#e9d6a0;margin-bottom:12px}
  p{color:#8d8470;font-size:15px;line-height:1.7;margin-bottom:30px}
  .btn{display:inline-block;padding:13px 30px;border:1px solid #c8a44d;color:#e9d6a0;text-decoration:none;
    letter-spacing:.18em;text-transform:uppercase;font-size:12px;border-radius:4px;transition:.2s}
  .btn:hover{background:linear-gradient(120deg,#a9883c,#e9d6a0 60%,#c8a44d);color:#15120a;border-color:transparent}
  .diamond{color:#c8a44d;opacity:.5;font-size:13px;margin:22px 0 0}
</style>
</head>
<body>
  <div class="err">
    @if (function_exists('setting') && setting('logo'))
      <img class="logo-img" src="{{ asset('storage/' . setting('logo')) }}" alt="{{ config('app.name') }}">
    @else
      <div class="mark">@lang('site.brand_mark')</div>
      <div class="sub">@lang('site.brand_sub')</div>
    @endif

    <div class="code">{{ $code }}</div>
    <h1>{{ $tt['h'] }}</h1>
    <p>{{ $tt['p'] }}</p>
    <a class="btn" href="{{ url('/') }}">{{ $btn }}</a>
    <div class="diamond">✦</div>
  </div>
</body>
</html>
