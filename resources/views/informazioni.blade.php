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
                Questo progetto è una replica funzionale di Reddit, una piattaforma dove è possibile visualizzare, commentare e votare post di vario genere. Gli utenti possono interagire con contenuti recuperati direttamente da Reddit e creare i propri contributi originali, arricchendo così l'esperienza di community.
            </p>
            <ul>
                <li><strong>Crea post:</strong> Condividi pensieri, immagini e notizie su argomenti di tuo interesse.</li>
                <li><strong>Vota:</strong> Esprimi la tua opinione con upvote e downvote, contribuendo a evidenziare i contenuti più apprezzati dalla community.</li>
                <li><strong>Interagisci:</strong> Commenta i post, partecipa alle discussioni e connettiti con altri utenti che condividono i tuoi interessi.</li>
            </ul>
            <h2>Come funziona?</h2>
            <p>
                Esplora la pagina principale e scegli un subreddit tematico che ti interessa. Puoi navigare tra i contenuti esistenti oppure creare nuovi post: hai la possibilità di condividere testi informativi o caricare immagini. Ogni contributo può essere valutato e commentato dalla community.
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