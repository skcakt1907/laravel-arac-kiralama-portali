@extends('layouts.app')
@section('title', __('site.sell_title'))

@section('content')
<section>
  <div class="container" style="max-width:720px">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.sell_eye')</div>
      <h2>@lang('site.sell_title')</h2>
      <p>@lang('site.sell_sub')</p>
      <span class="diamond">✦</span>
    </div>

    @if (session('sent_ok'))
      <div class="flash">@lang('site.sell_sent')</div>
    @endif

    <form method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data">
      @csrf
      <input type="text" name="name" value="{{ old('name') }}" placeholder="@lang('site.ph_name')" required>
      @error('name')<div class="field-err">{{ $message }}</div>@enderror
      <input type="email" name="email" value="{{ old('email') }}" placeholder="@lang('site.ph_email')" required>
      @error('email')<div class="field-err">{{ $message }}</div>@enderror
      <input type="text" name="phone" value="{{ old('phone') }}" placeholder="@lang('site.ph_phone')" required>
      @error('phone')<div class="field-err">{{ $message }}</div>@enderror
      <div style="display:flex;gap:16px">
        <input type="text" name="brand" value="{{ old('brand') }}" placeholder="@lang('site.ph_brand')">
        <input type="text" name="model" value="{{ old('model') }}" placeholder="@lang('site.ph_model')">
      </div>
      <div style="display:flex;gap:16px">
        <input type="number" name="year" value="{{ old('year') }}" placeholder="@lang('site.ph_year')">
        <input type="number" name="mileage_km" value="{{ old('mileage_km') }}" placeholder="@lang('site.ph_km')">
      </div>
      <textarea name="message" placeholder="@lang('site.ph_msg')">{{ old('message') }}</textarea>
      <label style="color:var(--muted);font-size:13px;letter-spacing:.05em">@lang('site.ph_photo')</label>
      <input type="file" name="photo" accept="image/*">
      @error('photo')<div class="field-err">{{ $message }}</div>@enderror
      <button class="btn solid" style="border:none;cursor:pointer">@lang('site.sell_btn')</button>
    </form>
  </div>
</section>
@endsection
