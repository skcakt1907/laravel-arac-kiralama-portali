@extends('layouts.app')
@section('title', __('site.my_account'))

@section('content')
<section>
  <div class="container">
    <div class="sec-head"><div class="eyebrow">@lang('site.welcome'), {{ $user->name }}</div><h2>@lang('site.my_account')</h2><span class="diamond">✦</span></div>

    @if (session('ok'))<div class="flash">{{ session('ok') }}</div>@endif

    <div class="contact-grid" style="align-items:start">
      {{-- Profil --}}
      <div>
        <h3 class="serif" style="color:var(--champ);font-size:22px;margin-bottom:20px">@lang('site.profile_info')</h3>
        <form method="POST" action="{{ route('member.profile') }}">
          @csrf @method('PUT')
          <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="@lang('site.name')" required>
          @error('name')<div class="field-err">{{ $message }}</div>@enderror
          <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="@lang('site.ph_email')" required>
          @error('email')<div class="field-err">{{ $message }}</div>@enderror
          <button class="btn solid" style="border:none;cursor:pointer">@lang('site.save')</button>
        </form>
      </div>

      {{-- Siparişler --}}
      <div>
        <h3 class="serif" style="color:var(--champ);font-size:22px;margin-bottom:20px">@lang('site.my_orders')</h3>
        @if ($orders->count())
          <div class="part-items">
            @foreach ($orders as $o)
              <div class="part">
                <div>
                  <div class="n">{{ $o->order_no }}</div>
                  <div class="c">{{ $o->created_at->format('d.m.Y') }} · @lang('site.status_' . $o->status)</div>
                </div>
                <div class="p">{{ $o->total_formatted }}</div>
              </div>
            @endforeach
          </div>
        @else
          <p class="muted" style="color:var(--muted)">@lang('site.no_orders')</p>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection
