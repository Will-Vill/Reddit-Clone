  // Sezione main per index.php
  if (document.querySelector('#sezione-main-index')) {
    caricaPostInizialiDB();
  }


// Parte degli upvote e downvote


const pulsantiUpvote = document.querySelectorAll('.upvote');
const pulsantiDownvote = document.querySelectorAll('.downvote');

for(const pulsanteUpvote of pulsantiUpvote)
{
  pulsanteUpvote.addEventListener('click',Upvote);
}

for(const pulsanteDownvote of pulsantiDownvote)
{
  pulsanteDownvote.addEventListener('click',Downvote);
}


async function Upvote(event)
{
  const pulsante = event.currentTarget;
  const post = pulsante.closest('.post');
  const votiContainer = pulsante.parentNode // siamo su post-voto
  const contatore = votiContainer.querySelector('.contatore-voto');
  const pulsanteDownvote = votiContainer.querySelector('.downvote');
  const VotiCorrenti = parseInt(contatore.textContent);

  let nuovoVoto = VotiCorrenti;
  let valoreVotoPerDB = 0; // 1 per upvote, -1 per downvote, 0 per rimuovere il voto


  if(pulsante.classList.contains('votato'))
  {
    pulsante.classList.remove('votato');
    nuovoVoto = VotiCorrenti - 1;
    valoreVotoPerDB = 0; // Si rimuove
  }
  else
  {
    if(pulsanteDownvote.classList.contains('votato'))
    {
      pulsanteDownvote.classList.remove('votato');
      nuovoVoto = VotiCorrenti + 2;
      valoreVotoPerDB = 1; // Si aggiunge upvote
    }
    else
    {
      nuovoVoto = VotiCorrenti + 1;
      valoreVotoPerDB = 1; // Si aggiunge upvote
    }
    pulsante.classList.add('votato');
  }
  contatore.textContent = nuovoVoto;

  const reddit_id = post.dataset.redditId;
  const titolo = post.querySelector('.titolo-post').textContent;
  const autore = post.dataset.autore;
  const subreddit = post.dataset.subreddit;
  const url = post.dataset.url || '';
  const thumbnail = post.dataset.thumbnail || '';
  const contenuto = post.dataset.contenuto || '';

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const formData = new FormData();
  formData.append('reddit_id', reddit_id);
  formData.append('titolo', titolo);
  formData.append('autore', autore);
  formData.append('subreddit', subreddit);
  formData.append('url', url);
  formData.append('thumbnail', thumbnail);
  formData.append('contenuto', contenuto);
  formData.append('tipo_voto_utente', valoreVotoPerDB);
  formData.append('voto_attuale_post', VotiCorrenti.toString());
  
  pulsante.disabled = true;


  try {
    const response = await fetch("/post_voto", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      body: formData
    })
    const jsonData = await onPostVotoResponse(response);
    onPostVotoJson(jsonData,pulsante);

  } catch(error) {
    console.error("Errore nell'Upvote");
  }
}


async function Downvote(event)
{
  const pulsante = event.currentTarget;
  const post = pulsante.closest('.post');
  const votiContainer = pulsante.parentNode // siamo su post-voto
  const contatore = votiContainer.querySelector('.contatore-voto');
  const pulsanteUpvote = votiContainer.querySelector('.upvote');
  const VotiCorrenti = parseInt(contatore.textContent);


  let nuovoVoto = VotiCorrenti;
  let valoreVotoPerDB = 0; // 1 per upvote, -1 per downvote, 0 per rimuovere il voto


  if(pulsante.classList.contains('votato'))
  {
    pulsante.classList.remove('votato');
    nuovoVoto = VotiCorrenti + 1;
    valoreVotoPerDB = 0; // Si rimuove
  }
  else
  {
    if(pulsanteUpvote.classList.contains('votato'))
    {
      pulsanteUpvote.classList.remove('votato');
      nuovoVoto = VotiCorrenti - 2;
      valoreVotoPerDB = -1; // Si aggiunge downvote
    }
    else
    {
      nuovoVoto = VotiCorrenti - 1;
      valoreVotoPerDB = -1; // Si aggiunge downvote
    }
    pulsante.classList.add('votato');
  }
  contatore.textContent = nuovoVoto;

  const reddit_id = post.dataset.redditId;
  const titolo = post.querySelector('.titolo-post').textContent;
  const autore = post.dataset.autore;
  const subreddit = post.dataset.subreddit;
  const url = post.dataset.url || '';
  const thumbnail = post.dataset.thumbnail || '';
  const contenuto = post.dataset.contenuto || '';

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const formData = new FormData();
  formData.append('reddit_id', reddit_id);
  formData.append('titolo', titolo);
  formData.append('autore', autore);
  formData.append('subreddit', subreddit);
  formData.append('url', url);
  formData.append('thumbnail', thumbnail);
  formData.append('contenuto', contenuto);
  formData.append('tipo_voto_utente', valoreVotoPerDB);
  formData.append('voto_attuale_post', VotiCorrenti.toString());
  
  pulsante.disabled = true;

  try {
    const response = await fetch("/post_voto", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      body: formData
    })
    const jsonData = await onPostVotoResponse(response);
    onPostVotoJson(jsonData,pulsante);

  } catch(error) {
    console.error("Errore nel Downvote");
  }
}





