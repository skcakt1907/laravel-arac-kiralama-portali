@extends('layouts.app')
@section('title', __('site.about_title'))

@section('content')
<section>
  <div class="container">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.about_eye')</div>
      <h2>@lang('site.about_title')</h2>
      <span class="diamond">✦</span>
    </div>

    <div class="about-text">{!! nl2br(e(setting_l('about', __('site.about_text')))) !!}</div>

    <div class="stats">
      <div class="stat"><b class="gold-grad">{{ setting('stat_delivered', '250+') }}</b><span>@lang('site.stat1')</span></div>
      <div class="stat"><b class="gold-grad">{{ setting('stat_brands', '30+') }}</b><span>@lang('site.stat2')</span></div>
      <div class="stat"><b class="gold-grad">{{ setting('stat_years', '15') }}</b><span>@lang('site.stat3')</span></div>
    </div>

    <div style="text-align:center;margin-top:60px">
      <a href="{{ route('pages.contact') }}" class="btn solid">@lang('site.cta2')</a>
    </div>
  </div>
</section>
@endsection
