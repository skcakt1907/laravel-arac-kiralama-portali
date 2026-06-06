@extends('admin.layout')
@section('title', 'Aracını Sat Talepleri')

@section('content')
<div class="panel">
  <table>
    <thead><tr><th>Foto</th><th>Kişi</th><th>Araç</th><th>Mesaj</th><th>Durum</th><th>Tarih</th><th>İşlem</th></tr></thead>
    <tbody>
      @forelse ($requests as $req)
        <tr>
          <td>@if ($req->photo)<a href="{{ $req->photo_url }}" target="_blank"><img class="thumb" src="{{ $req->photo_url }}" alt=""></a>@else<span class="muted">—</span>@endif</td>
          <td><b>{{ $req->name }}</b><br><span class="muted">{{ $req->email }}<br>{{ $req->phone }}</span></td>
          <td>{{ trim($req->brand . ' ' . $req->model) ?: '—' }}<br><span class="muted">{{ $req->year }}{{ $req->mileage_km ? ' · ' . number_format($req->mileage_km, 0, ',', '.') . ' km' : '' }}</span></td>
          <td class="muted" style="max-width:280px">{{ \Illuminate\Support\Str::limit($req->message, 120) }}</td>
          <td>
            <form method="POST" action="{{ route('admin.sell.toggle', $req) }}">@csrf @method('PATCH')
              <button class="badge {{ $req->is_handled ? 'on' : 'pending' }}" style="cursor:pointer;background:none">{{ $req->is_handled ? 'İlgilenildi' : 'Bekliyor' }}</button>
            </form>
          </td>
          <td class="muted">{{ $req->created_at->format('d.m.Y H:i') }}</td>
          <td>
            <form method="POST" action="{{ route('admin.sell.destroy', $req) }}" onsubmit="return confirm('Silinsin mi?')">@csrf @method('DELETE')
              <button class="btn sm danger">Sil</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="muted" style="text-align:center;padding:30px">Henüz talep yok.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
{{ $requests->links() }}
@endsection
