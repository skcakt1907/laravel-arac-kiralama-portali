@extends('layouts.app')

@section('title', __('site.checkout_title'))

@section('content')
<section>
  <div class="container">
    <div class="sec-head" style="margin-bottom:40px">
      <div class="eyebrow">@lang('site.parts_eye')</div>
      <h2>@lang('site.checkout_title')</h2>
    </div>

    <div class="contact-grid" style="align-items:start">
      {{-- Form --}}
      <form method="POST" action="{{ route('checkout.place') }}">
        @csrf
        <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="@lang('site.ph_name')" required>
        @error('customer_name')<div class="field-err">{{ $message }}</div>@enderror
        <input type="email" name="email" value="{{ old('email') }}" placeholder="@lang('site.ph_email')" required>
        @error('email')<div class="field-err">{{ $message }}</div>@enderror
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="@lang('site.ph_phone')" required>
        @error('phone')<div class="field-err">{{ $message }}</div>@enderror
        <div style="display:flex;gap:16px">
          <input type="text" name="country" value="{{ old('country') }}" placeholder="@lang('site.ph_country')">
          <input type="text" name="city" value="{{ old('city') }}" placeholder="@lang('site.ph_city')">
        </div>
        <textarea name="address" placeholder="@lang('site.ph_address')" required>{{ old('address') }}</textarea>
        @error('address')<div class="field-err">{{ $message }}</div>@enderror
        <textarea name="note" placeholder="@lang('site.ph_note')">{{ old('note') }}</textarea>
        <button class="btn solid" style="border:none;cursor:pointer">@lang('site.place_order')</button>
      </form>

      {{-- Özet --}}
      <div>
        <h3 class="serif" style="color:var(--champ);font-size:22px;margin-bottom:20px">@lang('site.order_summary')</h3>
        <div class="part-items">
          @foreach ($items as $row)
            <div class="part">
              <div class="n">{{ $row['part']->name }} <span class="c">× {{ $row['qty'] }}</span></div>
              <div class="p">{{ \App\Models\Part::formatMoney($row['line_total'], $currency) }}</div>
            </div>
          @endforeach
          <div class="part" style="border-color:var(--gold)">
            <div class="n">@lang('site.total')</div>
            <div class="p">{{ \App\Models\Part::formatMoney($subtotal, $currency) }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
