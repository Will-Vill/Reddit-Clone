@extends('layouts.app')

@section('body-class', 'post_singolo-page')

@section('title', $posts->titolo)

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="container">
    <div class="contenuto-destro">
        <section class="sezione-main">
            <article class="post post-singolo" 
                     data-db-id="{{ $posts->id }}"
                     data-reddit-id="{{ $posts->reddit_id }}"
                     data-autore="{{ $posts->autore }}"
                     data-subreddit="{{ $posts->subreddit }}"
                     data-url="{{ $posts->url ?? '' }}"
                     data-thumbnail="{{ $posts->thumbnail ?? '' }}"
                     data-contenuto="{{ $posts->post_contenuto ?? '' }}"
                     data-voto="{{ $posts->voto ?? '0' }}">
                
                <div class="header-post">
                    <div class="post-info">
                        <h1 class="titolo-post">{{ $posts->titolo }}</h1>
                        <div class="user-info">
                            <p class="utente-post">Pubblicato da {{ $posts->autore }}</p>
                        </div>
                    </div>
                    @if(!empty($posts->subreddit))
                    <div class="subreddit-container">
                        <div class="avatar-subreddit"><img src="{{ asset('assets/images/' . $posts->subreddit . '.png') }}" alt="logo {{ $posts->subreddit }}" onerror="this.src='{{ asset('assets/images/reddit-logo.png') }}';"/></div>
                        <a class="pulsante-subreddit">r/{{ $posts->subreddit }}</a>
                    </div>
                    @endif
                </div>

                <div class="corpo-post">
                    @if (!empty($posts->post_contenuto))
                        <p>{!! nl2br($posts->post_contenuto) !!}</p>
                    @endif

                    @php
                        $pathLocale = $posts->immagine_path ?? null;
                        $urlRemoto = (!empty($posts->url) && preg_match('/\.(jpeg|jpg|gif|png)$/i', $posts->url)) ? $posts->url : null;
                        $immagineDaMostrare = $pathLocale ?: $urlRemoto;
                        $erroreFallback = "this.src='" . asset('assets/images/reddit-logo.png') . "'; this.onerror=null;";
                        if ($pathLocale && $urlRemoto && $urlRemoto !== $pathLocale){
                            $erroreFallback = "this.src='" . $urlRemoto . "'; this.onerror=function(){this.src='" . asset('assets/images/reddit-logo.png') . "'; this.onerror=null;};";
                        }
                    @endphp

                    @if ($immagineDaMostrare)
                        <img src="{{ asset($immagineDaMostrare) }}" 
                             alt="{{ $posts->titolo }}" 
                             class="immagine-post"
                             onerror="{!! $erroreFallback !!}">
                    @elseif (!empty($posts->url) && empty($posts->post_contenuto))
                        <div class="post-link">
                            <a href="{{ $posts->url }}" class="btn-esterno" target="_blank" rel="noopener noreferrer">
                                <span class="icona-link">🔗</span>
                                <span> Apri contenuto esterno</span>
                            </a>
                        </div>
                    @endif
                </div>

                <div class="post-voto">
                    <button class="pulsante-voto upvote {{ ($posts->tipo_voto_utente ?? 0) == 1 ? 'votato' : '' }}">↑</button>
                    <span class="contatore-voto">{{ $posts->voto ?? '0' }}</span>
                    <button class="pulsante-voto downvote {{ ($posts->tipo_voto_utente ?? 0) == -1 ? 'votato' : '' }}">↓</button>
                </div>
            </article>

            
            <div class="toggle-commenti-container">
                <button class="toggle-commenti">Nascondi commenti</button>
            </div>
            <section class="sezione-commenti sezione-commenti-singolo">
                <h4 class="titolo-commenti">
                    Commenti (<span id="contatore-commenti">{{ $commenti->count() }}</span>)
                </h4>
                <div class="lista-commenti-caricati">
                    @forelse ($commenti as $commento)
                        <div class="commento">
                            <div class="commento-contenuto">
                                <div class="header-commenti">
                                    <div class="avatar-commento">
                                        <img src="{{ asset($commento->user_avatar ?? 'assets/images/reddit-logo.png') }}" alt="Avatar utente">
                                    </div>
                                    <div class="commenti-info">
                                        <h3 class="autore-commento">{{ $commento->username }}</h3>
                                        <span class="data-commento-inline">{{ \Carbon\Carbon::parse($commento->data_commento)->format('d/m/Y H:i') }}</span>
                                        <p class="testo-commento">{!! nl2br(e($commento->contenuto)) !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="nessun-commento-messaggio">Nessun commento ancora. Sii il primo!</p>
                    @endforelse
                </div>

                <div class="post-footer">
                    <div class="aggiungi-commento">
                        <div class="inserisci-commento-container">
                            <textarea class="inserisci-commento" placeholder="Scrivi un commento..." maxlength="300"></textarea>
                        </div>
                        <div class="commento-sezione_voti">
                            <button class="pulsante_invia-commento">Invia Commento</button>
                            <button class="pulsante_genera-ai">🤖 Genera commento AI</button>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/main.js') }}" defer></script>
@endpush