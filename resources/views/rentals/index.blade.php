@extends('layouts.app')
@section('title', __('site.rental_title'))

@section('content')
<section>
  <div class="container">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.rental_eye')</div>
      <h2>@lang('site.rental_title')</h2>
      <p>@lang('site.rental_sub')</p>
      <span class="diamond">✦</span>
    </div>

    @if ($rentals->count())
      <div class="cars">
        @foreach ($rentals as $rental)
          <a class="car-card" href="{{ route('rentals.show', $rental) }}" style="text-decoration:none;color:inherit;display:block">
            <div class="car-brand">{{ strtoupper($rental->brand) }}</div>
            <div class="car-model">{{ $rental->model }}</div>
            @if ($rental->has_image)
              <img class="car-thumb" src="{{ $rental->image_url }}" alt="{{ $rental->brand }} {{ $rental->model }}" loading="lazy">
            @endif
            <div class="car-specs">
              {{ collect([$rental->year, $rental->transmission, $rental->seats ? $rental->seats . ' ' . __('site.seats') : null])->filter()->implode(' · ') }}
            </div>
            <div class="car-foot">
              <span class="poa">{{ $rental->daily_price_formatted }} <small style="color:var(--muted)">/ @lang('site.day')</small></span>
              <span class="mini-btn">@lang('site.rent_now')</span>
            </div>
          </a>
        @endforeach
      </div>
      <div style="margin-top:48px;display:flex;justify-content:center">{{ $rentals->links() }}</div>
    @else
      <p class="about-text">@lang('site.no_rentals')</p>
    @endif
  </div>
</section>
@endsection
