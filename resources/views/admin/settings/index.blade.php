@extends('admin.layout')
@section('title', 'Site Ayarları')

@section('content')
<form class="form" method="POST" action="{{ route('admin.settings.update') }}" style="max-width:980px">
  @csrf @method('PUT')

  {{-- Dile bağlı içerik: TR / EN / AR sekmeleri --}}
  <h3 style="color:var(--gold-l);font-size:15px;margin-bottom:16px">İçerik (Dile Göre)</h3>

  <div class="tabs">
    <button type="button" class="tab-btn active" data-tab="tr">Türkçe</button>
    <button type="button" class="tab-btn" data-tab="en">English</button>
    <button type="button" class="tab-btn" data-tab="ar">العربية</button>
  </div>

  @foreach (['tr' => ['Türkçe', 'ltr'], 'en' => ['English', 'ltr'], 'ar' => ['العربية', 'rtl']] as $lc => $meta)
    <div class="tab-panel {{ $lc === 'tr' ? 'active' : '' }}" data-panel="{{ $lc }}">
      <div class="field full">
        <label>Hakkımızda Metni ({{ $meta[0] }})</label>
        <textarea name="about_{{ $lc }}" dir="{{ $meta[1] }}">{{ old('about_' . $lc, $s['about_' . $lc] ?? '') }}</textarea>
      </div>
      <div class="grid">
        <div class="field">
          <label>Adres ({{ $meta[0] }})</label>
          <input name="address_{{ $lc }}" dir="{{ $meta[1] }}" value="{{ old('address_' . $lc, $s['address_' . $lc] ?? '') }}">
        </div>
        <div class="field">
          <label>Çalışma Saatleri ({{ $meta[0] }})</label>
          <input name="hours_{{ $lc }}" dir="{{ $meta[1] }}" value="{{ old('hours_' . $lc, $s['hours_' . $lc] ?? '') }}">
        </div>
      </div>
    </div>
  @endforeach

  <hr style="border-color:var(--line);margin:24px 0">

  {{-- Dile bağlı olmayan: iletişim --}}
  <h3 style="color:var(--gold-l);font-size:15px;margin-bottom:16px">İletişim & Sosyal Medya</h3>
  <div class="grid">
    <div class="field"><label>Telefon</label><input name="phone" value="{{ old('phone', $s['phone'] ?? '') }}"></div>
    <div class="field"><label>E-posta</label><input name="email" value="{{ old('email', $s['email'] ?? '') }}"></div>
    <div class="field"><label>WhatsApp</label><input name="whatsapp" value="{{ old('whatsapp', $s['whatsapp'] ?? '') }}" placeholder="+9715..."></div>
    <div class="field"><label>Instagram (URL)</label><input name="instagram" value="{{ old('instagram', $s['instagram'] ?? '') }}"></div>
    <div class="field"><label>Facebook (URL)</label><input name="facebook" value="{{ old('facebook', $s['facebook'] ?? '') }}"></div>
    <div class="field"><label>X / Twitter (URL)</label><input name="twitter" value="{{ old('twitter', $s['twitter'] ?? '') }}"></div>
  </div>
  <div class="field full"><label>Google Harita Embed Kodu (iframe)</label><textarea name="map_embed" placeholder="<iframe ...></iframe>">{{ old('map_embed', $s['map_embed'] ?? '') }}</textarea></div>

  <hr style="border-color:var(--line);margin:24px 0">

  {{-- İstatistikler --}}
  <h3 style="color:var(--gold-l);font-size:15px;margin-bottom:16px">Ana Sayfa İstatistikleri</h3>
  <div class="grid">
    <div class="field"><label>Teslim Edilen Araç</label><input name="stat_delivered" value="{{ old('stat_delivered', $s['stat_delivered'] ?? '') }}"></div>
    <div class="field"><label>Prestijli Marka</label><input name="stat_brands" value="{{ old('stat_brands', $s['stat_brands'] ?? '') }}"></div>
    <div class="field"><label>Yıllık Tecrübe</label><input name="stat_years" value="{{ old('stat_years', $s['stat_years'] ?? '') }}"></div>
  </div>

  <div class="btn-row" style="margin-top:22px">
    <button class="btn solid">Kaydet</button>
    <a class="btn" href="{{ url('/#hakkimizda') }}" target="_blank">Sitede Gör</a>
  </div>
</form>

<script>
  document.querySelectorAll('.tab-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var tab = btn.dataset.tab;
      document.querySelectorAll('.tab-btn').forEach(function (b) { b.classList.toggle('active', b === btn); });
      document.querySelectorAll('.tab-panel').forEach(function (p) {
        p.classList.toggle('active', p.dataset.panel === tab);
      });
    });
  });
</script>
@endsection
