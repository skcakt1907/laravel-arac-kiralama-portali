@extends('layouts.app')
@section('title', __('site.blog_title'))

@section('content')
<section>
  <div class="container">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.blog_eye')</div>
      <h2>@lang('site.blog_title')</h2>
      <span class="diamond">✦</span>
    </div>

    @if ($posts->count())
      <div class="cars">
        @foreach ($posts as $post)
          <a class="car-card" href="{{ route('blog.show', $post) }}" style="text-decoration:none;color:inherit;display:block">
            @if ($post->has_image)
              <img class="car-thumb" src="{{ $post->image_url }}" alt="{{ $post->title }}" loading="lazy">
            @endif
            <div class="car-brand">{{ optional($post->published_at)->format('d.m.Y') }}</div>
            <div class="car-model" style="font-size:21px">{{ $post->title }}</div>
            @if ($post->excerpt)<p class="car-specs" style="letter-spacing:.02em;line-height:1.7">{{ \Illuminate\Support\Str::limit($post->excerpt, 110) }}</p>@endif
            <div class="car-foot"><span class="mini-btn">@lang('site.read_more')</span></div>
          </a>
        @endforeach
      </div>
      <div style="margin-top:48px;display:flex;justify-content:center">{{ $posts->links() }}</div>
    @else
      <p class="about-text">@lang('site.no_posts')</p>
    @endif
  </div>
</section>
@endsection
