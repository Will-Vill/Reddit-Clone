@extends('layouts.app')

@section('body-class', 'informazioni-page')

@section('title')
Profilo di {{ session('username') }}
@endsection

@section('content')
<div class="container-informazioni">
    <div class="contenuto-principale-informazioni">
        <h1 class="titolo-informazioni">Informazioni sul sito</h1>
            <p class="testo-informazioni">
                Questo progetto è una replica di Reddit dove è possibile visualizzare, commentare, votare i Post recuperati da Reddit e crearne altri
            </p>
            <ul>
                <li><strong>Crea post:</strong> Condividi pensieri, immagini o notizie.</li>
                <li><strong>Vota:</strong> Metti upvote o downvote ai post che preferisci</li>
                <li><strong>Interagisci:</strong> Commenta e interagisci con altri utenti</li>
            </ul>
            <h2>Come funziona?</h2>
            <p>
                Inizia dalla pagina principale scegli un subreddit. Inizia a pubblicare post puoi creare immagini o scrivere post di testo. Ogni post può essere votato e commentato da altri utenti.
            </p>
            <h2>Contatti</h2>
            <p>
                Per qualsiasi informazione, scrivi a <a href="mailto:VLLWLM03A24C351P@studium.unict.it">VLLWLM03A24C351P@studium.unict.it</a>
            </p>
    </div>
</div>






@endsection

@push('scripts')
<script src="{{ asset('assets/js/main.js') }}" defer></script>
@endpush