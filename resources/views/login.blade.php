@extends('layouts.login_register')

@section('title', 'Accedi - Reddit replica')

@section('content')
<div class="container login-container">
  <div class="login-box">
    <h2>Accedi</h2>
    <p class="policy">Continuando, accetti i nostri Termini di Utilizzo e <br> dichiari di aver compreso la nostra Privacy Policy.</p>
    
    <form id="login-form" name="login" method="POST" action="{{ route('login') }}">
      @csrf

      @error('username')
        <p class="error">{{ $message }}</p>
      @enderror

      <div class="form-group">
        <input type="text" id="username" name="username" placeholder="Username" required value="{{ old('username') }}">
      </div>

      <div class="form-group">
        <input type="password" id="password" name="password" placeholder="Password" required>
        <span class="toggle-password-visibility" data-target="password">👁️</span>
      </div>

      <div class="form-footer">
        <p>Non hai un account? <a href="{{ route('register') }}">Registrati</a></p>
      </div>

      <button type="submit" class="login-register-button">Accedi</button>
    </form>
  </div>
</div>
@endsection