function onPostVotoResponse(response) {
  if (!response.ok) {
    console.log("Errore nella risposta del server:");
  }
  return response.json();
}


function onPostVotoJson(json, pulsante) {
  const post = pulsante.closest('.post');
  const votiContainer = post.querySelector('.post-voto');

  const contatore = votiContainer.querySelector('.contatore-voto');
  const pulsanteUpvote = votiContainer.querySelector('.upvote');
  const pulsanteDownvote = votiContainer.querySelector('.downvote');

  const titoloLinkElement = post.querySelector('.post-info .titolo-post-link');

  if (json.success && json.nuovo_voto_post !== undefined && json.nuovo_tipo_voto_utente !== undefined) {
    console.log("Voto aggiornato con successo. Nuovo punteggio: ", json.nuovo_voto_post, "Flag_salvataggio_post: ", json.flag_salvataggio_post, "Nuovo tipo voto utente: ", json.nuovo_tipo_voto_utente);

    if (contatore) {
      contatore.textContent = json.nuovo_voto_post;
    }

    if (pulsanteUpvote && pulsanteDownvote) {
      pulsanteUpvote.classList.remove('votato');
      pulsanteDownvote.classList.remove('votato');

      const tipoVotoNumerico = parseInt(json.nuovo_tipo_voto_utente, 10);

      if (tipoVotoNumerico === 1) {
        pulsanteUpvote.classList.add('votato');
      } else if (tipoVotoNumerico === -1) {
        pulsanteDownvote.classList.add('votato');
      }

      if (titoloLinkElement && post.dataset.source === "redditApi") {
              const redditIdDelPost = post.dataset.redditId;
              if (redditIdDelPost) {
                 if (tipoVotoNumerico === 1 || tipoVotoNumerico === -1) {
                     titoloLinkElement.href = `/post_singolo/reddit/${encodeURIComponent(redditIdDelPost)}`;
                     titoloLinkElement.classList.remove('non-cliccabile');
                 } else {
                     titoloLinkElement.href = "#";
                     titoloLinkElement.classList.add('non-cliccabile');
                 }
              } else {
                 console.warn("redditId mancante per un post marcato come redditApi:", post);
                 titoloLinkElement.href = "#";
                 if (!titoloLinkElement.classList.contains('non-cliccabile')) {
                     titoloLinkElement.classList.add('non-cliccabile');
                 }
              }
          }
    }

  } else {
    console.error("Errore nell'aggiornamento del voto:", json.error || "Risposta API non valida o incompleta.");
  }

  if (pulsante) {
    pulsante.disabled = false;
  }
}






// Parte per nascondere i commenti


const pulsantiNascondi = document.querySelectorAll('.toggle-commenti');
for (const pulsante of pulsantiNascondi) {
  pulsante.addEventListener("click", nascondiCommenti);
}

function nascondiCommenti(event) {
  const pulsante = event.currentTarget;
  const toggleContainer = pulsante.parentNode;
  const post = toggleContainer.parentNode;
  const commentiContainer = post.querySelector('.sezione-commenti');
  
  if (commentiContainer.classList.contains('nascosto')) 
  {
    commentiContainer.classList.remove('nascosto');
    pulsante.textContent = 'Nascondi commenti';
    pulsante.classList.remove('collapsed');
  } 
  else 
  {
    commentiContainer.classList.add('nascosto');
    pulsante.textContent = 'Mostra commenti';
    pulsante.classList.add('collapsed');
  }
}


// Parte per il tema chiaro/scuro


const tema = document.querySelector("#tema-toggle");

tema.addEventListener('click', cambioTema);

function cambioTema() {
  
  if (document.body.classList.contains('tema-chiaro')) 
  {
    document.body.classList.remove('tema-chiaro');
    tema.querySelector('.icona-tema').textContent = '☀️';
  } 
  else 
  {
    document.body.classList.add('tema-chiaro');
    tema.querySelector('.icona-tema').textContent = '🌙';
  }
}



// Parte invia commento

