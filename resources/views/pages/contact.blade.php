@extends('layouts.app')
@section('title', __('site.con_title'))

@section('content')
<section class="contact">
  <div class="container">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.con_eye')</div>
      <h2>@lang('site.con_title')</h2>
      <span class="diamond">✦</span>
    </div>

    @if (session('sent_ok'))
      <div class="flash">@lang('site.sent_ok')</div>
    @endif

    @include('partials.contact-cta')

    <div class="contact-grid">
      <div class="c-info">
        <h3 class="serif">@lang('site.showroom')</h3>
        <div class="c-line"><i>⌖</i><span>{{ setting_l('address', __('site.addr')) }}</span></div>
        <div class="c-line"><i>✆</i><span>{{ setting('phone', '+971 — — — — —') }}</span></div>
        <div class="c-line"><i>✉</i><span>{{ setting('email', 'info@ornek-kiralama.com') }}</span></div>
        <div class="c-line"><i>◷</i><span>{{ setting_l('hours', __('site.hours')) }}</span></div>
        @if (setting('instagram') || setting('facebook') || setting('twitter') || setting('whatsapp'))
          <div class="c-line" style="gap:18px;margin-top:8px">
            @if (setting('whatsapp'))<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('whatsapp')) }}" target="_blank" style="color:var(--gold-l);text-decoration:none">WhatsApp</a>@endif
            @if (setting('instagram'))<a href="{{ setting('instagram') }}" target="_blank" style="color:var(--gold-l);text-decoration:none">Instagram</a>@endif
            @if (setting('facebook'))<a href="{{ setting('facebook') }}" target="_blank" style="color:var(--gold-l);text-decoration:none">Facebook</a>@endif
            @if (setting('twitter'))<a href="{{ setting('twitter') }}" target="_blank" style="color:var(--gold-l);text-decoration:none">X</a>@endif
          </div>
        @endif
        @if (setting('map_embed'))
          <div style="margin-top:24px">{!! embed_html(setting('map_embed')) !!}</div>
        @endif
      </div>

      <form method="POST" action="{{ route('contact.send') }}">
        @csrf
        <input type="text" name="name" value="{{ old('name') }}" placeholder="@lang('site.ph_name')" required>
        @error('name')<div class="field-err">{{ $message }}</div>@enderror
        <input type="email" name="email" value="{{ old('email') }}" placeholder="@lang('site.ph_email')" required>
        @error('email')<div class="field-err">{{ $message }}</div>@enderror
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="@lang('site.ph_phone')">
        <textarea name="message" placeholder="@lang('site.ph_msg')" required>{{ old('message') }}</textarea>
        @error('message')<div class="field-err">{{ $message }}</div>@enderror
        <button class="btn solid" style="border:none;cursor:pointer">@lang('site.send')</button>
      </form>
    </div>
  </div>
</section>
@endsection
