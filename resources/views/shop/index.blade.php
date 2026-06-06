@extends('layouts.app')

@section('title', __('site.shop_title'))

@section('content')
<section>
  <div class="container">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.parts_eye')</div>
      <h2>@lang('site.shop_title')</h2>
      <p>@lang('site.shop_sub')</p>
      <span class="diamond">✦</span>
    </div>

    @if (session('sent_ok') === 'added')
      <div class="flash">@lang('site.item_added')</div>
    @endif

    {{-- Kategori filtresi --}}
    @if ($categories->count())
      <div class="lang" style="justify-content:center;flex-wrap:wrap;gap:8px;margin-bottom:48px">
        <a href="{{ route('shop.index') }}" class="{{ $activeCategory ? '' : 'active' }}">@lang('site.all')</a>
        @foreach ($categories as $cat)
          <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="{{ $activeCategory === $cat->slug ? 'active' : '' }}">{{ $cat->name }}</a>
        @endforeach
      </div>
    @endif

    @if ($parts->count())
      <div class="cars">
        @foreach ($parts as $part)
          <div class="car-card">
            <a href="{{ route('shop.show', $part) }}" style="text-decoration:none;color:inherit;display:block">
              @if ($part->cover_image)
                <img class="car-thumb" src="{{ asset('storage/' . $part->cover_image) }}" alt="{{ $part->name }}" loading="lazy">
              @endif
              @if ($part->compatible)<div class="car-brand">{{ strtoupper($part->compatible) }}</div>@endif
              <div class="car-model" style="font-size:21px">{{ $part->name }}</div>
            </a>
            <div class="car-foot">
              <span class="p" style="font-family:'Marcellus',serif;color:var(--gold-l);font-size:19px">{{ $part->price_formatted }}</span>
              @if ($part->in_stock)
                <form method="POST" action="{{ route('cart.add', $part) }}">@csrf
                  <button class="mini-btn" style="border:none;cursor:pointer">@lang('site.add_to_cart')</button>
                </form>
              @else
                <span class="poa" style="color:var(--muted)">@lang('site.out_of_stock')</span>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <div style="margin-top:48px;display:flex;justify-content:center">{{ $parts->links() }}</div>
    @else
      <p class="about-text">@lang('site.no_parts')</p>
    @endif
  </div>
</section>
@endsection
