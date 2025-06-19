const sezioneMain = document.getElementById('sezione-main-interazioni');
if(sezioneMain && typeof PostDatabase === 'function') {
    sezioneMain.innerHTML = '<div class="loading-message">⏳ Caricamento dei post...</div>';
    PostDatabase(sezioneMain);
}