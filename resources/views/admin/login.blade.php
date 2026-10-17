@extends('admin.layout', ['title' => 'Autentificare', 'active' => 'login'])

@section('content')
<section class="login">
  <h1>Panou de admin</h1>
  <p class="muted">CarbonIt Movement · chestionar pentru elevii din Iași</p>
  @unless ($configured)
    <p class="notice">Contul de admin nu este configurat. Setează ADMIN_PASSWORD_HASH în .env.</p>
  @endunless
  @if ($error)<p class="notice err" role="alert">{{ $error }}</p>@endif
  <form method="post" action="{{ route('admin.login.submit') }}" autocomplete="off">
    @csrf
    <label for="user">Utilizator</label>
    <input id="user" name="user" type="text" autocomplete="username" required>
    <label for="pass">Parolă</label>
    <input id="pass" name="pass" type="password" autocomplete="current-password" required>
    <button class="btn" type="submit">Intră în panou</button>
  </form>
</section>
@endsection
