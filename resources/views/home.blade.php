@extends('layouts.app')

@section('content')

<header class="hero">
  <div class="kicker">@lang('site.kicker')</div>
  <h1 class="gold-grad">@lang('site.brand_mark')</h1>
  <div class="tag">@lang('site.tag')</div>

  <svg class="car-art" viewBox="0 0 600 200" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="goldLine" x1="0" y1="0" x2="600" y2="0" gradientUnits="userSpaceOnUse">
        <stop offset="0" stop-color="#8a6d2f"/><stop offset=".5" stop-color="#e9d6a0"/><stop offset="1" stop-color="#8a6d2f"/>
      </linearGradient>
    </defs>
    <path class="draw" stroke="url(#goldLine)" stroke-width="2" stroke-linecap="round"
      d="M30 150 C60 148 72 146 92 140 C98 119 112 104 152 97 C182 92 202 89 232 77 C252 63 282 55 320 55 C356 55 381 63 401 76 C431 88 471 94 511 102 C541 108 561 118 566 132 C569 142 561 148 546 150 L495 150 A40 40 0 0 0 415 150 L195 150 A40 40 0 0 0 115 150 L38 150"/>
    <circle class="draw" cx="155" cy="150" r="29" stroke="url(#goldLine)" stroke-width="2"/>
    <circle cx="155" cy="150" r="11" stroke="#c8a44d" stroke-width="1.4" opacity=".7"/>
    <circle class="draw" cx="455" cy="150" r="29" stroke="url(#goldLine)" stroke-width="2"/>
    <circle cx="455" cy="150" r="11" stroke="#c8a44d" stroke-width="1.4" opacity=".7"/>
    <path d="M20 186 L580 186" stroke="#c8a44d" stroke-width="1" opacity=".3"/>
  </svg>

  <div class="cta-row">
    <a href="{{ url('/#koleksiyon') }}" class="btn solid">@lang('site.cta1')</a>
    <a href="{{ url('/#iletisim') }}" class="btn ghost">@lang('site.cta2')</a>
  </div>
</header>

<div class="marquee">
  <div class="marquee-track">
    @foreach (array_merge($brands, $brands) as $brand)
      <span>{{ $brand }}</span><i>✦</i>
    @endforeach
  </div>
</div>

<section id="koleksiyon">
  <div class="container">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.col_eye')</div>
      <h2>@lang('site.col_title')</h2>
      <p>@lang('site.col_sub')</p>
      <span class="diamond">✦</span>
    </div>
    <div class="cars">
      @foreach ($cars as $car)
        <div class="car-card">
          <div class="car-brand">{{ $car['brand'] }}</div>
          <div class="car-model">{{ $car['model'] }}</div>
          <svg viewBox="0 0 600 190" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="gf{{ $loop->index }}" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#e9d6a0" stop-opacity=".85"/><stop offset="1" stop-color="#7c6228" stop-opacity=".55"/></linearGradient></defs><path fill="url(#gf{{ $loop->index }})" d="M25 132 C60 110 100 96 150 88 C210 70 290 62 360 70 C420 76 480 92 535 110 C555 116 560 124 552 134 L540 138 L505 138 A38 38 0 0 0 429 138 L215 138 A38 38 0 0 0 139 138 L45 138 C30 138 20 136 25 132 Z"/><circle cx="177" cy="138" r="27" fill="#0b0a08" stroke="#c8a44d" stroke-width="2"/><circle cx="177" cy="138" r="10" fill="none" stroke="#c8a44d" stroke-width="1.4"/><circle cx="467" cy="138" r="27" fill="#0b0a08" stroke="#c8a44d" stroke-width="2"/><circle cx="467" cy="138" r="10" fill="none" stroke="#c8a44d" stroke-width="1.4"/><path d="M30 172 L570 172" stroke="#c8a44d" stroke-width="1" opacity=".25"/></svg>
          <div class="car-specs">{{ $car['specs'] }}</div>
          <div class="car-foot">
            <span class="poa">✦ @lang('site.poa')</span>
            <a class="mini-btn" href="{{ url('/#iletisim') }}">@lang('site.inq')</a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section id="parca" class="parts">
  <div class="container parts-grid">
    <div>
      <div class="eyebrow">@lang('site.parts_eye')</div>
      <h2 class="serif">@lang('site.parts_title')</h2>
      <p>@lang('site.parts_sub')</p>
      <br>
      <a href="{{ url('/#parca') }}" class="btn ghost">@lang('site.parts_btn')</a>
    </div>
    <div class="part-items">
      @foreach ($parts as $part)
        <div class="part">
          <div>
            <div class="n">{{ $part['name'] }}</div>
            <div class="c">{{ $part['car'] }}</div>
          </div>
          <div class="p">{{ $part['price'] }}</div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section id="hakkimizda">
  <div class="container">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.about_eye')</div>
      <h2>@lang('site.about_title')</h2>
    </div>
    <p class="about-text">@lang('site.about_text')</p>
    <div class="stats">
      <div class="stat"><b class="gold-grad">250+</b><span>@lang('site.stat1')</span></div>
      <div class="stat"><b class="gold-grad">30+</b><span>@lang('site.stat2')</span></div>
      <div class="stat"><b class="gold-grad">15</b><span>@lang('site.stat3')</span></div>
    </div>
  </div>
</section>

<section id="iletisim" class="contact">
  <div class="container">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.con_eye')</div>
      <h2>@lang('site.con_title')</h2>
    </div>

    @if (session('sent_ok'))
      <div class="flash">@lang('site.sent_ok')</div>
    @endif

    <div class="contact-grid">
      <div class="c-info">
        <h3 class="serif">@lang('site.showroom')</h3>
        <div class="c-line"><i>⌖</i><span>@lang('site.addr')</span></div>
        <div class="c-line"><i>✆</i><span>+971 — — — — —</span></div>
        <div class="c-line"><i>✉</i><span>info@ornek-kiralama.com</span></div>
        <div class="c-line"><i>◷</i><span>@lang('site.hours')</span></div>
      </div>
      <form method="POST" action="{{ route('contact.send') }}">
        @csrf
        <input type="text" name="name" value="{{ old('name') }}" placeholder="@lang('site.ph_name')" required>
        @error('name')<div class="field-err">{{ $message }}</div>@enderror
        <input type="email" name="email" value="{{ old('email') }}" placeholder="@lang('site.ph_email')" required>
        @error('email')<div class="field-err">{{ $message }}</div>@enderror
        <textarea name="message" placeholder="@lang('site.ph_msg')" required>{{ old('message') }}</textarea>
        @error('message')<div class="field-err">{{ $message }}</div>@enderror
        <button class="btn solid" style="border:none;cursor:pointer">@lang('site.send')</button>
      </form>
    </div>
  </div>
</section>

@endsection