const pulsanteInviaCommento = document.querySelectorAll('.pulsante_invia-commento');

for (const pulsante of pulsanteInviaCommento)
{
  pulsante.addEventListener('click', inviaCommento);
}

async function inviaCommento(event)
{
  const pulsante = event.currentTarget;
  const sezioneVoti = pulsante.parentNode; // siamo su commento-sezione_voti
  const aggiungiCommento = sezioneVoti.parentNode; // siamo su aggiungi-commento

  const testoCommento = aggiungiCommento.querySelector('.inserisci-commento');
  const textArea = testoCommento.value;

  let postElement;
  if (document.body.contains(document.querySelector('article.post.post-singolo'))) { // Siamo in post_singolo.php
      postElement = document.querySelector('article.post.post-singolo');
  } else {
      const postFooter = aggiungiCommento.parentNode; 
      const sezioneCommenti = postFooter.parentNode; 
      postElement = sezioneCommenti.parentNode; 
  }

  if(textArea === '')
  {
    alert('Non puoi inviare un commento vuoto!');
    return;
  }

  const reddit_id = postElement.dataset.redditId;


  const titoloElement = postElement.querySelector('.titolo-post');
  const titolo = titoloElement ? titoloElement.textContent : (postElement.dataset.titolo || "Titolo non disponibile");
  const autore = postElement.dataset.autore;
  const subreddit = postElement.dataset.subreddit;
  const url = postElement.dataset.url || '';
  const thumbnail = postElement.dataset.thumbnail || '';
  const contenutoDelPost = postElement.dataset.contenuto || '';
  const contatoreVotoElement = postElement.querySelector('.contatore-voto');
  const votoDelPost = contatoreVotoElement ? contatoreVotoElement.textContent : (postElement.dataset.voto || '0');

  const formData = new FormData();
  formData.append('commento', textArea);
  formData.append('reddit_id', reddit_id);
  formData.append('titolo', titolo);
  formData.append('autore', autore);
  formData.append('subreddit', subreddit);
  formData.append('url', url);
  formData.append('thumbnail', thumbnail);
  formData.append('contenuto', contenutoDelPost);
  formData.append('voto', votoDelPost);
  
  pulsante.disabled = true;
  pulsante.textContent = 'Invio in corso...';





  try {
    const response = await fetch("api/post_commento.php", {
      method: 'POST',
      body: formData
    })
    const jsonData = await onPostCommentoResponse(response);
    const sezioneCommentiSingolo = document.querySelector('.sezione-commenti-singolo');
    const listaCommentiCaricati = sezioneCommentiSingolo ? sezioneCommentiSingolo.querySelector('.lista-commenti-caricati') : null;
    onPostCommentoJson(jsonData, listaCommentiCaricati, testoCommento, textArea, pulsante);
  } catch(error) {
    console.error("Errore inviaCommento");
  }
}



function onPostCommentoResponse(response) {
  if (!response.ok) {
    console.log("Errore nella risposta del server:");
  }
  return response.json();
}

function onPostCommentoJson(json, listaCommentiContainer, testoCommento, textArea, pulsante) {
  if(json.success) {
    const nuovoCommento = document.createElement('div');
    nuovoCommento.classList.add('commento');

    nuovoCommento.innerHTML = `
      <div class="commento-contenuto">
        <div class="header-commenti">
          <div class="avatar-commento">
            <img src="${json.user_avatar || 'assets/images/reddit-logo.png'}" alt="Avatar utente">
          </div>
          <div class="commenti-info">
            <h3 class="autore-commento">${json.username || 'Tu'}</h3>
            <p class="testo-commento"></p>
          </div>
        </div>
      </div>
    `;

    nuovoCommento.querySelector(".testo-commento").textContent = textArea;
    
     if (listaCommentiContainer) {
        const nessunCommentoMsg = listaCommentiContainer.querySelector('.nessun-commento-messaggio');
        if (nessunCommentoMsg) {
            nessunCommentoMsg.remove();
        }
        listaCommentiContainer.appendChild(nuovoCommento); 
    } else {
        console.warn("Contenitore lista commenti (listaCommentiContainer) non trovato per aggiornare l'UI.");
    }
    
    if (testoCommento) {
        testoCommento.value = '';
    }
  } else {
    alert("Errore nel salvare il commento: " + (json.error || "Errore sconosciuto dal server"));
  }

  if (pulsante) {
    pulsante.disabled = false;
    pulsante.textContent = 'Invia Commento';
  }
}

// Immagine modale

