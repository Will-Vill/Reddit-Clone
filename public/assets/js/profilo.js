const formStatus = {
  password: true,
  confirmPassword: true,
};

const tabButtons = document.querySelectorAll('.tab-button');
const tabContents = document.querySelectorAll('.tab-content');
const benvenutoIniziale = document.getElementById('benvenuto-iniziale');
const formModificaProfilo = document.getElementById('form-modifica-profilo');
const messaggioAggiornamento = document.getElementById('messaggio-aggiornamento-profilo');
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirm_password');
const bioInput = document.getElementById('nuova-bio');
const bioDisplay = document.querySelector('.bio-display');

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

if (passwordInput) {
    passwordInput.addEventListener('blur', checkPassword);
}

if (confirmPasswordInput) {
    confirmPasswordInput.addEventListener('blur', checkConfirmPassword);
}

function checkPassword(event) {
    const passwordInput = event.currentTarget;
    const formGroup = passwordInput.parentNode;
    const password = passwordInput.value;
    const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,30}$/

    if (passwordInput.value === '') {
        formGroup.classList.remove('invalid', 'valid');
        formStatus.password = true;
     if (confirmPasswordInput.value === '') {
        confirmPasswordInput.parentNode.classList.remove('invalid', 'valid');
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
    const password = passwordInput.value;
    const confirm_password = confirm_passwordInput.value;

   if (confirm_passwordInput.value === '' && passwordInput.value === '') {
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

if(formModificaProfilo) {
    formModificaProfilo.addEventListener('submit', checkDati);
}

async function checkDati(event) {
    event.preventDefault();
        
    if (passwordInput.value !== '' || confirmPasswordInput.value !== '') {
        const passwordValid = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,30}$/.test(passwordInput.value);
        const confirmValid = passwordInput.value === confirmPasswordInput.value;
        
        formStatus.password = passwordValid;
        formStatus.confirmPassword = confirmValid;
        
        if (!passwordValid || !confirmValid) {
            messaggioAggiornamento.textContent = 'Password non valida. Controlla e riprova.';
            messaggioAggiornamento.className = 'messaggio-aggiornamento error';
            return;
        }
    }

    const BioCambiata = bioInput.value.trim() !== '';
    const PasswordCambiata = passwordInput.value !== '';
    
    if (!BioCambiata && !PasswordCambiata) return;

    messaggioAggiornamento.textContent = 'Salvataggio in corso...';
    messaggioAggiornamento.className = 'messaggio-aggiornamento neutral';

    const formData = new FormData(formModificaProfilo);
    if (formData.get('password') === '') {
        formData.delete('password');
        formData.delete('password_confirmation');
    }
    
    try {
        const response = await fetch("/aggiorna_profilo", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        
        const data = await onResponse(response);
        
        messaggioAggiornamento.textContent = data.message || 'Operazione completata.';
        messaggioAggiornamento.className = data.success ? 'messaggio-aggiornamento success' : 'messaggio-aggiornamento error';
        
        if (data.success) {
            if (data.updated_fields?.bio && bioDisplay) { // controlla anche se data.updated_fields è vero
                bioDisplay.innerHTML = data.new_bio_html || '<em>Nessuna biografia impostata.</em>';
                bioInput.value = '';
            }
            
            if (data.updated_fields?.password) {
                passwordInput.value = '';
                confirmPasswordInput.value = '';
                passwordInput.parentNode.classList.remove('valid', 'invalid');
                confirmPasswordInput.parentNode.classList.remove('valid', 'invalid');
            }
            
            if (data.require_logout) {
                setTimeout(() => window.location.href = '/login', 2000);
                return;
            }
        }
    } catch(error) {
        console.error('Errore durante l\'invio del form:', error);
        messaggioAggiornamento.textContent = 'Si è verificato un errore imprevisto.';
        messaggioAggiornamento.className = 'messaggio-aggiornamento error';
    }
}

function onResponse(response) {
    return response.json();
}