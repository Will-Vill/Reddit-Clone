if(document.querySelector(".form-creazione-post")) {
    console.log('Inizializzo la creazione post');
    inizializzaCreaPost();
}


function inizializzaCreaPost() {
    const radioTipoContenuto = document.querySelectorAll('input[name="tipo_contenuto"]');
    
    // Non serve più il gestore di click sul div, basta quello sul radio
    for (const radio of radioTipoContenuto) {
        radio.addEventListener('change', aggiornaVisibilitaCampiContenuto);
    }
    
    // NON chiamare più la funzione al caricamento, perché Blade ha già fatto il lavoro.
    // aggiornaVisibilitaCampiContenuto(); 
}

function handleClick() {
    var radioButton = this.querySelector('input[type="radio"]');
    if (radioButton && !radioButton.checked) {
        radioButton.checked = true;
        radioButton.dispatchEvent(new Event('change'));
        console.log('Radio selezionato manualmente:', radioButton.value);
    }
}

function aggiornaVisibilitaCampiContenuto() {
    const tipoSelezionato = document.querySelector('input[name="tipo_contenuto"]:checked').value;
    const campoTesto = document.getElementById('campo_contenuto_testo');
    const campoImmagine = document.getElementById('campo_contenuto_immagine');

    // La logica ora è più semplice: mostra uno e nascondi l'altro.
    if (tipoSelezionato === 'text') {
        campoTesto.style.display = 'block';
        campoImmagine.style.display = 'none';
    } else {
        campoTesto.style.display = 'none';
        campoImmagine.style.display = 'block';
    }
}