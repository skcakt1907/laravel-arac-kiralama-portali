@extends('admin.layout')
@section('title', $vehicle->exists ? 'Araç Düzenle' : 'Yeni Araç')

@section('content')
<form class="form" method="POST"
      action="{{ $vehicle->exists ? route('admin.vehicles.update', $vehicle) : route('admin.vehicles.store') }}"
      enctype="multipart/form-data">
  @csrf
  @if ($vehicle->exists) @method('PUT') @endif

  <div class="grid">
    <div class="field"><label>Marka *</label><input name="brand" value="{{ old('brand', $vehicle->brand) }}" required></div>
    <div class="field"><label>Model *</label><input name="model" value="{{ old('model', $vehicle->model) }}" required></div>
    <div class="field"><label>Model Yılı</label><input type="number" name="year" value="{{ old('year', $vehicle->year) }}"></div>
    <div class="field"><label>Kilometre</label><input type="number" name="mileage_km" value="{{ old('mileage_km', $vehicle->mileage_km) }}"></div>
    <div class="field"><label>Motor</label><input name="engine" value="{{ old('engine', $vehicle->engine) }}"></div>
    <div class="field"><label>Yakıt</label><input name="fuel" value="{{ old('fuel', $vehicle->fuel) }}"></div>
    <div class="field"><label>Vites</label><input name="transmission" value="{{ old('transmission', $vehicle->transmission) }}"></div>
    <div class="field"><label>Kasa Tipi</label><input name="body_type" value="{{ old('body_type', $vehicle->body_type) }}"></div>
    <div class="field"><label>Renk</label><input name="color" value="{{ old('color', $vehicle->color) }}"></div>

    <div class="field full"><label>Açıklama</label><textarea name="description">{{ old('description', $vehicle->description) }}</textarea></div>

    <div class="field">
      <label>Kapak Görseli {{ $vehicle->exists ? '(değiştirmek için seç)' : '' }}</label>
      <input type="file" name="image" accept="image/*">
      @if ($vehicle->has_image)
        <img class="thumb" style="width:120px;height:90px;margin-top:10px" src="{{ $vehicle->image_url }}" alt="">
      @endif
    </div>

    <div class="field">
      <label>Durum</label>
      <div class="check" style="margin-bottom:10px"><input type="checkbox" name="is_published" value="1" id="pub" {{ old('is_published', $vehicle->is_published ?? true) ? 'checked' : '' }}><label for="pub" style="margin:0;text-transform:none">Yayında</label></div>
      <div class="check"><input type="checkbox" name="is_featured" value="1" id="feat" {{ old('is_featured', $vehicle->is_featured ?? false) ? 'checked' : '' }}><label for="feat" style="margin:0;text-transform:none">Ana sayfa vitrini</label></div>
    </div>
  </div>

  <div class="btn-row" style="margin-top:20px">
    <button class="btn solid">{{ $vehicle->exists ? 'Güncelle' : 'Kaydet' }}</button>
    <a class="btn" href="{{ route('admin.vehicles.index') }}">Vazgeç</a>
  </div>
</form>
@endsection
