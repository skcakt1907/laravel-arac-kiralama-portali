@extends('admin.layout')
@section('title', 'Araçlar')

@section('content')
<div class="toolbar">
  <form class="search" method="GET">
    <input type="text" name="q" value="{{ $q }}" placeholder="Marka veya model ara…">
    <button class="btn">Ara</button>
    @if ($q)<a class="btn" href="{{ route('admin.vehicles.index') }}">Temizle</a>@endif
  </form>
  <a class="btn solid" href="{{ route('admin.vehicles.create') }}">+ Yeni Araç</a>
</div>

<div class="panel">
  <table>
    <thead>
      <tr><th>Görsel</th><th>Marka / Model</th><th>Yıl</th><th>KM</th><th>Yayın</th><th>Vitrin</th><th>Satıldı</th><th>İşlem</th></tr>
    </thead>
    <tbody>
      @forelse ($vehicles as $v)
        <tr>
          <td>
            @if ($v->has_image)<img class="thumb" src="{{ $v->image_url }}" alt="">
            @else<span class="muted">—</span>@endif
          </td>
          <td><b>{{ $v->brand }}</b><br><span class="muted">{{ $v->model }}</span></td>
          <td>{{ $v->year ?? '—' }}</td>
          <td>{{ $v->mileage_km ? number_format($v->mileage_km, 0, ',', '.') : '—' }}</td>
          <td>
            <form method="POST" action="{{ route('admin.vehicles.toggle', $v) }}">@csrf @method('PATCH')
              <input type="hidden" name="field" value="is_published">
              <button class="badge {{ $v->is_published ? 'on' : 'off' }}" style="cursor:pointer;background:none">{{ $v->is_published ? 'Yayında' : 'Gizli' }}</button>
            </form>
          </td>
          <td>
            <form method="POST" action="{{ route('admin.vehicles.toggle', $v) }}">@csrf @method('PATCH')
              <input type="hidden" name="field" value="is_featured">
              <button class="badge {{ $v->is_featured ? 'on' : 'off' }}" style="cursor:pointer;background:none">{{ $v->is_featured ? '★ Vitrin' : '☆' }}</button>
            </form>
          </td>
          <td>
            <form method="POST" action="{{ route('admin.vehicles.toggle', $v) }}">@csrf @method('PATCH')
              <input type="hidden" name="field" value="is_sold">
              <button class="badge {{ $v->is_sold ? 'cancelled' : 'off' }}" style="cursor:pointer;background:none">{{ $v->is_sold ? 'Satıldı' : '—' }}</button>
            </form>
          </td>
          <td>
            <div class="btn-row">
              <a class="btn sm" href="{{ route('admin.vehicles.edit', $v) }}">Düzenle</a>
              <form method="POST" action="{{ route('admin.vehicles.destroy', $v) }}" onsubmit="return confirm('Bu araç silinsin mi?')">@csrf @method('DELETE')
                <button class="btn sm danger">Sil</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="muted" style="text-align:center;padding:30px">Araç bulunamadı.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $vehicles->links() }}
@endsection