const immaginiPost = document.querySelectorAll('.immagine-post');
const modale = document.querySelector('#immagine-modale');
const immagineModale = document.querySelector('#img-modale');
const didascaliaModale = document.querySelector('.didascalia-modale');
const chiudiBtn = document.querySelector('.chiudi-modale');

for (const immagine of immaginiPost) {
  immagine.addEventListener('click', apriModale);
}

chiudiBtn.addEventListener('click', chiudiModale);


modale.addEventListener('click', chiudiModaleClick);
document.addEventListener('keydown', chiudiModaleEsc);

function chiudiModaleClick(event) {
  if (event.target === modale) {
    chiudiModale();
  }
}

function chiudiModaleEsc(event) {
  if (event.key === 'Escape') {
    chiudiModale();
  }
}

function apriModale(event) {
  const immagine = event.currentTarget;
  
  modale.classList.remove('nascosto');
  
  immagineModale.src = immagine.src;
  
  const post = immagine.parentNode.parentNode; // cosi andiamo in corpo-post e poi post
  const titoloPost = post.querySelector('.titolo-post');

  if(titoloPost)
    {
      didascaliaModale.textContent = titoloPost.textContent;
    }
    else
    {
      didascaliaModale.textContent = immagine.alt;
    }
  
  document.body.classList.add('no-scroll');
}

function chiudiModale() {
  modale.classList.add('nascosto');
  document.body.classList.remove('no-scroll');
}





// Menu mobile




const pulsanteMenuMobile = document.querySelector('.pulsante-menu-mobile');
const dropdownMenu = document.querySelector('.dropdown-menu');
const menuMobileContainer = document.querySelector('.menu-mobile-container');

pulsanteMenuMobile.addEventListener('click', toggleMenu);

function toggleMenu() {
  if(dropdownMenu.classList.contains('nascosto')) {
    dropdownMenu.classList.remove('nascosto');
  }
  else {
    dropdownMenu.classList.add('nascosto');
  }
}


document.addEventListener('click',cliccatoFuoriMobile);



function cliccatoFuoriMobile(event)
{
  if(!menuMobileContainer.contains(event.target))
  {
    dropdownMenu.classList.add('nascosto');
  }
}




// Menu utente

const avatarContainer = document.querySelector('.avatar-container');
const avatarUtente = document.querySelector('.avatar-utente');
const menuUtente = document.querySelector('.menu-utente');

avatarUtente.addEventListener('click', toggleMenuUtente);

function toggleMenuUtente() {
  if(menuUtente.classList.contains('nascosto')) {
    menuUtente.classList.remove('nascosto');
  } else {
    menuUtente.classList.add('nascosto');
  }
}

document.addEventListener('click',cliccatoFuori);

function cliccatoFuori(event) {
  if(!avatarContainer.contains(event.target)) {
    menuUtente.classList.add('nascosto');
  }
}














// Parte API Reddit OAuth 2.0





