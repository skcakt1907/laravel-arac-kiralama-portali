<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yönetim Girişi · Demirbey</title>
@if (setting('favicon'))<link rel="icon" href="{{ asset('storage/' . setting('favicon')) }}">@endif
<link href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="login-wrap">
  <form class="login-box" method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    <div class="lg">
      @if (setting('logo'))
        <img src="{{ asset('storage/' . setting('logo')) }}" alt="logo" style="max-height:54px;max-width:200px;margin-bottom:8px">
      @endif
      <b>DEMİRBEY</b>
      <small>Yönetim Paneli</small>
    </div>

    @if ($errors->any())
      <div class="errors">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <div class="field">
      <label>E-posta</label>
      <input type="email" name="email" value="{{ old('email') }}" required autofocus>
    </div>
    <div class="field">
      <label>Parola</label>
      <input type="password" name="password" required>
    </div>
    <div class="field check">
      <input type="checkbox" name="remember" id="remember" value="1">
      <label for="remember" style="margin:0;text-transform:none">Beni hatırla</label>
    </div>
    <button class="btn solid" style="width:100%;justify-content:center">Giriş Yap</button>
  </form>
</div>
</body>
</html>
