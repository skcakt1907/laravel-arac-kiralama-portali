@extends('layouts.app')
@section('title', $doc['title'])

@section('content')
<section>
  <div class="container" style="max-width:860px">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.legal')</div>
      <h2>{{ $doc['title'] }}</h2>
      <span class="diamond">✦</span>
    </div>

    <p class="policy-note">@lang('policy.updated'): {{ date('Y') }} · @lang('policy.intro_note')</p>

    <article class="policy">
      @foreach ($doc['sections'] as $s)
        <h3>{{ $s['h'] }}</h3>
        <p>{{ str_replace(':company', config('app.name'), $s['b']) }}</p>
      @endforeach
    </article>
  </div>
</section>
@endsection
