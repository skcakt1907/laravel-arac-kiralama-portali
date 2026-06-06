@extends('admin.layout')
@section('title', 'Siparişler')

@section('content')
<div class="toolbar">
  <div class="btn-row">
    <a class="btn sm {{ !$status ? 'solid' : '' }}" href="{{ route('admin.orders.index') }}">Tümü</a>
    @foreach (['pending','paid','shipped','cancelled'] as $s)
      <a class="btn sm {{ $status === $s ? 'solid' : '' }}" href="{{ route('admin.orders.index', ['status' => $s]) }}">{{ __('site.status_' . $s) }}</a>
    @endforeach
  </div>
</div>

<div class="panel">
  <table>
    <thead><tr><th>Sipariş No</th><th>Müşteri</th><th>İletişim</th><th>Tutar</th><th>Durum</th><th>Tarih</th><th></th></tr></thead>
    <tbody>
      @forelse ($orders as $o)
        <tr>
          <td><b>{{ $o->order_no }}</b></td>
          <td>{{ $o->customer_name }}</td>
          <td class="muted">{{ $o->email }}<br>{{ $o->phone }}</td>
          <td>{{ $o->total_formatted }}</td>
          <td><span class="badge {{ $o->status }}">{{ __('site.status_' . $o->status) }}</span></td>
          <td class="muted">{{ $o->created_at->format('d.m.Y H:i') }}</td>
          <td><a class="btn sm" href="{{ route('admin.orders.show', $o) }}">Aç</a></td>
        </tr>
      @empty
        <tr><td colspan="7" class="muted" style="text-align:center;padding:30px">Sipariş bulunamadı.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $orders->links() }}
@endsection
