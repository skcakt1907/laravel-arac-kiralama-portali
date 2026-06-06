@extends('admin.layout')
@section('title', 'Hesap Ayarları')

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;max-width:880px">

  {{-- Profil / e-posta --}}
  <form class="form" method="POST" action="{{ route('admin.account.profile') }}">
    @csrf @method('PUT')
    <h3 style="color:var(--gold-l);font-size:15px;margin-bottom:16px">Profil & E-posta</h3>
    <div class="field"><label>Ad</label><input name="name" value="{{ old('name', $user->name) }}" required></div>
    <div class="field"><label>E-posta</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
    <button class="btn solid">Güncelle</button>
  </form>

  {{-- Parola --}}
  <form class="form" method="POST" action="{{ route('admin.account.password') }}">
    @csrf @method('PUT')
    <h3 style="color:var(--gold-l);font-size:15px;margin-bottom:16px">Parola Değiştir</h3>
    <div class="field"><label>Mevcut Parola</label><input type="password" name="current_password" required></div>
    <div class="field"><label>Yeni Parola</label><input type="password" name="password" required></div>
    <div class="field"><label>Yeni Parola (Tekrar)</label><input type="password" name="password_confirmation" required></div>
    <button class="btn solid">Parolayı Değiştir</button>
  </form>

</div>
@endsection
