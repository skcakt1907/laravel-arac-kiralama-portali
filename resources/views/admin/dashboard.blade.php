@extends('admin.layout')
@section('title', 'Genel Bakış')

@section('content')
<div class="cards">
  <a class="card" href="{{ route('admin.vehicles.index') }}">
    <div class="n">{{ $stats['vehicles'] }}</div>
    <div class="l">Toplam Araç</div>
  </a>
  <div class="card">
    <div class="n">{{ $stats['vehicles_pub'] }}</div>
    <div class="l">Yayında Araç</div>
  </div>
  <a class="card" href="{{ route('admin.parts.index') }}">
    <div class="n">{{ $stats['parts'] }}</div>
    <div class="l">Toplam Parça</div>
  </a>
  <a class="card" href="{{ route('admin.orders.index') }}">
    <div class="n">{{ $stats['orders'] }}</div>
    <div class="l">Sipariş</div>
  </a>
  <a class="card" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">
    <div class="n">{{ $stats['orders_pending'] }}</div>
    <div class="l">Bekleyen Ödeme</div>
  </a>
</div>

<div class="toolbar"><h2 style="font-size:16px">Son Siparişler</h2></div>
<div class="panel">
  <table>
    <thead><tr><th>Sipariş No</th><th>Müşteri</th><th>Tutar</th><th>Durum</th><th>Tarih</th><th></th></tr></thead>
    <tbody>
      @forelse ($recentOrders as $o)
        <tr>
          <td><b>{{ $o->order_no }}</b></td>
          <td>{{ $o->customer_name }}</td>
          <td>{{ $o->total_formatted }}</td>
          <td><span class="badge {{ $o->status }}">{{ __('site.status_' . $o->status) }}</span></td>
          <td class="muted">{{ $o->created_at->format('d.m.Y H:i') }}</td>
          <td><a class="btn sm" href="{{ route('admin.orders.show', $o) }}">Aç</a></td>
        </tr>
      @empty
        <tr><td colspan="6" class="muted" style="text-align:center;padding:30px">Henüz sipariş yok.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
