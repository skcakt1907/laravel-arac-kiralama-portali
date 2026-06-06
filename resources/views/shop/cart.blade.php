@extends('layouts.app')

@section('title', __('site.cart_title'))

@section('content')
<section>
  <div class="container">
    <div class="sec-head" style="margin-bottom:40px">
      <div class="eyebrow">@lang('site.parts_eye')</div>
      <h2>@lang('site.cart_title')</h2>
    </div>

    @if ($items->isEmpty())
      <p class="about-text" style="margin-bottom:30px">@lang('site.cart_empty')</p>
      <div style="text-align:center"><a href="{{ route('shop.index') }}" class="btn ghost">@lang('site.continue_shop')</a></div>
    @else
      <div class="part-items" style="max-width:820px;margin:0 auto 30px">
        @foreach ($items as $row)
          @php($part = $row['part'])
          <div class="part" style="gap:18px">
            <div style="flex:1">
              <div class="n">{{ $part->name }}</div>
              <div class="c">{{ $part->compatible }} · {{ $part->price_formatted }}</div>
            </div>
            <form method="POST" action="{{ route('cart.update', $part) }}" style="display:flex;gap:8px;align-items:center;margin:0">
              @csrf @method('PATCH')
              <input type="number" name="qty" value="{{ $row['qty'] }}" min="0" style="width:74px">
              <button class="mini-btn" style="border:none;cursor:pointer">@lang('site.update')</button>
            </form>
            <div class="p" style="min-width:90px;text-align:end">{{ \App\Models\Part::formatMoney($row['line_total'], $part->currency) }}</div>
            <form method="POST" action="{{ route('cart.remove', $part) }}" style="margin:0">
              @csrf @method('DELETE')
              <button class="poa" style="background:none;border:none;color:var(--muted);cursor:pointer">✕</button>
            </form>
          </div>
        @endforeach
      </div>

      <div style="max-width:820px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;border-top:1px solid var(--line);padding-top:24px">
        <a href="{{ route('shop.index') }}" class="btn ghost">@lang('site.continue_shop')</a>
        <div style="text-align:end">
          <div class="car-specs" style="margin:0">@lang('site.subtotal'): <b style="color:var(--gold-l)">{{ \App\Models\Part::formatMoney($subtotal, $currency) }}</b></div>
          <a href="{{ route('checkout.form') }}" class="btn solid" style="margin-top:14px">@lang('site.checkout')</a>
        </div>
      </div>
    @endif
  </div>
</section>
@endsection
