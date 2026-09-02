<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Reddit Replica')</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/reddit-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>
<body class="app-page @yield('body-class')">
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
        @section('ricerca')
          <span class="ricerca">
            <form action="{{ route('Ricerca') }}" method="GET">
              <input type="text" name="q" class="ricerca-input" placeholder="Cerca su Reddit">
            </form>
          </span>
        @show
          <span class="pulsanti">
            <a href="{{ route('creaPostPagina') }}" class="pulsante-crea-post">Crea Post</a>
            <a href="https://github.com/Will-Vill" class="pulsante-github" target="_blank">Git Hub</a>
            <button id="tema-toggle" class="pulsante-tema" aria-label="Cambia tema">
              <span class="icona-tema">☀️</span>
            </button>
            <div class="avatar-container">
              <img src="{{ asset(session('avatar', 'assets/images/reddit-logo.png')) }}" alt="Avatar" class="avatar-utente">
              <div class="menu-utente nascosto">
                <div class="menu-utente-header">
                  <img src="{{ asset(session('avatar', 'assets/images/reddit-logo.png')) }}" alt="Avatar" class="avatar-menu-piccolo">
                  <span class="nome-utente-display">{{ session('username', 'Utente') }}</span>
                </div>
                <div class="menu-utente-body">
                  <a href="{{ route('profiloUtente') }}" class="menu-link profilo_utente-link">Profilo Utente</a>
                  <a href="{{ route('logout') }}" class="menu-link logout-link">Logout</a>
                </div>
              </div>
            </div>
            <div class="menu-mobile-container">
              <button class="pulsante-menu-mobile">☰</button>
              <div class="dropdown-menu nascosto">
                <a href="{{ route('creaPostPagina') }}" class="pulsante-crea-post-mobile">Crea Post</a>
                <a href="https://github.com/williamvil1" class="pulsante-github-mobile" target="_blank">GitHub</a>
              </div>
            </div>
          </span>
        </div>
    </header>
    <main>
        @yield('content')
    </main>

    @section('footer')
        <footer id="footer-telefono">
            <p>Sito creato da William Villari</p>
        </footer>
        <footer id="footer-pagina_principale">
            <p>Sito creato da<br><i>William Villari</i></p>
        </footer>
        <div id="immagine-modale" class="modale-immagine nascosto">
            <span class="chiudi-modale">&times;</span>
            <img class="contenuto-modale" id="img-modale">
            <div class="didascalia-modale"></div>
        </div>
    @show
    
    @stack('scripts')
</body>
</html>