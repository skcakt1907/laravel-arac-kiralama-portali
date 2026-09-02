@extends('layouts.app')

@section('title', $vehicle->brand . ' ' . $vehicle->model)
@section('meta_description', \Illuminate\Support\Str::limit($vehicle->description ?: ($vehicle->brand . ' ' . $vehicle->model . ' · ' . $vehicle->specs_line), 160))
@if ($vehicle->has_image)@section('og_image', $vehicle->image_url)@endif

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Car',
    'name' => $vehicle->brand . ' ' . $vehicle->model,
    'brand' => ['@type' => 'Brand', 'name' => $vehicle->brand],
    'model' => $vehicle->model,
    'vehicleModelDate' => $vehicle->year,
    'mileageFromOdometer' => $vehicle->mileage_km ? ['@type' => 'QuantitativeValue', 'value' => $vehicle->mileage_km, 'unitCode' => 'KMT'] : null,
    'color' => $vehicle->color,
    'image' => $vehicle->image_url,
    'url' => url()->current(),
], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
</script>
@endpush

@section('content')
<section>
  <div class="container">

    @if (session('sent_ok'))
      <div class="flash">@lang('site.sent_ok')</div>
    @endif
    @if (session('compare_msg'))
      <div class="flash">{{ session('compare_msg') }}</div>
    @endif

    <div class="sec-head" style="margin-bottom:40px">
      <div class="eyebrow">{{ strtoupper($vehicle->brand) }}</div>
      <h2>{{ $vehicle->model }}</h2>
      @if ($vehicle->is_sold)<div style="margin-top:10px"><span class="sold-badge" style="position:static;display:inline-block">@lang('site.sold')</span></div>@endif
      <span class="diamond">✦</span>
      <div style="margin-top:18px">
        <form method="POST" action="{{ route('compare.add', $vehicle) }}" style="display:inline">
          @csrf
          <button class="btn ghost" style="cursor:pointer">@lang('site.compare_add')</button>
        </form>
        <a href="{{ route('compare.index') }}" class="btn ghost">@lang('site.compare')</a>
      </div>
    </div>

    <div class="contact-grid" style="align-items:start">
      {{-- Sol: görsel / galeri --}}
      <div>
        @if ($vehicle->has_image)
          <img class="car-thumb" src="{{ $vehicle->image_url }}" alt="{{ $vehicle->brand }} {{ $vehicle->model }}">
        @else
          <div class="car-card" style="padding:40px">
            <svg viewBox="0 0 600 190" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="gfShow" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#e9d6a0" stop-opacity=".85"/><stop offset="1" stop-color="#7c6228" stop-opacity=".55"/></linearGradient></defs><path fill="url(#gfShow)" d="M25 132 C60 110 100 96 150 88 C210 70 290 62 360 70 C420 76 480 92 535 110 C555 116 560 124 552 134 L540 138 L505 138 A38 38 0 0 0 429 138 L215 138 A38 38 0 0 0 139 138 L45 138 C30 138 20 136 25 132 Z"/><circle cx="177" cy="138" r="27" fill="#0b0a08" stroke="#c8a44d" stroke-width="2"/><circle cx="177" cy="138" r="10" fill="none" stroke="#c8a44d" stroke-width="1.4"/><circle cx="467" cy="138" r="27" fill="#0b0a08" stroke="#c8a44d" stroke-width="2"/><circle cx="467" cy="138" r="10" fill="none" stroke="#c8a44d" stroke-width="1.4"/></svg>
            <p class="car-specs" style="text-align:center;margin:14px 0 0">@lang('site.images_soon')</p>
          </div>
        @endif

        @if ($vehicle->images->count())
          <div class="cars" style="grid-template-columns:repeat(4,1fr);gap:10px;margin-top:12px">
            @foreach ($vehicle->images as $img)
              <img class="car-thumb" style="height:90px" src="{{ $img->url }}" alt="">
            @endforeach
          </div>
        @endif
      </div>

      {{-- Sağ: teknik + iletişim --}}
      <div>
        <h3 class="serif" style="color:var(--champ);font-size:24px;letter-spacing:.08em;margin-bottom:22px">@lang('site.specs')</h3>
        <div class="part-items" style="margin-bottom:34px">
          @foreach ([
            'year'         => $vehicle->year,
            'mileage'      => $vehicle->mileage_km ? number_format($vehicle->mileage_km, 0, ',', '.') . ' km' : null,
            'engine'       => $vehicle->engine,
            'fuel'         => $vehicle->fuel,
            'transmission' => $vehicle->transmission,
            'body'         => $vehicle->body_type,
            'color'        => $vehicle->color,
          ] as $key => $val)
            @if ($val)
              <div class="part">
                <div class="c" style="margin:0">@lang('site.spec_' . $key)</div>
                <div class="n">{{ $val }}</div>
              </div>
            @endif
          @endforeach
        </div>

        @if ($vehicle->description)
          <p class="parts-grid" style="display:block;color:var(--muted);line-height:1.9;margin-bottom:30px">{{ $vehicle->description }}</p>
        @endif

        {{-- Fiyat YOK — sadece "Bize Ulaşın" --}}
        <div class="poa" style="display:block;margin-bottom:18px;font-size:13px">✦ @lang('site.poa')</div>

        <form method="POST" action="{{ route('contact.send') }}">
          @csrf
          <input type="hidden" name="vehicle" value="{{ $vehicle->brand }} {{ $vehicle->model }}">
          <input type="text" name="name" value="{{ old('name') }}" placeholder="@lang('site.ph_name')" required>
          @error('name')<div class="field-err">{{ $message }}</div>@enderror
          <input type="email" name="email" value="{{ old('email') }}" placeholder="@lang('site.ph_email')" required>
          @error('email')<div class="field-err">{{ $message }}</div>@enderror
          <input type="text" name="phone" value="{{ old('phone') }}" placeholder="@lang('site.ph_phone')">
          <textarea name="message" placeholder="@lang('site.ph_msg')" required>{{ old('message', __('site.inq_prefill', ['car' => $vehicle->brand . ' ' . $vehicle->model])) }}</textarea>
          @error('message')<div class="field-err">{{ $message }}</div>@enderror
          <button class="btn solid" style="border:none;cursor:pointer">@lang('site.cta2')</button>
        </form>
      </div>
    </div>

    {{-- Benzer araçlar --}}
    @if ($related->count())
      <div class="sec-head" style="margin:90px 0 40px">
        <div class="eyebrow">{{ strtoupper($vehicle->brand) }}</div>
        <h2 style="font-size:32px">@lang('site.related')</h2>
      </div>
      <div class="cars">
        @foreach ($related as $vehicle)
          @include('partials.vehicle-card', ['vehicle' => $vehicle])
        @endforeach
      </div>
    @endif

  </div>
</section>
@endsection
