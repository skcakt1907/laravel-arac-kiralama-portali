@extends('admin.layout')
@section('title', 'Kategoriler')

@section('content')
<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">
  <div class="panel">
    <table>
      <thead><tr><th>Sıra</th><th>Ad</th><th>Ürün</th><th>İşlem</th></tr></thead>
      <tbody>
        @forelse ($categories as $c)
          <tr>
            <form method="POST" action="{{ route('admin.categories.update', $c) }}">@csrf @method('PUT')
              <td style="width:80px"><input name="sort_order" type="number" value="{{ $c->sort_order }}" style="width:60px;background:#0d0b07;border:1px solid var(--line);color:var(--text);padding:6px;border-radius:4px"></td>
              <td><input name="name" value="{{ $c->name }}" style="background:#0d0b07;border:1px solid var(--line);color:var(--text);padding:8px;border-radius:4px;width:100%"></td>
              <td class="muted">{{ $c->parts_count }}</td>
              <td>
                <div class="btn-row">
                  <button class="btn sm">Kaydet</button>
              </form>
                  <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" onsubmit="return confirm('Kategori silinsin mi? (Ürünler kategorisiz kalır)')">@csrf @method('DELETE')
                    <button class="btn sm danger">Sil</button>
                  </form>
                </div>
              </td>
          </tr>
        @empty
          <tr><td colspan="4" class="muted" style="text-align:center;padding:30px">Kategori yok.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <form class="form" method="POST" action="{{ route('admin.categories.store') }}" style="padding:22px">
    @csrf
    <h3 style="font-size:15px;margin-bottom:16px;color:var(--gold-l)">Yeni Kategori</h3>
    <div class="field"><label>Ad *</label><input name="name" required></div>
    <div class="field"><label>Sıra</label><input type="number" name="sort_order" value="0"></div>
    <button class="btn solid" style="width:100%;justify-content:center">Ekle</button>
  </form>
</div>
@endsection
