@extends('layouts.app')
@section('title', __('site.login_title'))

@section('content')
<section>
  <div class="container" style="max-width:440px">
    <div class="sec-head"><h2>@lang('site.login_title')</h2><span class="diamond">✦</span></div>

    @if ($errors->any())
      <div class="flash" style="border-color:#9c3b3b;color:#e0a0a0;background:rgba(156,59,59,.1)">
        @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('member.login.post') }}">
      @csrf
      <input type="email" name="email" value="{{ old('email') }}" placeholder="@lang('site.ph_email')" required autofocus>
      <input type="password" name="password" placeholder="@lang('site.password')" required>
      <label style="display:flex;gap:8px;align-items:center;color:var(--muted);font-size:13px">
        <input type="checkbox" name="remember" value="1" style="width:auto"> @lang('site.remember_me')
      </label>
      <button class="btn solid" style="border:none;cursor:pointer">@lang('site.login')</button>
    </form>

    <p style="text-align:center;margin-top:22px"><a href="{{ route('member.register') }}" style="color:var(--gold-l)">@lang('site.no_account')</a></p>
  </div>
</section>
@endsection
