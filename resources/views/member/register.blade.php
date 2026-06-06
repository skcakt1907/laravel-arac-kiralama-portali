@extends('layouts.app')
@section('title', __('site.register_title'))

@section('content')
<section>
  <div class="container" style="max-width:440px">
    <div class="sec-head"><h2>@lang('site.register_title')</h2><span class="diamond">✦</span></div>

    @if ($errors->any())
      <div class="flash" style="border-color:#9c3b3b;color:#e0a0a0;background:rgba(156,59,59,.1)">
        @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('member.register.post') }}">
      @csrf
      <input type="text" name="name" value="{{ old('name') }}" placeholder="@lang('site.name')" required autofocus>
      <input type="email" name="email" value="{{ old('email') }}" placeholder="@lang('site.ph_email')" required>
      <input type="password" name="password" placeholder="@lang('site.password')" required>
      <input type="password" name="password_confirmation" placeholder="@lang('site.password_again')" required>
      <button class="btn solid" style="border:none;cursor:pointer">@lang('site.register')</button>
    </form>

    <p style="text-align:center;margin-top:22px"><a href="{{ route('member.login') }}" style="color:var(--gold-l)">@lang('site.have_account')</a></p>
  </div>
</section>
@endsection
