@extends('admin.layout')
@section('title', $rental->exists ? 'Kiralık Araç Düzenle' : 'Yeni Kiralık Araç')

@section('content')
<form class="form" method="POST"
      action="{{ $rental->exists ? route('admin.rentals.update', $rental) : route('admin.rentals.store') }}"
      enctype="multipart/form-data">
  @csrf
  @if ($rental->exists) @method('PUT') @endif

  <div class="grid">
    <div class="field"><label>Marka *</label><input name="brand" value="{{ old('brand', $rental->brand) }}" required></div>
    <div class="field"><label>Model *</label><input name="model" value="{{ old('model', $rental->model) }}" required></div>
    <div class="field"><label>Yıl</label><input type="number" name="year" value="{{ old('year', $rental->year) }}"></div>
    <div class="field"><label>Günlük Fiyat *</label><input type="number" step="0.01" name="daily_price" value="{{ old('daily_price', $rental->daily_price ?? 0) }}" required></div>
    <div class="field">
      <label>Para Birimi *</label>
      <select name="currency">
        @foreach (['AED','USD','EUR','TRY','GBP'] as $cur)
          <option value="{{ $cur }}" {{ old('currency', $rental->currency ?? 'AED') === $cur ? 'selected' : '' }}>{{ $cur }}</option>
        @endforeach
      </select>
    </div>
    <div class="field"><label>Koltuk Sayısı</label><input type="number" name="seats" value="{{ old('seats', $rental->seats) }}"></div>
    <div class="field"><label>Motor</label><input name="engine" value="{{ old('engine', $rental->engine) }}"></div>
    <div class="field"><label>Vites</label><input name="transmission" value="{{ old('transmission', $rental->transmission) }}"></div>
    <div class="field"><label>Kasa Tipi</label><input name="body_type" value="{{ old('body_type', $rental->body_type) }}"></div>

    <div class="field full"><label>Açıklama</label><textarea name="description">{{ old('description', $rental->description) }}</textarea></div>

    <div class="field">
      <label>Görsel</label>
      <input type="file" name="image" accept="image/*">
      @if ($rental->has_image)<img class="thumb" style="width:120px;height:90px;margin-top:10px" src="{{ $rental->image_url }}" alt="">@endif
    </div>
    <div class="field">
      <label>Durum</label>
      <div class="check" style="margin-bottom:10px"><input type="checkbox" name="is_published" value="1" id="pub" {{ old('is_published', $rental->is_published ?? true) ? 'checked' : '' }}><label for="pub" style="margin:0;text-transform:none">Yayında</label></div>
      <div class="check"><input type="checkbox" name="is_featured" value="1" id="feat" {{ old('is_featured', $rental->is_featured ?? false) ? 'checked' : '' }}><label for="feat" style="margin:0;text-transform:none">Öne çıkan</label></div>
    </div>
  </div>

  <div class="btn-row" style="margin-top:20px">
    <button class="btn solid">{{ $rental->exists ? 'Güncelle' : 'Kaydet' }}</button>
    <a class="btn" href="{{ route('admin.rentals.index') }}">Vazgeç</a>
  </div>
</form>
@endsection
