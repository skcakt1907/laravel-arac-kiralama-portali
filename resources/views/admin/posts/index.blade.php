@extends('admin.layout')
@section('title', 'Blog')

@section('content')
<div class="toolbar">
  <div></div>
  <a class="btn solid" href="{{ route('admin.posts.create') }}">+ Yeni Yazı</a>
</div>

<div class="panel">
  <table>
    <thead><tr><th>Görsel</th><th>Başlık (TR)</th><th>Yayın</th><th>Tarih</th><th>İşlem</th></tr></thead>
    <tbody>
      @forelse ($posts as $p)
        <tr>
          <td>@if ($p->has_image)<img class="thumb" src="{{ $p->image_url }}" alt="">@else<span class="muted">—</span>@endif</td>
          <td><b>{{ $p->title_tr ?: '(TR boş)' }}</b></td>
          <td><span class="badge {{ $p->is_published ? 'on' : 'off' }}">{{ $p->is_published ? 'Yayında' : 'Taslak' }}</span></td>
          <td class="muted">{{ optional($p->published_at)->format('d.m.Y') ?? '—' }}</td>
          <td>
            <div class="btn-row">
              <a class="btn sm" href="{{ route('admin.posts.edit', $p) }}">Düzenle</a>
              <form method="POST" action="{{ route('admin.posts.destroy', $p) }}" onsubmit="return confirm('Silinsin mi?')">@csrf @method('DELETE')
                <button class="btn sm danger">Sil</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="muted" style="text-align:center;padding:30px">Yazı yok.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
{{ $posts->links() }}
@endsection
