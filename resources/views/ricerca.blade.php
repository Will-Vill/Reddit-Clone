@extends('layouts.app')

@section('title', 'Risultati ricerca: ' . $query)

@section('content')
<div class="container ricerca-container">
    <div class="contenuto-principale-ricerca">
        <h1>Risultati per "{{ $query }}"</h1>
        
        @if($risultati->count() > 0)
            @foreach($risultati as $post)
                <div class="post-card">
                    @include('components.post', ['post' => $post])
                </div>
            @endforeach
            {{ $risultati->appends(['q' => $query])->links() }}
        @else
            <p>Nessun risultato trovato per "{{ $query }}".</p>
            <p>Suggerimenti:</p>
            <ul>
                <li>Controlla l'ortografia delle parole</li>
                <li>Prova a usare parole chiave diverse</li>
                <li>Prova a cercare termini più generici</li>
            </ul>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/main.js') }}" defer></script>
@endpush