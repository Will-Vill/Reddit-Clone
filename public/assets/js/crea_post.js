if(document.querySelector(".form-creazione-post")) {
    console.log('Inizializzo la creazione post');
    inizializzaCreaPost();
}


function inizializzaCreaPost() {
    const radioTipoContenuto = document.querySelectorAll('input[name="tipo_contenuto"]');
    
    for (const radio of radioTipoContenuto) {
        radio.addEventListener('change', aggiornaVisibilitaCampiContenuto);
    }

    aggiornaVisibilitaCampiContenuto();
}

function aggiornaVisibilitaCampiContenuto() {
    const tipoSelezionato = document.querySelector('input[name="tipo_contenuto"]:checked').value;
    const campoTesto = document.getElementById('campo_contenuto_testo');
    const campoImmagine = document.getElementById('campo_contenuto_immagine');

    if (tipoSelezionato === 'text') {
        campoTesto.style.display = 'block';
        campoImmagine.style.display = 'none';
    } else {
        campoTesto.style.display = 'none';
        campoImmagine.style.display = 'block';
    }
}