function mostraRedditPost(postsData)
{
  const sezioneMain = document.querySelector(".sezione-main");
  let num_postsData = postsData.length;
  for(let i = 0; i < num_postsData; i++)
  {
    const postData = postsData[i].data;

    const post = document.createElement("article");
    post.className = "post";
    post.dataset.source = "redditApi";

    post.dataset.redditId = postData.name || '';
    post.dataset.autore = postData.author || 'unknown_author';
    post.dataset.subreddit = postData.subreddit || 'unknown_subreddit';
    post.dataset.url = postData.url || '';
    post.dataset.thumbnail = postData.thumbnail || '';
    post.dataset.contenuto = postData.selftext || '';
    post.dataset.voto = (postData.score !== null && postData.score !== undefined) ? String(postData.score) : '0';

    // link per il titolo
    const titoloLink = document.createElement("a");
    titoloLink.className = "titolo-post-link non-cliccabile";
    titoloLink.href = "#";

    const titoloH3 = document.createElement("h3");
    titoloH3.className = "titolo-post";
    titoloH3.textContent = postData.title || '[Titolo non disponibile]';
    titoloLink.appendChild(titoloH3);

    // Header
    const headerPost = document.createElement("div");
    headerPost.className = "header-post";
    headerPost.innerHTML = `
      <div class="post-info">
        <div class="user-info">
          <p class="utente-post"></p>
        </div>
      </div>
      <div class="subreddit-container">
        <div class="avatar-subreddit"><img alt="logo" /></div>
        <a class="pulsante-subreddit"></a>
      </div>
    `;

    const postInfoDiv = headerPost.querySelector(".post-info");
    const userInfoDiv = headerPost.querySelector(".user-info");
    postInfoDiv.insertBefore(titoloLink, userInfoDiv);

    headerPost.querySelector(".utente-post").textContent = "Posted by " + (postData.author || 'unknown_author');
    const avatarImg = headerPost.querySelector("img");
    avatarImg.src = "assets/images/" + (postData.subreddit || 'default') + ".png";
    avatarImg.onerror = gestisciErroreImmagine;
    headerPost.querySelector(".pulsante-subreddit").textContent = "r/" + (postData.subreddit || 'unknown_subreddit');

    post.appendChild(headerPost);

    // Corpo del post
    const corpoPost = document.createElement("div");
    corpoPost.className = "corpo-post";

    if (postData.selftext) {
      const testo = document.createElement("p");
      testo.textContent = postData.selftext;
      corpoPost.appendChild(testo);
    }

    let imageUrl = null;
    if (postData.url?.match(/\.(jpeg|jpg|gif|png)$/i)) {
      imageUrl = postData.url;
    }

    if (imageUrl) {
      const img = document.createElement("img");
      img.src = imageUrl;
      img.alt = postData.title || 'Immagine del post';
      img.className = "immagine-post";
      img.addEventListener('error', gestisciErroreImmagine);
      corpoPost.appendChild(img);
    } else if (postData.url && !postData.selftext) {
      const divLink = document.createElement("div");
      divLink.className = "post-link";
      divLink.innerHTML = `
         <a href="${postData.url}" class="btn-esterno" target="_blank" rel="noopener noreferrer">
          <span class="icona-link">🔗</span>
          <span> Apri contenuto esterno</span>
        </a>
      `;
      corpoPost.appendChild(divLink);
    }

    post.appendChild(corpoPost);

    // Voti
    const voti = document.createElement("div");
    voti.className = "post-voto";
    voti.innerHTML = `
      <button class="pulsante-voto upvote">↑</button>
      <span class="contatore-voto"></span>
      <button class="pulsante-voto downvote">↓</button>
    `;
    voti.querySelector(".contatore-voto").textContent = (postData.score !== null && postData.score !== undefined) ? postData.score : '0';
    post.appendChild(voti);


    sezioneMain.appendChild(post);

    const nuovoPulsanteUpvote = post.querySelector('.upvote');
    nuovoPulsanteUpvote.addEventListener('click', Upvote);
    
    const nuovoPulsanteDownvote = post.querySelector('.downvote');
    nuovoPulsanteDownvote.addEventListener('click', Downvote);
    
    const nuovaImmagine = post.querySelector('.immagine-post');
    if (nuovaImmagine) {
      nuovaImmagine.addEventListener('click', apriModale);
    }

  }
}

function gestisciErroreImmagine() {
  this.src = 'assets/images/reddit-logo.png';
}







function onRedditJson(json) 
{
  console.log("JSON Reddit ricevuto");

  return json.data.children;
}




function onRedditResponse(response) 
{
  return response.json();
}




async function fetchRedditPost(event) 
{
  event.preventDefault();
  const subreddit = event.currentTarget.dataset.subreddit;
  console.log("Recupero post da: r/" + subreddit);

  const sezioneMain = document.querySelector(".sezione-main");
  if (!sezioneMain) {
    console.error("Sezione principale non trovata per i post di Reddit.");
    return;
  }
  sezioneMain.innerHTML = '<p class="caricamento-messaggio">Caricamento dei post da r/' + subreddit + '...</p>';

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const formData = new FormData();
  formData.append('subreddit',subreddit)


  try {
    const response = await fetch("/reddit", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      body: formData,
    })
    const jsonData = await onRedditResponse(response);
    const risultatoAPI = onRedditJson(jsonData);

    sezioneMain.innerHTML = ''; 


    mostraRedditPost(risultatoAPI);
  } catch(error) {
    console.error("Errore reddit api");
  }
}

const linkSubreddit = document.querySelectorAll('.subreddit-link');
for (const link of linkSubreddit) {
  link.addEventListener('click', fetchRedditPost);
}



// Parte API Google Gemini API (con key)





function mostraCommentoGenerato(rispostaAI, pulsante) 
{
  const textareaId = pulsante.dataset.textarea;
  const textarea = document.getElementById(textareaId);
  
  textarea.value = rispostaAI;
  
  pulsante.textContent = '🤖 Genera commento AI';
  pulsante.disabled = false;
}



function onGeminiJson(json) 
{
  console.log("JSON ricevuto");
  return json.candidates[0].content.parts[0].text;
}



function onGeminiResponse(response) 
{
  console.log("Richiesta ricevuta");
  return response.json();
}



