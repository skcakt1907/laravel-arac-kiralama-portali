{{-- Tek araç kartı. Beklenen: $vehicle (App\Models\Vehicle) --}}
<a class="car-card {{ $vehicle->is_sold ? 'is-sold' : '' }}" href="{{ route('vehicles.show', $vehicle) }}" style="text-decoration:none;color:inherit;display:block">
  @if ($vehicle->is_sold)<span class="sold-badge">@lang('site.sold')</span>@endif
  <div class="car-brand">{{ strtoupper($vehicle->brand) }}</div>
  <div class="car-model">{{ $vehicle->model }}</div>

  @if ($vehicle->has_image)
    <img class="car-thumb" src="{{ $vehicle->image_url }}" alt="{{ $vehicle->brand }} {{ $vehicle->model }}" loading="lazy">
  @else
    {{-- Görsel gelene kadar altın araç silüeti --}}
    <svg viewBox="0 0 600 190" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="gf{{ $vehicle->id }}" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#e9d6a0" stop-opacity=".85"/><stop offset="1" stop-color="#7c6228" stop-opacity=".55"/></linearGradient></defs><path fill="url(#gf{{ $vehicle->id }})" d="M25 132 C60 110 100 96 150 88 C210 70 290 62 360 70 C420 76 480 92 535 110 C555 116 560 124 552 134 L540 138 L505 138 A38 38 0 0 0 429 138 L215 138 A38 38 0 0 0 139 138 L45 138 C30 138 20 136 25 132 Z"/><circle cx="177" cy="138" r="27" fill="#0b0a08" stroke="#c8a44d" stroke-width="2"/><circle cx="177" cy="138" r="10" fill="none" stroke="#c8a44d" stroke-width="1.4"/><circle cx="467" cy="138" r="27" fill="#0b0a08" stroke="#c8a44d" stroke-width="2"/><circle cx="467" cy="138" r="10" fill="none" stroke="#c8a44d" stroke-width="1.4"/><path d="M30 172 L570 172" stroke="#c8a44d" stroke-width="1" opacity=".25"/></svg>
  @endif

  <div class="car-specs">{{ $vehicle->specs_line }}</div>
  <div class="car-foot">
    <span class="poa">✦ @lang('site.poa')</span>
    <span class="mini-btn">@lang('site.inq')</span>
  </div>
</a>
