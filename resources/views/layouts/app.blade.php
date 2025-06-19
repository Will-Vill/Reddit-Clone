<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Reddit Replica')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>
<body class="app-page">
    <header>
        <div class="header-container">
          <div class="header-sinistra">
            <span class="icona-reddit">
              <img src="{{ asset('assets/images/reddit-logo.png') }}" alt="Reddit">
            </span>
            <h3 class="titolo-reddit">
              <a href="{{ route('index') }}">reddit</a>
            </h3>
          </div>
          <span class="ricerca">
            <input type="text" class="ricerca-input" placeholder="Cerca su Reddit">
          </span>
          <span class="pulsanti">
            <a href="{{-- route('posts.create') --}}" class="pulsante-crea-post">Crea Post</a>
            <a href="https://github.com/williamvil1" class="pulsante-github" target="_blank">Git Hub</a>
            <button id="tema-toggle" class="pulsante-tema" aria-label="Cambia tema">
              <span class="icona-tema">☀️</span>
            </button>
            <div class="menu-mobile-container">
              <button class="pulsante-menu-mobile">☰</button>
              <div class="dropdown-menu nascosto">
                <a href="{{-- route('posts.create') --}}" class="pulsante-crea-post-mobile">Crea Post</a>
                <a href="https://github.com/williamvil1" class="pulsante-github-mobile" target="_blank">GitHub</a>
              </div>
            </div>
          </span>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>