async function fetchGeneraCommentoAI(event) {
  event.preventDefault();
  
  const pulsante = event.currentTarget;
  const commentoSezioneVoti = pulsante.parentNode;
  const aggiungiCommento = commentoSezioneVoti.parentNode;
  const textarea = aggiungiCommento.querySelector(".inserisci-commento");

  const postSingoloElement = document.querySelector('article.post.post-singolo');
  let titolo = "questo post";

  if (postSingoloElement) {
    const titoloElement = postSingoloElement.querySelector('h1.titolo-post');
    if (titoloElement) {
      titolo = titoloElement.textContent;
    } else {
      titolo = postSingoloElement.dataset.titolo || "questo post";
    }
  } else {
    const postAntenato = pulsante.closest('.post');
    if (postAntenato) {
      const titoloElementAntenato = postAntenato.querySelector('.titolo-post');
      if (titoloElementAntenato) {
        titolo = titoloElementAntenato.textContent;
      }
    }
  }
  
  pulsante.textContent = '⏳ Generando...';
  pulsante.disabled = true;
  
  pulsante.dataset.textarea = textarea.id || "textarea-" + Date.now();
  if (!textarea.id) textarea.id = pulsante.dataset.textarea;

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
  const formData = new FormData();
  formData.append('titolo', titolo);

  try {
    const response = await fetch("/gemini", { // Nella response c'è la risposta HTTP ricevuta dal php che ha nel corpo una stringa json
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      body: formData
    })
    const jsonData = await onGeminiResponse(response); // Nel jsonData c'è l'oggetto javascript 
    const rispostaAI = onGeminiJson(jsonData); // Nella rispostaAI c'è il testo effettivo ricevuto da gemini
    mostraCommentoGenerato(rispostaAI,pulsante);
  } catch(error) {
    console.error("Errore nella generazione del commento.");
  }
}


const pulsantiGeneraAI = document.querySelectorAll('.pulsante_genera-ai');
for(const pulsanteGeneraAI of pulsantiGeneraAI)
{
  pulsanteGeneraAI.addEventListener('click',fetchGeneraCommentoAI)
}





// Parte profilo_utente.php

