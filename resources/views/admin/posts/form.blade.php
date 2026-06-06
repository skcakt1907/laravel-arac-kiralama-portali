@extends('admin.layout')
@section('title', $post->exists ? 'Yazı Düzenle' : 'Yeni Yazı')

@section('content')
<form class="form" method="POST"
      action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
      enctype="multipart/form-data" style="max-width:980px">
  @csrf
  @if ($post->exists) @method('PUT') @endif

  <div class="tabs">
    <button type="button" class="tab-btn active" data-tab="tr">Türkçe</button>
    <button type="button" class="tab-btn" data-tab="en">English</button>
    <button type="button" class="tab-btn" data-tab="ar">العربية</button>
  </div>

  @foreach (['tr' => ['Türkçe', 'ltr'], 'en' => ['English', 'ltr'], 'ar' => ['العربية', 'rtl']] as $lc => $meta)
    <div class="tab-panel {{ $lc === 'tr' ? 'active' : '' }}" data-panel="{{ $lc }}">
      <div class="field"><label>Başlık ({{ $meta[0] }})</label><input name="title_{{ $lc }}" dir="{{ $meta[1] }}" value="{{ old('title_' . $lc, $post->{'title_' . $lc}) }}"></div>
      <div class="field"><label>Özet ({{ $meta[0] }})</label><textarea name="excerpt_{{ $lc }}" dir="{{ $meta[1] }}" style="min-height:70px">{{ old('excerpt_' . $lc, $post->{'excerpt_' . $lc}) }}</textarea></div>
      <div class="field"><label>İçerik ({{ $meta[0] }})</label><textarea name="body_{{ $lc }}" dir="{{ $meta[1] }}" style="min-height:240px">{{ old('body_' . $lc, $post->{'body_' . $lc}) }}</textarea></div>
    </div>
  @endforeach

  <hr style="border-color:var(--line);margin:20px 0">

  <div class="grid">
    <div class="field">
      <label>Kapak Görseli</label>
      <input type="file" name="image" accept="image/*">
      @if ($post->has_image)<img class="thumb" style="width:120px;height:90px;margin-top:10px" src="{{ $post->image_url }}" alt="">@endif
    </div>
    <div class="field">
      <label>Yayın Tarihi</label>
      <input type="date" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d')) }}">
      <div class="check" style="margin-top:12px"><input type="checkbox" name="is_published" value="1" id="pub" {{ old('is_published', $post->is_published ?? true) ? 'checked' : '' }}><label for="pub" style="margin:0;text-transform:none">Yayında</label></div>
    </div>
  </div>

  <div class="btn-row" style="margin-top:20px">
    <button class="btn solid">{{ $post->exists ? 'Güncelle' : 'Kaydet' }}</button>
    <a class="btn" href="{{ route('admin.posts.index') }}">Vazgeç</a>
  </div>
</form>

<script>
  document.querySelectorAll('.tab-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var tab = btn.dataset.tab;
      document.querySelectorAll('.tab-btn').forEach(function (b) { b.classList.toggle('active', b === btn); });
      document.querySelectorAll('.tab-panel').forEach(function (p) { p.classList.toggle('active', p.dataset.panel === tab); });
    });
  });
</script>
@endsection
