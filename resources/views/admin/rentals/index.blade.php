@extends('admin.layout')
@section('title', 'Kiralık Araçlar')

@section('content')
<div class="toolbar">
  <div></div>
  <a class="btn solid" href="{{ route('admin.rentals.create') }}">+ Yeni Kiralık Araç</a>
</div>

<div class="panel">
  <table>
    <thead><tr><th>Görsel</th><th>Marka / Model</th><th>Yıl</th><th>Günlük</th><th>Yayın</th><th>İşlem</th></tr></thead>
    <tbody>
      @forelse ($rentals as $r)
        <tr>
          <td>@if ($r->has_image)<img class="thumb" src="{{ $r->image_url }}" alt="">@else<span class="muted">—</span>@endif</td>
          <td><b>{{ $r->brand }}</b><br><span class="muted">{{ $r->model }}</span></td>
          <td>{{ $r->year ?? '—' }}</td>
          <td>{{ $r->daily_price_formatted }}</td>
          <td><span class="badge {{ $r->is_published ? 'on' : 'off' }}">{{ $r->is_published ? 'Yayında' : 'Gizli' }}</span></td>
          <td>
            <div class="btn-row">
              <a class="btn sm" href="{{ route('admin.rentals.edit', $r) }}">Düzenle</a>
              <form method="POST" action="{{ route('admin.rentals.destroy', $r) }}" onsubmit="return confirm('Silinsin mi?')">@csrf @method('DELETE')
                <button class="btn sm danger">Sil</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="muted" style="text-align:center;padding:30px">Kiralık araç yok.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
{{ $rentals->links() }}
@endsection
