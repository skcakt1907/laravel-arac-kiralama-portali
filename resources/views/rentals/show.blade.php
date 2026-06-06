@extends('layouts.app')
@section('title', $rental->brand . ' ' . $rental->model)
@if ($rental->has_image)@section('og_image', $rental->image_url)@endif

@section('content')
<section>
  <div class="container">
    @if (session('sent_ok'))<div class="flash">@lang('site.sent_ok')</div>@endif

    <div class="sec-head" style="margin-bottom:40px">
      <div class="eyebrow">{{ strtoupper($rental->brand) }}</div>
      <h2>{{ $rental->model }}</h2>
      <span class="diamond">✦</span>
    </div>

    <div class="contact-grid" style="align-items:start">
      <div>
        @if ($rental->has_image)
          <img class="car-thumb" src="{{ $rental->image_url }}" alt="{{ $rental->brand }} {{ $rental->model }}">
        @else
          <div class="car-card" style="padding:60px;text-align:center"><div class="poa">@lang('site.images_soon')</div></div>
        @endif
      </div>

      <div>
        <div class="stat" style="text-align:start;margin-bottom:22px">
          <b class="gold-grad" style="font-size:38px">{{ $rental->daily_price_formatted }}</b>
          <span style="display:block;color:var(--muted);font-size:12px;letter-spacing:.2em">/ @lang('site.day')</span>
        </div>

        <div class="part-items" style="margin-bottom:28px">
          @foreach ([
            'spec_year'         => $rental->year,
            'spec_engine'       => $rental->engine,
            'spec_transmission' => $rental->transmission,
            'seats'             => $rental->seats,
            'spec_body'         => $rental->body_type,
          ] as $key => $val)
            @if ($val)
              <div class="part"><div class="c" style="margin:0">@lang('site.' . $key)</div><div class="n">{{ $val }}</div></div>
            @endif
          @endforeach
        </div>

        @if ($rental->description)
          <p style="color:var(--muted);line-height:1.9;margin-bottom:28px">{{ $rental->description }}</p>
        @endif

        <form method="POST" action="{{ route('contact.send') }}">
          @csrf
          <input type="hidden" name="vehicle" value="[KİRALAMA] {{ $rental->brand }} {{ $rental->model }}">
          <input type="text" name="name" value="{{ old('name') }}" placeholder="@lang('site.ph_name')" required>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="@lang('site.ph_email')" required>
          <input type="text" name="phone" value="{{ old('phone') }}" placeholder="@lang('site.ph_phone')">
          <textarea name="message" placeholder="@lang('site.ph_msg')" required>{{ old('message', __('site.rent_prefill', ['car' => $rental->brand . ' ' . $rental->model])) }}</textarea>
          <button class="btn solid" style="border:none;cursor:pointer">@lang('site.rent_request')</button>
        </form>
      </div>
    </div>

    @if ($related->count())
      <div class="sec-head" style="margin:90px 0 40px"><h2 style="font-size:32px">@lang('site.rental_title')</h2></div>
      <div class="cars">
        @foreach ($related as $rental)
          <a class="car-card" href="{{ route('rentals.show', $rental) }}" style="text-decoration:none;color:inherit;display:block">
            <div class="car-brand">{{ strtoupper($rental->brand) }}</div>
            <div class="car-model">{{ $rental->model }}</div>
            @if ($rental->has_image)<img class="car-thumb" src="{{ $rental->image_url }}" alt="">@endif
            <div class="car-foot"><span class="poa">{{ $rental->daily_price_formatted }} / @lang('site.day')</span><span class="mini-btn">@lang('site.rent_now')</span></div>
          </a>
        @endforeach
      </div>
    @endif
  </div>
</section>
@endsection
