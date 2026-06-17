@extends('layouts.app')
@section('title', __('site.compare_title'))

@php
  $grouped = $allVehicles->groupBy('brand');
  $canAdd  = $vehicles->count() < $max;
@endphp

@section('content')
<section>
  <div class="container">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.col_eye')</div>
      <h2>@lang('site.compare_title')</h2>
      <span class="diamond">✦</span>
    </div>

    @if (session('compare_msg'))
      <div class="flash">{{ session('compare_msg') }}</div>
    @endif

    {{-- Araç seçici (boşken veya yer varken) --}}
    @if ($canAdd)
      <form method="POST" action="{{ route('compare.store') }}" class="cmp-addbar">@csrf
        <span class="cmp-addlabel">＋ @lang('site.c_add')</span>
        <select name="vehicle_id" onchange="this.form.submit()" class="cmp-select">
          <option value="">@lang('site.c_pick')</option>
          @foreach ($grouped as $brand => $list)
            <optgroup label="{{ $brand }}">
              @foreach ($list as $opt)
                <option value="{{ $opt->id }}">{{ $opt->brand }} {{ $opt->model }}</option>
              @endforeach
            </optgroup>
          @endforeach
        </select>
        <span class="muted" style="font-size:12px">{{ $vehicles->count() }} / {{ $max }}</span>
      </form>
    @endif

    @if ($vehicles->isEmpty())
      <p class="about-text" style="text-align:center;margin-top:10px">@lang('site.compare_empty')</p>
    @else
      @php($n = $vehicles->count())
      <div style="overflow-x:auto">
        {{-- div-grid (tablo değil) — form'lar hücre içinde sorunsuz çalışsın --}}
        <div class="compare-table cmp-grid" style="grid-template-columns:160px repeat({{ $n }}, minmax(210px,1fr))">

          {{-- 1) Başlık: görsel + ad + Değiştir + kaldır --}}
          <div class="cmp-cell label head"></div>
          @foreach ($vehicles as $v)
            <div class="cmp-cell head">
              @if ($v->has_image)
                <img class="cmp-img" src="{{ $v->image_url }}" alt="{{ $v->brand }} {{ $v->model }}">
              @else
                <div class="cmp-noimg">{{ $v->brand }}</div>
              @endif
              <div class="cmp-name">{{ $v->brand }} {{ $v->model }}</div>
              <div class="cmp-actions">
                <form method="POST" action="{{ route('compare.swap', $v) }}">@csrf
                  <select name="new_id" onchange="this.form.submit()" class="cmp-select sm" title="@lang('site.c_change')">
                    @foreach ($grouped as $brand => $list)
                      <optgroup label="{{ $brand }}">
                        @foreach ($list as $opt)
                          <option value="{{ $opt->id }}" {{ $opt->id === $v->id ? 'selected' : '' }}>{{ $opt->brand }} {{ $opt->model }}</option>
                        @endforeach
                      </optgroup>
                    @endforeach
                  </select>
                </form>
                <form method="POST" action="{{ route('compare.remove', $v) }}">@csrf @method('DELETE')
                  <button class="cmp-x" title="@lang('site.remove')">✕</button>
                </form>
              </div>
            </div>
          @endforeach

          {{-- 2) Özellik satırları --}}
          @foreach ([
            'spec_year' => 'year', 'spec_mileage' => 'mileage_km', 'spec_engine' => 'engine',
            'spec_fuel' => 'fuel', 'spec_transmission' => 'transmission', 'spec_body' => 'body_type', 'spec_color' => 'color',
          ] as $label => $field)
            <div class="cmp-cell label">@lang('site.' . $label)</div>
            @foreach ($vehicles as $v)
              <div class="cmp-cell">{{ $field === 'mileage_km' ? ($v->mileage_km ? number_format($v->mileage_km, 0, ',', '.') . ' km' : '—') : ($v->$field ?: '—') }}</div>
            @endforeach
          @endforeach

          {{-- 3) İncele --}}
          <div class="cmp-cell label"></div>
          @foreach ($vehicles as $v)
            <div class="cmp-cell"><a href="{{ route('vehicles.show', $v) }}" class="mini-btn">@lang('site.inq')</a></div>
          @endforeach
        </div>
      </div>

      <div style="text-align:center;margin-top:30px">
        <form method="POST" action="{{ route('compare.clear') }}" style="display:inline">@csrf
          <button class="btn ghost" style="cursor:pointer">@lang('site.compare_clear')</button>
        </form>
      </div>
    @endif
  </div>
</section>
@endsection