function mostraPostDatabase(arrayDeiPost, containerDestinazione) {
    containerDestinazione.innerHTML = '';

    if (!arrayDeiPost || arrayDeiPost.length === 0) {
        containerDestinazione.innerHTML = '<p class="nessun-post-messaggio">Non hai ancora commentato/votato nessun post.</p>';
        return;
    }

    for (let i = 0; i < arrayDeiPost.length; i++) {
        const datiPostSingolo = arrayDeiPost[i]; 
        
        const postElement = document.createElement("article");
        postElement.className = "post";
        postElement.dataset.source = "database";

        const idPerLink = datiPostSingolo.post_db_id;
        const redditIdOriginale = datiPostSingolo.reddit_id;

        postElement.dataset.dbId = datiPostSingolo.post_db_id || '';
        postElement.dataset.redditId = datiPostSingolo.reddit_id;
        postElement.dataset.autore = datiPostSingolo.autore;
        postElement.dataset.subreddit = datiPostSingolo.subreddit;
        postElement.dataset.url = datiPostSingolo.url || '';
        postElement.dataset.thumbnail = datiPostSingolo.thumbnail || '';
        postElement.dataset.contenuto = datiPostSingolo.post_contenuto || '';
        postElement.dataset.voto = datiPostSingolo.voto || '0';
        postElement.dataset.tipoContenuto = datiPostSingolo.tipo_contenuto || 'text';




        const headerPost = document.createElement("div");
        headerPost.className = "header-post";

        const titoloLinkDB = document.createElement("a");
        if (redditIdOriginale) {
            titoloLinkDB.href = `/post_singolo/reddit/${encodeURIComponent(redditIdOriginale)}`;
        } else {
            titoloLinkDB.href = '#';
            console.warn("Nessun ID disponibile per il link del post:", datiPostSingolo);
        }
        titoloLinkDB.className = "titolo-post-link";

        const titoloH3DB = document.createElement("h3");
        titoloH3DB.className = "titolo-post";
        titoloH3DB.textContent = escapeHTML(datiPostSingolo.titolo);
        titoloLinkDB.appendChild(titoloH3DB);

        headerPost.innerHTML = `
          <div class="post-info">
            <div class="user-info">
              <p class="utente-post"></p>
            </div>
          </div>
          <div class="subreddit-container">
            <div class="avatar-subreddit"><img alt="logo subreddit" /></div>
            <a class="pulsante-subreddit"></a>
          </div>
        `;
        headerPost.querySelector(".post-info").insertBefore(titoloLinkDB, headerPost.querySelector(".user-info"));

        headerPost.querySelector(".titolo-post").textContent = escapeHTML(datiPostSingolo.titolo);
        headerPost.querySelector(".utente-post").textContent = "Pubblicato da " + escapeHTML(datiPostSingolo.autore);
        const avatarImg = headerPost.querySelector(".avatar-subreddit img");
        avatarImg.src = "assets/images/" + escapeHTML(datiPostSingolo.subreddit) + ".png";
        avatarImg.onerror = gestisciErroreImmagine;
        headerPost.querySelector(".pulsante-subreddit").textContent = "r/" + escapeHTML(datiPostSingolo.subreddit);

        postElement.appendChild(headerPost);


        const corpoPost = document.createElement("div");
        corpoPost.className = "corpo-post";


        const contenutoTestuale = datiPostSingolo.post_contenuto;
        if (contenutoTestuale) {
          const testo = document.createElement("p");
          testo.textContent = contenutoTestuale;
          corpoPost.appendChild(testo);
        }


        let immagineUrl = null
        let redditUrl = null;

        if (datiPostSingolo.url && datiPostSingolo.url.match(/\.(jpeg|jpg|gif|png)$/i)) {
          redditUrl = datiPostSingolo.url;
        }


        if (datiPostSingolo.immagine_path) {
            immagineUrl = datiPostSingolo.immagine_path;
        } else if (datiPostSingolo.url && datiPostSingolo.url?.match(/\.(jpeg|jpg|gif|png)$/i)) {
            immagineUrl = datiPostSingolo.url;
        }
    
        if (immagineUrl) {
            const img = document.createElement("img");
            img.src = immagineUrl;
            img.alt = escapeHTML(datiPostSingolo.titolo || 'Immagine del post');
            img.className = "immagine-post";

            if(datiPostSingolo.immagine_path && redditUrl && datiPostSingolo.immagine_path !== redditUrl) {
              img.dataset.redditUrlFallback = redditUrl;
            }
            img.addEventListener('error', gestisciErroreImmagineUrl);
            corpoPost.appendChild(img);
        } else if (datiPostSingolo.url && !contenutoTestuale) { 
            const divLink = document.createElement("div");
            divLink.className = "post-link";
            divLink.innerHTML = `
              <a href="${escapeHTML(datiPostSingolo.url)}" class="btn-esterno" target="_blank" rel="noopener noreferrer">
                <span class="icona-link">🔗</span>
                <span> Apri contenuto esterno</span>
              </a>
              `;
            corpoPost.appendChild(divLink);
        }



          
        postElement.appendChild(corpoPost);


        const voti = document.createElement("div");
        voti.className = "post-voto";
        voti.innerHTML = `
          <button class="pulsante-voto upvote">↑</button>
          <span class="contatore-voto"></span>
          <button class="pulsante-voto downvote">↓</button>
        `;

        voti.querySelector(".contatore-voto").textContent = datiPostSingolo.voto || '0';
        postElement.appendChild(voti);

        containerDestinazione.appendChild(postElement);

        const nuovoPulsanteUpvote = postElement.querySelector('.upvote');
        if(nuovoPulsanteUpvote) nuovoPulsanteUpvote.addEventListener('click', Upvote);
        
        const nuovoPulsanteDownvote = postElement.querySelector('.downvote');
        if(nuovoPulsanteDownvote) nuovoPulsanteDownvote.addEventListener('click', Downvote);

        const tipoVotoUtenteNumerico = parseInt(datiPostSingolo.tipo_voto_utente, 10);

        if (!isNaN(tipoVotoUtenteNumerico)) {
            if (tipoVotoUtenteNumerico === 1) {
                if(nuovoPulsanteUpvote) nuovoPulsanteUpvote.classList.add('votato');
            } else if (tipoVotoUtenteNumerico === -1) {
                if(nuovoPulsanteDownvote) nuovoPulsanteDownvote.classList.add('votato');
            }
        }
        
        const nuovaImmagine = postElement.querySelector('.immagine-post');
        if (nuovaImmagine) {
          nuovaImmagine.addEventListener('click', apriModale);
        }

    }
}



function onJsonPostDatabase(risultatoAPI, container) {
    if (risultatoAPI.success && risultatoAPI.data) {
      mostraPostDatabase(risultatoAPI.data, container);
    } else if (risultatoAPI.success && (!risultatoAPI.data || risultatoAPI.data.length === 0 )) {
      container.innerHTML = '<p class="nessun-post-messaggio">Non hai ancora commentato nessun post.</p>';
    }
    else {
      console.error('Errore dall\'API o dati mancanti:', risultatoAPI.error || 'Formato risposta API non valido.');
      container.innerHTML = '<p>Errore nel caricare i post: ' + (risultatoAPI.error || 'Dati non validi ricevuti dal server.') + '</p>';
    }
}


