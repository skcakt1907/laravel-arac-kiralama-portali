@extends('layouts.app')

@section('title', __('site.nav_collection'))

@section('content')
<section>
  <div class="container">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.col_eye')</div>
      <h2>@lang('site.nav_collection')</h2>
      <p>@lang('site.col_sub')</p>
      <span class="diamond">✦</span>
    </div>

    {{-- Marka filtresi --}}
    <div class="lang" style="justify-content:center;flex-wrap:wrap;gap:8px;margin-bottom:48px">
      <a href="{{ route('vehicles.index') }}" class="{{ $activeBrand ? '' : 'active' }}">@lang('site.all')</a>
      @foreach ($brands as $brand)
        <a href="{{ route('vehicles.index', ['brand' => $brand]) }}" class="{{ $activeBrand === $brand ? 'active' : '' }}">{{ $brand }}</a>
      @endforeach
    </div>

    @if ($vehicles->count())
      <div class="cars">
        @foreach ($vehicles as $vehicle)
          @include('partials.vehicle-card', ['vehicle' => $vehicle])
        @endforeach
      </div>

      <div style="margin-top:48px;display:flex;justify-content:center">
        {{ $vehicles->links() }}
      </div>
    @else
      <p class="about-text">@lang('site.no_vehicles')</p>
    @endif
  </div>
</section>
@endsection
