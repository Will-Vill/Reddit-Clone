@extends('layouts.app')

@section('body-class', 'interazioni_post-page')

@section('title')
Profilo di {{ session('username') }}
@endsection

@section('content')
<div class="container">
        <div class="contenuto-destro">
            <div class="interazioni-header">
                <a href="{{ Route('profiloUtente') }}" class="btn-torna-profilo">⬅️ Torna al Profilo</a>
                <h2>🔄 Le Mie Interazioni</h2>
                <p class="info-testo">Tutti i post con cui hai interagito</p>
            </div>
            <div class="sezione-main" id="sezione-main-interazioni">
            </div>
        </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/main.js') }}" defer></script>
@endpush