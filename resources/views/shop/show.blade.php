@extends('layouts.app')

@section('title', $part->name)

@section('content')
<section>
  <div class="container">
    <div class="sec-head" style="margin-bottom:40px">
      @if ($part->compatible)<div class="eyebrow">{{ strtoupper($part->compatible) }}</div>@endif
      <h2>{{ $part->name }}</h2>
      <span class="diamond">✦</span>
    </div>

    <div class="contact-grid" style="align-items:start">
      <div>
        @if ($part->cover_image)
          <img class="car-thumb" style="height:auto;aspect-ratio:4/3" src="{{ asset('storage/' . $part->cover_image) }}" alt="{{ $part->name }}">
        @else
          <div class="car-card" style="padding:60px;text-align:center">
            <div class="poa" style="font-size:13px">@lang('site.images_soon')</div>
          </div>
        @endif
      </div>

      <div>
        <div class="stat" style="text-align:start;margin-bottom:24px">
          <b class="gold-grad" style="font-size:40px">{{ $part->price_formatted }}</b>
        </div>

        <div class="part-items" style="margin-bottom:28px">
          @if ($part->sku)
            <div class="part"><div class="c" style="margin:0">@lang('site.sku')</div><div class="n">{{ $part->sku }}</div></div>
          @endif
          @if ($part->compatible)
            <div class="part"><div class="c" style="margin:0">@lang('site.compatible')</div><div class="n">{{ $part->compatible }}</div></div>
          @endif
          <div class="part">
            <div class="c" style="margin:0">{{ __('site.in_stock') }}</div>
            <div class="n">{{ $part->in_stock ? $part->stock : __('site.out_of_stock') }}</div>
          </div>
        </div>

        @if ($part->description)
          <p style="color:var(--muted);line-height:1.9;margin-bottom:28px">{{ $part->description }}</p>
        @endif

        @if ($part->in_stock)
          <form method="POST" action="{{ route('cart.add', $part) }}" style="display:flex;gap:14px;align-items:center">
            @csrf
            <input type="number" name="qty" value="1" min="1" max="{{ $part->stock }}" style="width:90px">
            <button class="btn solid" style="border:none;cursor:pointer">@lang('site.add_to_cart')</button>
          </form>
        @else
          <div class="poa" style="color:var(--muted)">@lang('site.out_of_stock')</div>
        @endif
      </div>
    </div>

    @if ($related->count())
      <div class="sec-head" style="margin:90px 0 40px"><h2 style="font-size:32px">@lang('site.related_parts')</h2></div>
      <div class="cars">
        @foreach ($related as $part)
          <a class="car-card" href="{{ route('shop.show', $part) }}" style="text-decoration:none;color:inherit;display:block">
            <div class="car-model" style="font-size:20px">{{ $part->name }}</div>
            <div class="car-foot"><span class="poa">{{ $part->price_formatted }}</span><span class="mini-btn">@lang('site.view_detail')</span></div>
          </a>
        @endforeach
      </div>
    @endif
  </div>
</section>
@endsection
