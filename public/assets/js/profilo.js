const formStatus = {
  password: true,
  confirmPassword: true,
};




const tabButtons = document.querySelectorAll('.tab-button');
const tabContents = document.querySelectorAll('.tab-content');
const benvenutoIniziale = document.getElementById('benvenuto-iniziale');




for (const content of tabContents) {
  content.classList.remove('active');
}


for (const button of tabButtons) {
    button.addEventListener('click', gestioneTab);
}

function gestioneTab(event) {
    const button = event.currentTarget;
    const targetTab = button.getAttribute('data-tab');
    const targetContent = document.getElementById('tab-' + targetTab);

    const Attivo = button.classList.contains('active');

    if(Attivo) {
        button.classList.remove('active');
        targetContent.classList.remove('active');
        
        if(benvenutoIniziale) {
            benvenutoIniziale.style.display = 'block';
        }
    } else {
        if(benvenutoIniziale) {
            benvenutoIniziale.style.display = 'none';
        }
        
        for (const btn of tabButtons) {
            btn.classList.remove('active');
        }
        
        button.classList.add('active');
        
        for (const content of tabContents) {
            content.classList.remove('active');
        }
        
        targetContent.classList.add('active');
    }
}





document.getElementById('password').addEventListener('blur', checkPassword);
document.getElementById('confirm_password').addEventListener('blur', checkConfirmPassword);



function checkPassword(event) {
    const passwordInput = event.currentTarget;
    const formGroup = passwordInput.parentNode;
    const password = passwordInput.value;
    const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,30}$/

    if (passwordInput.value === '') {
        formGroup.classList.remove('invalid', 'valid');
        formStatus.password = true;
     if (document.getElementById('confirm_password').value === '') {
        document.getElementById('confirm_password').parentNode.classList.remove('invalid', 'valid');
        formStatus.confirmPassword = true;
     }
     return;
    }
    if(passwordRegex.test(password)) {
        formGroup.classList.remove('invalid');
        formGroup.classList.add('valid');
        formStatus.password = true;
    } else {
        formGroup.classList.remove('valid');
        formGroup.classList.add('invalid');
        formStatus.password = false;
    }
}

function checkConfirmPassword(event) {
    const confirm_passwordInput = event.currentTarget;
    const formGroup = confirm_passwordInput.parentNode;
    const password = document.getElementById('password').value;
    const confirm_password = confirm_passwordInput.value;

   if (confirm_passwordInput.value === '' && document.getElementById('password').value === '') {
        formGroup.classList.remove('invalid', 'valid');
        formStatus.confirmPassword = true;
        return;
    }
    if(confirm_password === password && confirm_password !== '') {
        formGroup.classList.remove('invalid');
        formGroup.classList.add('valid');
        formStatus.confirmPassword = true;
    } else {
        formGroup.classList.remove('valid');
        formGroup.classList.add('invalid');
        formStatus.confirmPassword = false;
    }
}

function checkPasswordVisibility(event) {
    const targetInputId = event.currentTarget.dataset.target;
    const targetInput = document.getElementById(targetInputId);
    if (targetInput) {
            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                event.currentTarget.textContent = '🙈';
            } else {
                targetInput.type = 'password';
                event.currentTarget.textContent = '👁️';
            }
        }
}


const toggle_passwords = document.querySelectorAll('.toggle-password-visibility2');
for(const toggle_password of toggle_passwords) {
    toggle_password.addEventListener('click',checkPasswordVisibility);
}

const formModificaProfilo = document.getElementById('form-modifica-profilo');
const messaggioAggiornamento = document.getElementById('messaggio-aggiornamento-profilo');
const passwordInputGlobal = document.getElementById('password');
const confirmPasswordInputGlobal = document.getElementById('confirm_password');



if(formModificaProfilo) {
    formModificaProfilo.addEventListener('submit', checkDati);
}


