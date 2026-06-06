@extends('layouts.app')
@section('title', $post->title)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? $post->body ?? ''), 160))
@if ($post->has_image)@section('og_image', $post->image_url)@endif

@section('content')
<section>
  <div class="container" style="max-width:840px">
    <div class="sec-head" style="margin-bottom:30px">
      <div class="eyebrow">{{ optional($post->published_at)->format('d.m.Y') }}</div>
      <h2>{{ $post->title }}</h2>
      <span class="diamond">✦</span>
    </div>

    @if ($post->has_image)
      <img class="car-thumb" style="aspect-ratio:16/9;margin-bottom:30px" src="{{ $post->image_url }}" alt="{{ $post->title }}">
    @endif

    <div style="color:var(--text);line-height:2;font-size:16px">
      {!! nl2br(e($post->body)) !!}
    </div>

    <div style="text-align:center;margin-top:50px">
      <a href="{{ route('blog.index') }}" class="btn ghost">← @lang('site.blog_title')</a>
    </div>

    @if ($recent->count())
      <div class="sec-head" style="margin:80px 0 36px"><h2 style="font-size:30px">@lang('site.recent_posts')</h2></div>
      <div class="cars">
        @foreach ($recent as $post)
          <a class="car-card" href="{{ route('blog.show', $post) }}" style="text-decoration:none;color:inherit;display:block">
            @if ($post->has_image)<img class="car-thumb" src="{{ $post->image_url }}" alt="">@endif
            <div class="car-model" style="font-size:19px">{{ $post->title }}</div>
            <div class="car-foot"><span class="mini-btn">@lang('site.read_more')</span></div>
          </a>
        @endforeach
      </div>
    @endif
  </div>
</section>
@endsection
