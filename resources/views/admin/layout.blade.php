<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Yönetim') · Demirbey Admin</title>
@if (setting('favicon'))<link rel="icon" href="{{ asset('storage/' . setting('favicon')) }}">@endif
<link href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="admin">
  <aside class="side">
    <div class="brand">
      <b>DEMİRBEY</b>
      <small>Yönetim Paneli</small>
    </div>
    <nav>
      @php($r = request()->route()->getName())
      <a href="{{ route('admin.dashboard') }}" class="{{ $r === 'admin.dashboard' ? 'active' : '' }}">◆ <span>Genel Bakış</span></a>
      <a href="{{ route('admin.crm.index') }}" class="{{ str_starts_with($r, 'admin.crm') ? 'active' : '' }}">👥 <span>Müşteriler / CRM</span></a>
      <a href="{{ route('admin.vehicles.index') }}" class="{{ str_starts_with($r, 'admin.vehicles') ? 'active' : '' }}">⛟ <span>Araçlar</span></a>
      <a href="{{ route('admin.rentals.index') }}" class="{{ str_starts_with($r, 'admin.rentals') ? 'active' : '' }}">🔑 <span>Kiralık Araçlar</span></a>
      <a href="{{ route('admin.parts.index') }}" class="{{ str_starts_with($r, 'admin.parts') ? 'active' : '' }}">⚙ <span>Parçalar</span></a>
      <a href="{{ route('admin.categories.index') }}" class="{{ str_starts_with($r, 'admin.categories') ? 'active' : '' }}">▤ <span>Kategoriler</span></a>
      <a href="{{ route('admin.orders.index') }}" class="{{ str_starts_with($r, 'admin.orders') ? 'active' : '' }}">🧾 <span>Siparişler</span></a>
      <a href="{{ route('admin.sell.index') }}" class="{{ str_starts_with($r, 'admin.sell') ? 'active' : '' }}">💰 <span>Sat Talepleri</span></a>
      <a href="{{ route('admin.posts.index') }}" class="{{ str_starts_with($r, 'admin.posts') ? 'active' : '' }}">✎ <span>Blog</span></a>

      <details class="side-group" {{ (str_starts_with($r, 'admin.settings') || str_starts_with($r, 'admin.account')) ? 'open' : '' }}>
        <summary>⚙ <span>Site Ayarları</span><i class="caret">▾</i></summary>
        <a href="{{ route('admin.settings.edit') }}" class="{{ str_starts_with($r, 'admin.settings') ? 'active' : '' }}"><span>İçerik & İletişim</span></a>
        <a href="{{ route('admin.account.edit') }}" class="{{ str_starts_with($r, 'admin.account') ? 'active' : '' }}"><span>Hesap · Şifre / E-posta</span></a>
      </details>

      <a href="{{ url('/') }}" target="_blank">↗ <span>Siteyi Gör</span></a>
    </nav>
  </aside>

  <div class="main">
    <div class="topbar">
      <h1>@yield('title', 'Yönetim')</h1>
      <div class="right">
        <span>{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf
          <button class="btn sm">Çıkış</button>
        </form>
      </div>
    </div>

    <div class="content">
      @if (session('ok'))<div class="flash">{{ session('ok') }}</div>@endif
      @if ($errors->any())
        <div class="errors">
          @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
      @endif
      @yield('content')
    </div>
  </div>
</div>
</body>
</html>
