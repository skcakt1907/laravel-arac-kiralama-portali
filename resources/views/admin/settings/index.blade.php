@extends('admin.layout')
@section('title', 'Site Ayarları')

@section('content')
<form class="form" method="POST" action="{{ route('admin.settings.update') }}" style="max-width:980px">
  @csrf @method('PUT')

  {{-- Hakkımızda --}}
  <h3 style="color:var(--gold-l);font-size:15px;margin-bottom:16px">Hakkımızda Metni</h3>
  <div class="grid">
    <div class="field full"><label>Türkçe</label><textarea name="about_tr">{{ old('about_tr', $s['about_tr'] ?? '') }}</textarea></div>
    <div class="field full"><label>English</label><textarea name="about_en">{{ old('about_en', $s['about_en'] ?? '') }}</textarea></div>
    <div class="field full"><label>العربية</label><textarea name="about_ar" dir="rtl">{{ old('about_ar', $s['about_ar'] ?? '') }}</textarea></div>
  </div>

  <hr style="border-color:var(--line);margin:24px 0">

  {{-- İletişim --}}
  <h3 style="color:var(--gold-l);font-size:15px;margin-bottom:16px">İletişim Bilgileri</h3>
  <div class="grid">
    <div class="field"><label>Telefon</label><input name="phone" value="{{ old('phone', $s['phone'] ?? '') }}"></div>
    <div class="field"><label>E-posta</label><input name="email" value="{{ old('email', $s['email'] ?? '') }}"></div>
    <div class="field"><label>WhatsApp</label><input name="whatsapp" value="{{ old('whatsapp', $s['whatsapp'] ?? '') }}" placeholder="+9715..."></div>
    <div class="field"><label>Instagram (URL)</label><input name="instagram" value="{{ old('instagram', $s['instagram'] ?? '') }}"></div>
    <div class="field"><label>Facebook (URL)</label><input name="facebook" value="{{ old('facebook', $s['facebook'] ?? '') }}"></div>
    <div class="field"><label>X / Twitter (URL)</label><input name="twitter" value="{{ old('twitter', $s['twitter'] ?? '') }}"></div>
  </div>

  <h4 style="color:var(--muted);font-size:12px;letter-spacing:.08em;margin:8px 0 12px;text-transform:uppercase">Adres (dile göre)</h4>
  <div class="grid">
    <div class="field"><label>Adres (TR)</label><input name="address_tr" value="{{ old('address_tr', $s['address_tr'] ?? '') }}"></div>
    <div class="field"><label>Adres (EN)</label><input name="address_en" value="{{ old('address_en', $s['address_en'] ?? '') }}"></div>
    <div class="field"><label>Adres (AR)</label><input name="address_ar" dir="rtl" value="{{ old('address_ar', $s['address_ar'] ?? '') }}"></div>
    <div class="field"><label>Çalışma Saatleri (TR)</label><input name="hours_tr" value="{{ old('hours_tr', $s['hours_tr'] ?? '') }}"></div>
    <div class="field"><label>Çalışma Saatleri (EN)</label><input name="hours_en" value="{{ old('hours_en', $s['hours_en'] ?? '') }}"></div>
    <div class="field"><label>Çalışma Saatleri (AR)</label><input name="hours_ar" dir="rtl" value="{{ old('hours_ar', $s['hours_ar'] ?? '') }}"></div>
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
@endsection
