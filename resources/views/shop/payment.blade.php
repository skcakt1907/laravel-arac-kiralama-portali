@extends('layouts.app')

@section('title', __('site.payment_title'))

@section('content')
<section>
  <div class="container" style="max-width:760px">
    <div class="sec-head" style="margin-bottom:36px">
      <div class="eyebrow">@lang('site.parts_eye')</div>
      <h2>@lang('site.payment_title')</h2>
      <span class="diamond">✦</span>
    </div>

    <div class="flash" style="max-width:none">@lang('site.order_received') <b>{{ $order->order_no }}</b></div>

    <div class="part-items" style="margin:24px 0 30px">
      @foreach ($order->items as $item)
        <div class="part">
          <div class="n">{{ $item->name }} <span class="c">× {{ $item->qty }}</span></div>
          <div class="p">{{ \App\Models\Part::formatMoney($item->line_total, $order->currency) }}</div>
        </div>
      @endforeach
      <div class="part" style="border-color:var(--gold)">
        <div class="n">@lang('site.total')</div>
        <div class="p">{{ $order->total_formatted }}</div>
      </div>
      <div class="part">
        <div class="c" style="margin:0">@lang('site.order_status')</div>
        <div class="n">@lang('site.status_' . $order->status)</div>
      </div>
    </div>

    @if ($order->status === \App\Models\Order::STATUS_PAID)
      <div class="flash" style="max-width:none">@lang('site.payment_paid')</div>
    @elseif ($configured)
      <form method="POST" action="{{ route('payment.pay', $order) }}" style="text-align:center">
        @csrf
        <button class="btn solid" style="border:none;cursor:pointer">@lang('site.pay_now')</button>
      </form>
    @else
      {{-- WeoBank API henüz bağlı değil --}}
      <p class="about-text" style="font-size:15px">@lang('site.payment_pending_msg')</p>
    @endif

    <div style="text-align:center;margin-top:30px">
      <a href="{{ route('shop.index') }}" class="btn ghost">@lang('site.continue_shop')</a>
    </div>
  </div>
</section>
@endsection
