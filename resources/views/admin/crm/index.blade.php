@extends('admin.layout')
@section('title', 'Müşteriler / CRM')

@section('content')

{{-- Özet kartlar --}}
<div class="cards">
  <div class="card"><div class="n">{{ $stats['members'] }}</div><div class="l">Üye</div></div>
  <div class="card"><div class="n">{{ $stats['customers'] }}</div><div class="l">Müşteri (sipariş)</div></div>
  <div class="card"><div class="n">{{ $stats['contacts'] }}</div><div class="l">Toplam Kişi</div></div>
  <div class="card"><div class="n">{{ $stats['orders'] }}</div><div class="l">Sipariş</div></div>
  <div class="card"><div class="n">{{ $stats['paid'] }}</div><div class="l">Ödenen</div></div>
  <div class="card"><div class="n">{{ $stats['pending'] }}</div><div class="l">Bekleyen</div></div>
  <div class="card"><div class="n">{{ $stats['leads'] }}</div><div class="l">Sat Talebi</div></div>
</div>

{{-- Arama --}}
<div class="toolbar">
  <form class="search" method="GET" action="{{ route('admin.crm.index') }}">
    <input type="text" name="q" value="{{ $q }}" placeholder="Ad, e-posta veya telefon ara…">
    <button class="btn sm">Ara</button>
    @if ($q)<a class="btn sm" href="{{ route('admin.crm.index') }}">Temizle</a>@endif
  </form>
  <span class="muted">{{ $contacts->count() }} kişi</span>
</div>

{{-- Birleşik kişi listesi --}}
<div class="panel">
  <table>
    <thead>
      <tr>
        <th>Ad</th>
        <th>İletişim</th>
        <th>Tür</th>
        <th>Sipariş</th>
        <th>Harcama</th>
        <th>Son İşlem</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($contacts as $x)
        <tr>
          <td><b>{{ $x['name'] ?: '—' }}</b></td>
          <td>
            <a href="mailto:{{ $x['email'] }}">{{ $x['email'] }}</a>
            @if ($x['phone'])<br><span class="muted">{{ $x['phone'] }}</span>@endif
          </td>
          <td>
            @if ($x['is_member'])<span class="badge on">Üye</span>@endif
            @if ($x['is_customer'])<span class="badge paid">Müşteri</span>@endif
            @if ($x['is_lead'])<span class="badge pending">Sat Talebi</span>@endif
          </td>
          <td>{{ $x['orders'] ?: '—' }}</td>
          <td>{{ $x['spent'] > 0 ? \App\Models\Part::formatMoney($x['spent'], $x['currency'] ?? 'USD') : '—' }}</td>
          <td class="muted">{{ optional($x['last'])->format('d.m.Y') ?? '—' }}</td>
        </tr>
      @empty
        <tr><td colspan="6" class="muted" style="text-align:center;padding:30px">Kayıt bulunamadı.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<p class="muted" style="margin-top:14px;font-size:12px">
  Harcama yalnızca <b>ödenen</b> siparişleri kapsar. Aynı e-posta hem üye hem müşteri olabilir; liste e-postaya göre birleştirilmiştir.
</p>
@endsection
