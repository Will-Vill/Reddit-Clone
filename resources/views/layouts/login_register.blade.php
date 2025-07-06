<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Reddit Replica')</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/reddit-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>
<body class="login-page">
    <header>
        <div class="header-container-login">
          <div class="header-sinistra">
            <span class="icona-reddit">
              <img src="{{ asset('assets/images/reddit-logo.png') }}" alt="Reddit">
            </span>
            <h3 class="titolo-reddit">
              <a href="{{ route('index') }}">reddit</a>
            </h3>
          </div>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <script src="{{ asset('assets/js/toggle-password.js') }}" defer></script>
    @stack('scripts')
</body>
</html>