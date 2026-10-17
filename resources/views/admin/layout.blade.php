<!doctype html>
<html lang="ro">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $title ?? 'Admin' }} · CarbonIt Movement</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500..800&family=IBM+Plex+Mono:wght@400;500&family=Instrument+Sans:wght@400..700&display=swap">
<link rel="stylesheet" href="/assets/admin.css">
<link rel="stylesheet" href="/assets/stats.css">
</head>
<body>
@unless (($active ?? '') === 'login')
<header class="a-head">
  <div class="a-wrap a-bar">
    <a class="a-brand" href="{{ route('admin.dashboard') }}">CarbonIt Movement <span>admin</span></a>
    <nav>
      <a href="{{ route('admin.dashboard') }}" @if (($active ?? '') === 'dash') aria-current="page" @endif>Panou</a>
      <a href="{{ route('admin.responses') }}" @if (($active ?? '') === 'resp') aria-current="page" @endif>Răspunsuri</a>
      <a href="{{ config('app.url') }}" target="_blank" rel="noopener">Site</a>
    </nav>
    <form method="post" action="{{ route('admin.logout') }}">
      @csrf
      <button class="btn btn-ghost btn-small" type="submit">Ieșire</button>
    </form>
  </div>
</header>
@endunless
<main class="a-wrap a-main">
@yield('content')
</main>
@stack('scripts')
</body>
</html>
