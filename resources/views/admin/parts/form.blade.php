@extends('admin.layout')
@section('title', $part->exists ? 'Parça Düzenle' : 'Yeni Parça')

@section('content')
<form class="form" method="POST"
      action="{{ $part->exists ? route('admin.parts.update', $part) : route('admin.parts.store') }}"
      enctype="multipart/form-data">
  @csrf
  @if ($part->exists) @method('PUT') @endif

  <div class="grid">
    <div class="field full"><label>Ad *</label><input name="name" value="{{ old('name', $part->name) }}" required></div>

    <div class="field">
      <label>Kategori</label>
      <select name="part_category_id">
        <option value="">— Seçiniz —</option>
        @foreach ($categories as $c)
          <option value="{{ $c->id }}" {{ old('part_category_id', $part->part_category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="field"><label>Uyumlu Araç</label><input name="compatible" value="{{ old('compatible', $part->compatible) }}" placeholder="FERRARI · 488 / F8"></div>

    <div class="field"><label>SKU / Ürün Kodu</label><input name="sku" value="{{ old('sku', $part->sku) }}"></div>
    <div class="field"><label>Stok *</label><input type="number" name="stock" value="{{ old('stock', $part->stock ?? 0) }}" min="0" required></div>

    <div class="field"><label>Fiyat *</label><input type="number" step="0.01" name="price" value="{{ old('price', $part->price ?? 0) }}" min="0" required></div>
    <div class="field">
      <label>Para Birimi *</label>
      <select name="currency">
        @foreach (['USD','EUR','AED','TRY','GBP'] as $cur)
          <option value="{{ $cur }}" {{ old('currency', $part->currency ?? 'USD') === $cur ? 'selected' : '' }}>{{ $cur }}</option>
        @endforeach
      </select>
    </div>

    <div class="field full"><label>Kısa Açıklama</label><input name="short_desc" value="{{ old('short_desc', $part->short_desc) }}" maxlength="255"></div>
    <div class="field full"><label>Açıklama</label><textarea name="description">{{ old('description', $part->description) }}</textarea></div>

    <div class="field">
      <label>Görsel {{ $part->exists ? '(değiştirmek için seç)' : '' }}</label>
      <input type="file" name="image" accept="image/*">
      @if ($part->cover_image)
        <img class="thumb" style="width:120px;height:90px;margin-top:10px" src="{{ asset('storage/' . $part->cover_image) }}" alt="">
      @endif
    </div>
    <div class="field">
      <label>Durum</label>
      <div class="check" style="margin-bottom:10px"><input type="checkbox" name="is_published" value="1" id="pub" {{ old('is_published', $part->is_published ?? true) ? 'checked' : '' }}><label for="pub" style="margin:0;text-transform:none">Yayında</label></div>
      <div class="check"><input type="checkbox" name="is_featured" value="1" id="feat" {{ old('is_featured', $part->is_featured ?? false) ? 'checked' : '' }}><label for="feat" style="margin:0;text-transform:none">Ana sayfa vitrini</label></div>
    </div>
  </div>

  <div class="btn-row" style="margin-top:20px">
    <button class="btn solid">{{ $part->exists ? 'Güncelle' : 'Kaydet' }}</button>
    <a class="btn" href="{{ route('admin.parts.index') }}">Vazgeç</a>
  </div>
</form>
@endsection
