@extends ('layouts.app')

@section('body-class', 'profilo_utente-page')

@section('title')
Profilo di {{ $info->username }}
@endsection

@section('content')
<div class="container">

    <div class="contenuto-destro profilo-contenuto-speciale"> 
        <div id="benvenuto-iniziale" class="benvenuto-container">
            <h3>👋 Benvenuto nel tuo profilo, {{ $info->username }} !</h3>
            <p>Seleziona una scheda qui sotto per visualizzare le tue attività o modificare il tuo profilo.</p>
        </div>

        <div class="profilo-tabs">
            <button class="tab-button" data-tab="interazioni">🔄 Le Mie Interazioni</button>
            <button class="tab-button" data-tab="informazioni">ℹ️ Informazioni Profilo</button>
            <button class="tab-button" data-tab="modifica">⚙️ Modifica Profilo</button>
        </div>

        <div class="tab-content" id="tab-interazioni">
            <section class="sezione-main">
                <h3>Post con cui hai interagito</h3>
                <p class="info-testo">Visualizza tutti i post con cui hai interagito</p>
                <a href="interazioni_post.php" class="btn-salva btn-interazioni-link">🔄 Visualizza le Mie Interazioni</a>
            </section>
        </div>

        <div class="tab-content" id="tab-informazioni">
            <section class="sezione-main">
                <h3>Le Tue Informazioni</h3>
                <div class="info-profilo-container">
                    <div class="info-profilo-item">
                        <span class="info-label">Nome Utente:</span>
                        <span class="info-valore">{{ $info->username }}</span>
                    </div>

                    <div class="info-profilo-item bio-item">
                        <span class="info-label">Biografia:</span>
                            <div class="info-valore bio-display">
                                 {!! !empty($info->bio) ? htmlspecialchars($info->bio) : '<em>Nessuna biografia impostata.</em>' !!}
                            </div>
                    </div>

                    @if (isset($info->data_registrazione) && !empty($info->data_registrazione))
                    <div class="info-profilo-item">
                        <span class="info-label">Membro Dal:</span>
                        <span class="info-valore">{{ htmlspecialchars(date("d/m/Y", strtotime($info->data_registrazione))) }}</span>
                    </div>
                    @endif

                    @if (isset($info->email) && !empty($info->email))
                    <div class="info-profilo-item">
                        <span class="info-label">Email:</span>
                        <span class="info-valore">{{ htmlspecialchars($info->email) }}</span>
                    </div>
                    @endif

                    @if (isset($commentiTotali))
                    <div class="info-profilo-item">
                        <span class="info-label">Commenti Creati:</span>
                        <span class="info-valore">{{ htmlspecialchars($commentiTotali) }}</span>
                    </div>
                    @endif

                </div>
            </section>
        </div>

       <div class="tab-content" id="tab-modifica">
            <section class="sezione-main">
                <h3>Modifica il tuo profilo</h3>
                <form id="form-modifica-profilo" class="form-modifica" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nuova-bio">Aggiungi/modifica biografia:</label>
                        <textarea id="nuova-bio" name="bio" rows="4" placeholder="Scrivi qualcosa su di te..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="password">Cambio password:</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="Nuova password (opzionale)">
                            <span class="toggle-password-visibility2" data-target="password">👁️</span>
                        </div>
                        <div class="input-wrapper">
                            <input type="password" id="confirm_password" name="password_confirmation" placeholder="Conferma nuova password (opzionale)">
                            <span class="toggle-password-visibility2" data-target="confirm_password">👁️</span>
                        </div>
                    </div>
                    <button type="submit" class="btn-salva">💾 Salva Modifiche</button>
                </form>
                <div id="messaggio-aggiornamento-profilo"></div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/main.js') }}" defer></script>
<script src="{{ asset('assets/js/profilo.js') }}" defer></script>
@endpush