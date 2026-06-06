@extends('admin.layout')
@section('title', 'Sipariş ' . $order->order_no)

@section('content')
<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">
  {{-- Kalemler --}}
  <div class="panel">
    <table>
      <thead><tr><th>Ürün</th><th>Birim</th><th>Adet</th><th>Tutar</th></tr></thead>
      <tbody>
        @foreach ($order->items as $it)
          <tr>
            <td>{{ $it->name }}</td>
            <td>{{ \App\Models\Part::formatMoney($it->unit_price, $order->currency) }}</td>
            <td>{{ $it->qty }}</td>
            <td>{{ \App\Models\Part::formatMoney($it->line_total, $order->currency) }}</td>
          </tr>
        @endforeach
        <tr><td colspan="3" style="text-align:end"><b>Toplam</b></td><td><b>{{ $order->total_formatted }}</b></td></tr>
      </tbody>
    </table>
  </div>

  {{-- Müşteri + durum --}}
  <div class="form" style="padding:22px">
    <h3 style="font-size:15px;margin-bottom:14px;color:var(--gold-l)">Müşteri</h3>
    <p style="line-height:1.9;font-size:13.5px">
      <b>{{ $order->customer_name }}</b><br>
      {{ $order->email }}<br>
      {{ $order->phone }}<br>
      <span class="muted">{{ $order->address }}{{ $order->city ? ', ' . $order->city : '' }}{{ $order->country ? ', ' . $order->country : '' }}</span>
      @if ($order->note)<br><br><span class="muted">Not: {{ $order->note }}</span>@endif
    </p>

    <hr style="border-color:var(--line);margin:18px 0">

    <form method="POST" action="{{ route('admin.orders.status', $order) }}">
      @csrf @method('PATCH')
      <div class="field">
        <label>Sipariş Durumu</label>
        <select name="status">
          @foreach (['pending','paid','shipped','cancelled'] as $s)
            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ __('site.status_' . $s) }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn solid" style="width:100%;justify-content:center">Güncelle</button>
    </form>

    <p class="muted" style="font-size:12px;margin-top:14px">
      Ödeme: {{ strtoupper($order->payment_method) }}
      @if ($order->paid_at)<br>Ödendi: {{ $order->paid_at->format('d.m.Y H:i') }}@endif
    </p>

    <a class="btn" style="width:100%;justify-content:center;margin-top:14px" href="{{ route('admin.orders.index') }}">← Listeye Dön</a>
  </div>
</div>
@endsection
