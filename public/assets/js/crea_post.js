if(document.querySelector(".form-creazione-post")) {
    console.log('Inizializzo la creazione post');
    inizializzaCreaPost();
}


function inizializzaCreaPost() {
    const radioTipoContenuto = document.querySelectorAll('input[name="tipo_contenuto"]');
    var contenitoriRadio = document.querySelectorAll('.form-group > div');

    for (var i = 0; i < contenitoriRadio.length; i++) {
        contenitoriRadio[i].addEventListener('click', handleClick);
    }
    
    if (radioTipoContenuto.length === 0) {
        return;
    }
    
    for (var i = 0; i < radioTipoContenuto.length; i++) {
        radioTipoContenuto[i].addEventListener('change', aggiornaVisibilitaCampiContenuto);
    }
    
    aggiornaVisibilitaCampiContenuto();
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
    
    var tipoSelezionato = document.querySelector('input[name="tipo_contenuto"]:checked');
    var campoTesto = document.getElementById('campo_contenuto_testo');
    var campoImmagine = document.getElementById('campo_contenuto_immagine');

    var tuttiiContenitori = document.querySelectorAll('.form-group > div');
    for (var i = 0; i < tuttiiContenitori.length; i++) {
        tuttiiContenitori[i].classList.remove('radio-selected');
    }
    
    if (campoTesto) campoTesto.style.display = 'none';
    if (campoImmagine) campoImmagine.style.display = 'none';
    
    if (!tipoSelezionato) {
        console.log('Nessun radio button selezionato');
        return;
    }

    var contenitoreSelezionato = tipoSelezionato.closest('div');
    if (contenitoreSelezionato) {
        contenitoreSelezionato.classList.add('radio-selected');
    }
    
    var valoreSelezionato = tipoSelezionato.value;
    console.log('Tipo selezionato:', valoreSelezionato);
    
    if (valoreSelezionato === 'text' && campoTesto) {
        campoTesto.style.display = 'block';
    } else if (valoreSelezionato === 'image' && campoImmagine) {
        campoImmagine.style.display = 'block';
    }
}