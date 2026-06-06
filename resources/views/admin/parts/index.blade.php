@extends('admin.layout')
@section('title', 'Parçalar')

@section('content')
<div class="toolbar">
  <form class="search" method="GET">
    <input type="text" name="q" value="{{ $q }}" placeholder="Parça ara…">
    <button class="btn">Ara</button>
    @if ($q)<a class="btn" href="{{ route('admin.parts.index') }}">Temizle</a>@endif
  </form>
  <a class="btn solid" href="{{ route('admin.parts.create') }}">+ Yeni Parça</a>
</div>

<div class="panel">
  <table>
    <thead><tr><th>Görsel</th><th>Ad</th><th>Kategori</th><th>Fiyat</th><th>Stok</th><th>Yayın</th><th>İşlem</th></tr></thead>
    <tbody>
      @forelse ($parts as $p)
        <tr>
          <td>@if ($p->cover_image)<img class="thumb" src="{{ asset('storage/' . $p->cover_image) }}" alt="">@else<span class="muted">—</span>@endif</td>
          <td><b>{{ $p->name }}</b><br><span class="muted">{{ $p->compatible }}</span></td>
          <td>{{ $p->category->name ?? '—' }}</td>
          <td>{{ $p->price_formatted }}</td>
          <td>{{ $p->stock }}</td>
          <td><span class="badge {{ $p->is_published ? 'on' : 'off' }}">{{ $p->is_published ? 'Yayında' : 'Gizli' }}</span></td>
          <td>
            <div class="btn-row">
              <a class="btn sm" href="{{ route('admin.parts.edit', $p) }}">Düzenle</a>
              <form method="POST" action="{{ route('admin.parts.destroy', $p) }}" onsubmit="return confirm('Bu parça silinsin mi?')">@csrf @method('DELETE')
                <button class="btn sm danger">Sil</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="muted" style="text-align:center;padding:30px">Parça bulunamadı.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $parts->links() }}
@endsection