async function checkDati(event) {
    event.preventDefault();
    if (passwordInputGlobal.value !== '' || confirmPasswordInputGlobal.value !== '') {
        checkPassword({ currentTarget: passwordInputGlobal });
        checkConfirmPassword({ currentTarget: confirmPasswordInputGlobal });
    } else {
        formStatus.password = true;
        formStatus.confirmPassword = true;
        passwordInputGlobal.parentNode.classList.remove('invalid', 'valid');
        confirmPasswordInputGlobal.parentNode.classList.remove('invalid', 'valid');
    }
    const passwordCheckValid = !Object.values(formStatus).includes(false);

    const currentBioValue = document.getElementById('nuova-bio').value;
    const checkBioChanged = currentBioValue.trim() !== '';
    const checkPasswordCambiate = passwordInputGlobal.value !== '' || confirmPasswordInputGlobal.value !== '';

    let smthChanged = checkBioChanged || checkPasswordCambiate;

    if (!passwordCheckValid && checkPasswordCambiate) {
        if (messaggioAggiornamento) {
            messaggioAggiornamento.textContent = 'Password non valida. Controlla e riprova.';
            messaggioAggiornamento.className = 'messaggio-aggiornamento error';
        }
        return;
    }

    if (smthChanged) {
        if (messaggioAggiornamento) {
            messaggioAggiornamento.textContent = 'Salvataggio in corso...';
            messaggioAggiornamento.className = 'messaggio-aggiornamento neutral';
        }

        const formData = new FormData(formModificaProfilo);

        if (formData.get('password') === '' && formStatus.password === true) {
            formData.delete('password');
            formData.delete('conferma_password');
        }

        try {
            const response = await fetch("api/aggiorna_profilo.php", {
            method: 'POST',
            body: formData
        })
        const jsonData = await checkDatiOnResponse(response);
        aggiornaProfilo(jsonData);
        } catch(error) {
            console.error('Errore durante l\'invio del form:', error);
            if (messaggioAggiornamento) {
                messaggioAggiornamento.textContent = 'Errore: ' + (error.message || 'Impossibile completare la richiesta.');
                messaggioAggiornamento.className = 'messaggio-aggiornamento error';
            }
        }
    } else {
        if (messaggioAggiornamento) {
            messaggioAggiornamento.textContent = 'Nessuna modifica rilevata.';
            messaggioAggiornamento.className = 'messaggio-aggiornamento neutral';
        }
    }
}


function checkDatiOnResponse(response) {
    if (!response.ok) {
        throw new Error('Errore nella risposta del server: ' + response.statusText);
    }
    return response.json();
}


function aggiornaProfilo(data) {

    if (messaggioAggiornamento) {
        messaggioAggiornamento.textContent = data.message || 'Operazione completata.';
        messaggioAggiornamento.className = data.success ? 'messaggio-aggiornamento success' : 'messaggio-aggiornamento error';
    }

    if (data.success) {
        const nuovaBioTextarea = document.getElementById('nuova-bio');
        console.log("Dati aggiornati:", data);

        if (data.updated_fields && data.updated_fields.bio) {
            const bioDisplayElement = document.querySelector('#tab-informazioni .info-valore.bio-display');
            if (bioDisplayElement) {
                if (data.new_bio_html !== null && data.new_bio_html !== undefined) {
                    bioDisplayElement.innerHTML = data.new_bio_html;
                } else {
                    bioDisplayElement.innerHTML = '<em>Nessuna biografia impostata.</em>';
                }
            }
            if (nuovaBioTextarea) {
                nuovaBioTextarea.value = '';
            }
        }
        
        if (data.updated_fields && data.updated_fields.password) {
            const passwordInputGlobal = document.getElementById('password');
            const confirmPasswordInputGlobal = document.getElementById('confirm_password');
            if (passwordInputGlobal) passwordInputGlobal.value = '';
            if (confirmPasswordInputGlobal) confirmPasswordInputGlobal.value = '';
            if (passwordInputGlobal && passwordInputGlobal.parentNode) passwordInputGlobal.parentNode.classList.remove('valid', 'invalid');
            if (confirmPasswordInputGlobal && confirmPasswordInputGlobal.parentNode) confirmPasswordInputGlobal.parentNode.classList.remove('valid', 'invalid');
            formStatus.password = true;
            formStatus.confirmPassword = true;
        }

        if (data.require_logout) {
            setTimeout(redirectToIndex, 2000);
            return;
        }
    } else {
        console.error("Errore dall'API:", data.message);
    }
}



function redirectToIndex() {
    window.location.href = 'index.php';
}


function escapeHTML(str) {
    if (str === null || str === undefined) return '';
    return str.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}