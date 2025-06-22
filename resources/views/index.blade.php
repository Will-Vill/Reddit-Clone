@extends('layouts.app')

@section('body-class', 'index-page')

@section('title', 'Reddit Replica - Home')

@section('content')
<div class="container">
    <nav class="categorie">
      <h2 class="home-link">
        <span class="icona-home"></span>
        <a href="{{ route('index') }}">Home</a>
      </h2>
      <div class="link-categorie">
        <h5 class="gaming-link">
          <span class="icona-gaming">
            <img src="{{ asset('assets/images/gaming.png') }}" alt="Gaming">
          </span>
          <a href="#" data-subreddit="gaming" class="subreddit-link">Gaming</a>
        </h5>
        <h5 class="link-sport">
          <span class="icona-sport">
            <img src="{{ asset('assets/images/sports.png') }}" alt="Sport">
          </span>
          <a href="#" data-subreddit="sports" class="subreddit-link">Sport</a>
        </h5>
        <h5 class="link-anime">
          <span class="icona-anime">
            <img src="{{ asset('assets/images/anime.png') }}" alt="Anime">
          </span>
          <a href="#" data-subreddit="anime" class="subreddit-link">Anime</a>
        </h5>
        <h5 class="link-film_e_serie">
          <span class="icona-film_e_serie">
            <img src="{{ asset('assets/images/movies.png') }}" alt="Film">
          </span>
          <a href="#" data-subreddit="movies" class="subreddit-link">Film e serie</a>
        </h5>
        <h5 class="link-musica">
          <span class="icona-musica">
            <img src="{{ asset('assets/images/Music.png') }}" alt="Musica">
          </span>
          <a href="#" data-subreddit="music" class="subreddit-link">Musica</a>
        </h5>
        <h5 class="link-scienze">
          <span class="icona-scienze">
            <img src="{{ asset('assets/images/science.png') }}" alt="Scienze">
          </span>
          <a href="#" data-subreddit="science" class="subreddit-link">Scienze</a>
        </h5>
      </div>
      <div id="div-informazioni">
        <h4 class="link-informazioni">
          <span class="icona-informazioni">
            <img src="{{ asset('assets/images/informazioni.png') }}" alt="Informazioni">
          </span>
          <a href="">Informazioni</a>
        </h4>
      </div>
      <footer id="footer-pagina_principale">
        <p>Sito creato da<br><i>William Villari</i></p>
      </footer>
    </nav>

    <div class="contenuto-destro">
      <section id="sezione-main-index" class="sezione-main">
      </section>

      <div class="barra-laterale">
        <div class="box-laterale post-recenti">
          <h3>Post Recenti</h3>
          <button id="btn-carica-post-recenti" class="pulsante-carica-recenti">Carica Post Recenti</button>
          <ul class="lista-post-recenti">
            <li><p class="loading-message">Clicca il pulsante per caricare i post recenti.</p></li>
          </ul>
        </div>
    
        <div class="box-laterale community">
          <h3>Community Popolari</h3>
          <ul class="lista-community">
            <li>
              <a href="#" data-subreddit="gaming" class="subreddit-link">
                <div class="avatar-community">
                  <img src="{{ asset('assets/images/gaming.png') }}" alt="Gaming">
                </div>
                <div class="community-info">
                  <p class="community-nome">r/Gaming</p>
                  <p class="community-membri">5.2M membri</p>
                </div>
              </a>
            </li>
            <li>
              <a href="#" data-subreddit="sports" class="subreddit-link">
                <div class="avatar-community">
                  <img src="{{ asset('assets/images/sports.png') }}" alt="Sport">
                </div>
                <div class="community-info">
                  <p class="community-nome">r/Sport</p>
                  <p class="community-membri">3.8M membri</p>
                </div>
              </a>
            </li>
            <li>
              <a href="#" data-subreddit="anime" class="subreddit-link">
                <div class="avatar-community">
                  <img src="{{ asset('assets/images/anime.png') }}" alt="Anime">
                </div>
                <div class="community-info">
                  <p class="community-nome">r/Anime</p>
                  <p class="community-membri">2.6M membri</p>
                </div>
              </a>
            </li>
            <li>
                <a href="#" data-subreddit="movies" class="subreddit-link">
                  <div class="avatar-community">
                    <img src="{{ asset('assets/images/movies.png') }}" alt="Film">
                  </div>
                  <div class="community-info">
                    <p class="community-nome">r/Film</p>
                    <p class="community-membri">6.2M membri</p>
                  </div>
                </a>
            </li>
            <li>
                <a href="#" data-subreddit="Music" class="subreddit-link">
                  <div class="avatar-community">
                    <img src="{{ asset('assets/images/Music.png') }}" alt="Musica">
                  </div>
                  <div class="community-info">
                    <p class="community-nome">r/Musica</p>
                    <p class="community-membri">18.0M membri</p>
                  </div>
                </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <footer id="footer-telefono">
    <p>Sito creato da William Villari</p>
  </footer>

  <div id="immagine-modale" class="modale-immagine nascosto">
    <span class="chiudi-modale">&times;</span>
    <img class="contenuto-modale" id="img-modale">
    <div class="didascalia-modale"></div>
  </div>
@endsection

@section('footer')
@endsection

@push('scripts')
<script src="{{ asset('assets/js/main.js') }}" defer></script>
@endpush