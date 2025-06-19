@extends('layouts.login_register')

@section('title', 'Registrati - Reddit replica')

@section('content')
<div class="container login-container">
  <div class="register-box">
    <h2>Registrati</h2>
    <p class="policy">Continuando, accetti i nostri Termini di Utilizzo e <br> dichiari di aver compreso la nostra Privacy Policy.</p>
    
    <form id="register-form" name="register" method="POST" action="{{ route('register') }}">
      @csrf

      <div class="form-group">
        <input type="text" id="username" name="username" placeholder="Username" required value="{{ old('username') }}">
        <small class="form-hint">L'username deve contenere minimo 5 massimo 15 caratteri (solo lettere, numeri e underscore)</small>
        <span class="feedback-message"></span>
        @error('username')
            <p class="error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <input type="password" id="password" name="password" placeholder="Password" required>
        <span class="toggle-password-visibility" data-target="password">👁️</span>
        <small class="form-hint">La password deve avere almeno: 8 caratteri, una lettera maiuscola, un carattere speciale, un numero</small>
        @error('password')
            <p class="error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Conferma Password" required>
        <span class="toggle-password-visibility" data-target="password_confirmation">👁️</span>
      </div>

      <div class="form-group">
        <input type="email" id="email" name="email" placeholder="Email" required value="{{ old('email') }}">
        <span class="feedback-message"></span>
        @error('email')
            <p class="error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-footer">
        <p>Hai già un account? <a href="{{ route('login') }}">Accedi</a></p>
      </div>

      <button type="submit" class="login-register-button">Registrati</button>
    </form>
  </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/register.js') }}" defer></script>
@endpush