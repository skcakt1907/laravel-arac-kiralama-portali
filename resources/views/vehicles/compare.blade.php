@extends('layouts.app')
@section('title', __('site.compare_title'))

@section('content')
<section>
  <div class="container">
    <div class="sec-head">
      <div class="eyebrow">@lang('site.col_eye')</div>
      <h2>@lang('site.compare_title')</h2>
      <span class="diamond">✦</span>
    </div>

    @if ($vehicles->isEmpty())
      <p class="about-text">@lang('site.compare_empty')</p>
      <div style="text-align:center;margin-top:24px"><a href="{{ route('vehicles.index') }}" class="btn ghost">@lang('site.nav_collection')</a></div>
    @else
      <div style="overflow-x:auto">
        <table class="compare-table">
          <tr>
            <th class="rowlabel"></th>
            @foreach ($vehicles as $v)
              <th>
                @if ($v->has_image)<img src="{{ $v->image_url }}" alt="" style="width:100%;max-width:220px;aspect-ratio:1/1;object-fit:cover;border:1px solid var(--line);display:block;margin-bottom:10px">@endif
                {{ $v->brand }} {{ $v->model }}
                <form method="POST" action="{{ route('compare.remove', $v) }}" style="margin-top:8px">@csrf @method('DELETE')
                  <button class="poa" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:11px">✕ @lang('site.remove')</button>
                </form>
              </th>
            @endforeach
          </tr>
          @foreach ([
            'spec_year' => 'year', 'spec_mileage' => 'mileage_km', 'spec_engine' => 'engine',
            'spec_fuel' => 'fuel', 'spec_transmission' => 'transmission', 'spec_body' => 'body_type', 'spec_color' => 'color',
          ] as $label => $field)
            <tr>
              <td class="rowlabel">@lang('site.' . $label)</td>
              @foreach ($vehicles as $v)
                <td>{{ $field === 'mileage_km' ? ($v->mileage_km ? number_format($v->mileage_km, 0, ',', '.') . ' km' : '—') : ($v->$field ?: '—') }}</td>
              @endforeach
            </tr>
          @endforeach
          <tr>
            <td class="rowlabel"></td>
            @foreach ($vehicles as $v)
              <td><a href="{{ route('vehicles.show', $v) }}" class="mini-btn">@lang('site.inq')</a></td>
            @endforeach
          </tr>
        </table>
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
