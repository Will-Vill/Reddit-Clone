@extends('layouts.app')

@section('body-class', 'crea_post-page')

@section('title')
Profilo di {{ session('username') }}
@endsection

@section('content')
<main class="container">
        <div class="contenuto-principale form-creazione-post">
            <h1>Crea un Nuovo Post</h1>

            <form action="{{ route('creaPost') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="titolo">Titolo:</label>
                    <input type="text" name="titolo" id="titolo" class="form-control" value="{{ old('titolo') }}" required>
                </div>

                <div class="form-group">
                    <label for="subreddit">Subreddit:</label>
                    <select name="subreddit" id="subreddit" class="form-control" required>
                        <option value="" disabled selected>Seleziona un subreddit</option>
                        <option value="gaming" @if(old("subreddit") == 'gaming') selected @endif>Gaming</option>
                        <option value="sports" @if(old('subreddit') == 'sports') selected @endif>Sport</option>
                        <option value="anime" @if(old('subreddit') == 'anime') selected @endif>Anime</option>
                        <option value="movies" @if(old('subreddit') == 'movies') selected @endif>Film</option>
                        <option value="music" @if(old('subreddit') == 'music') selected @endif>Musica</option>
                        <option value="science" @if(old('subreddit') == 'science') selected @endif>Scienze</option>
                    </select>
                    @error('subreddit')
                        <p class="error"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Tipo di Contenuto:</label>
                    <div>
                        <input type="radio" name="tipo_contenuto" id="tipo_testo" value="text" @checked(old('tipo_contenuto', 'text') === 'text')>
                        <label for="tipo_testo">Testo</label>
                    </div>
                    @error('tipo_testo')
                        <p class="error"> {{ $message }}</p>
                    @enderror
                    <div>
                        <input type="radio" name="tipo_contenuto" id="tipo_immagine" value="image" @checked(old('tipo_immagine', 'image') === 'image')>
                        <label for="tipo_immagine">Immagine</label>
                    </div>
                    @error('tipo_immagine')
                        <p class="error"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group campo-contenuto-dinamico" data-tipo="text" id="campo_contenuto_testo">
                    <label for="contenuto_testo">Contenuto Testo:</label>
                    <textarea name="contenuto_testo" id="contenuto_testo" rows="5" class="form-control">{{ old('contenuto_testo') }}</textarea>
                    @error('contenuto_testo')
                        <p class="error"> {{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group campo-contenuto-dinamico" data-tipo="image" id="campo_contenuto_immagine" style="display:none;">
                    <label for="contenuto_immagine">Carica Immagine:</label>
                    <input type="file" name="contenuto_immagine" id="contenuto_immagine" class="form-control" accept="image/jpeg, image/png, image/gif">
                    @error('contenuto_immagine')
                        <p class="error"> {{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="pulsante-primario">Crea Post</button>
            </form>


        </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/main.js') }}" defer></script>
<script src="{{ asset('assets/js/crea_post.js') }}" defer></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/crea_post.css') }}">
@endpush