function PostDatabaseResponse(response) {
    if (!response.ok) {
        throw new Error('Errore HTTP: ' + response.status);
    }
    return response.json();
}




async function PostDatabase(container) {
  try {
    const response = await fetch("api/Recupera_Post_Commenti_DB.php");
    const risultatoAPI = await PostDatabaseResponse(response);
    onJsonPostDatabase(risultatoAPI,container);
  } catch(error) {
    console.error("Errore nel recupero dei Posts");
  }
}



function escapeHTML(stringa) {
  if (!stringa) return '';
  return stringa
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
}

function gestisciErroreImmagineUrl() {
  const redditUrlFallback = this.dataset.redditUrlFallback; // Che sarebbe l'url di reddit salvato
  const srcCorrente = this.src;

  if (redditUrlFallback && srcCorrente !== redditUrlFallback) {
    this.src = redditUrlFallback;
    delete this.dataset.redditUrlFallback; 
    return;
  }
  this.src = 'assets/images/reddit-logo.png';
  this.removeEventListener('error', gestisciErroreImmagine);
}











// Recuperare i post dal database nell'index.php 

async function caricaPostInizialiDB() {

  const sezioneMain = document.querySelector("#sezione-main-index");
  if(!sezioneMain) {
    console.error("Sezione principale non trovata.");
    return;
  }

  sezioneMain.innerHTML = '<p class="caricamento-messaggio">Caricamento dei post...</p>';

  try {
    const response = await fetch("/post_iniziali");
    const risultatoAPI = await postInizialionResponse(response);
    postInizialionJson(risultatoAPI,sezioneMain);

  } catch(error) {
    console.error("Errore nel recupero dei post");
  }
}





function postInizialionResponse(response) {
  if (!response.ok) {
    throw new Error('Errore HTTP: ' + response.status);
  }
  return response.json();
}



function postInizialionJson(risultatoAPI, sezioneMain) {
  if(risultatoAPI.success && risultatoAPI.posts && risultatoAPI.posts.length > 0) {
    mostraPostDatabase(risultatoAPI.posts, sezioneMain);
  } else if (risultatoAPI.success && (!risultatoAPI.posts || risultatoAPI.posts.length === 0) ) {
    sezioneMain.innerHTML = '<p class="nessun-post-messaggio">Non ci sono post disponibili.</p>';
  } else {
    console.error('Errore nell\'API o dati mancanti:', risultatoAPI.error || 'Formato risposta API non valido.');
    sezioneMain.innerHTML = '<p>Errore nel caricare i post</p>';
  }
}




const pulsantePostRecenti = document.getElementById('btn-carica-post-recenti');
if (pulsantePostRecenti) {
  pulsantePostRecenti.addEventListener('click', fetchPostRecenti);
}


async function fetchPostRecenti(event) {
  const pulsante = event.currentTarget;

  try {
    const response = await fetch("api/Recupera_post_recenti.php");
    const jsonData = await response.json();
    caricaPostRecenti(jsonData, pulsante);
  } catch(error) {
    console.error("Errore nel recupero dei post recenti");
  }
}


function caricaPostRecenti(data, pulsante) {
  const boxLateralePostRecenti = pulsante.parentNode; 
  const loadingMessage = boxLateralePostRecenti.querySelector('.loading-message');
  if (loadingMessage) {
    loadingMessage.remove(); 
  }

  let listaPostRecenti = boxLateralePostRecenti.querySelector('ul.lista-post-recenti');

  if (!listaPostRecenti) {
    listaPostRecenti = document.createElement('ul');
    listaPostRecenti.className = 'lista-post-recenti';
  }
  listaPostRecenti.innerHTML = '';

  if(data.success && data.post && data.post.length > 0) {
    for(const post of data.post) {
      const li = document.createElement('li');
      li.className = 'post-recenti-item';
      li.innerHTML = `
        <a href="/post_singolo/reddit/${encodeURIComponent(post.reddit_id)}" class="titolo-post-recenti"></a>
        <div class="post-recente-info">
          <p class="post-recente-titolo">${escapeHTML(post.titolo)}</p>
          <p class="post-recente-subreddit">r/${escapeHTML(post.subreddit)}</p>
        </div>
      `;
      listaPostRecenti.appendChild(li);
    }
  } else {
    const li = document.createElement('li');
    if(data.success && (!data.post || data.post.length === 0)) {
        li.textContent = 'Nessun post recente disponibile.';
    } else {
        li.textContent = data.error || 'Errore nel caricare i post o nessun post disponibile.';
        console.error("Errore API o dati non validi:", data.error);
    }
    listaPostRecenti.appendChild(li);
  }
}
