/* Parte register controlli username password ed email */
const formStatus = {
  username: true,
  password: true,
  confirmPassword: true,
  email: true
};





function checkUsernameDB(json) {
    const usernameInput = document.getElementById('username');
    const formGroup = usernameInput.parentNode;
    let feedbackSpan = formGroup.querySelector('.feedback-message');

    const isAvailable = !json.exists;
    formStatus.username = isAvailable;  // se è vero vuol dire che l'username è disponibile (da javascript era false cioè non esiste)

    if (isAvailable) {
        formGroup.classList.remove('invalid');
        formGroup.classList.add('valid');

        if(feedbackSpan) {
            feedbackSpan.textContent = 'Username disponibile';
            feedbackSpan.className = 'feedback-message valid';
        }
    } else {
        formGroup.classList.remove('valid');
        formGroup.classList.add('invalid');

        if(feedbackSpan) {
            feedbackSpan.className = 'feedback-message invalid';
            feedbackSpan.textContent = 'Username già utilizzato';
        }
    }
}

function checkEmailDB(json) {
    const emailInput = document.getElementById('email');
    const formGroup = emailInput.parentNode;
    let feedbackSpan = formGroup.querySelector('.feedback-message');

    const isAvailable = !json.exists;
    formStatus.email = isAvailable;  // se è vero vuol dire che l'email è disponibile (da javascript era false cioè non esiste)

    if (isAvailable) {
        formGroup.classList.remove('invalid');
        formGroup.classList.add('valid');

        if(feedbackSpan) {
            feedbackSpan.textContent = 'Email disponibile';
            feedbackSpan.className = 'feedback-message valid';
        } 
    } else {
        formGroup.classList.remove('valid');
        formGroup.classList.add('invalid');

        if(feedbackSpan) {
            feedbackSpan.className = 'feedback-message invalid';
            feedbackSpan.textContent = 'Email già utilizzata';
        }
    }
}

function fetchResponse(response) {
    if (!response.ok) return null;
    return response.json();
}

function checkUsername(event) {
    const usernameInput = event.currentTarget;
    const formGroup = usernameInput.parentNode;
    const username = usernameInput.value;
    const usernameRegex = /^(?!_)(?!.*__)[a-zA-Z0-9_]{5,15}(?<!_)$/;
    let feedbackSpan = formGroup.querySelector('.feedback-message');

    if(usernameRegex.test(username)) {
        if(feedbackSpan) feedbackSpan.textContent = '';
        formGroup.classList.remove('invalid');
        fetch("/check_username?q="+encodeURIComponent(usernameInput.value)).then(fetchResponse).then(checkUsernameDB);
    } else {
        formGroup.classList.remove('valid');
        formGroup.classList.add('invalid');

        if (feedbackSpan) {
             feedbackSpan.className = 'feedback-message invalid';
             if (username.length === 0) {
                 feedbackSpan.textContent = '';
             } else {
                 feedbackSpan.textContent = 'Formato username non valido.';
             }
        }
        formStatus.username = false;
        return Promise.resolve();
    }
}

function checkPassword(event) {
    const passwordInput = event.currentTarget;
    const formGroup = passwordInput.parentNode;
    const password = passwordInput.value;
    const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,30}$/
    if(passwordRegex.test(password)) {
        console.log('Password valida');
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
    if(confirm_password === password && confirm_password !== '') {
        console.log('Conferma password valida');
        formGroup.classList.remove('invalid');
        formGroup.classList.add('valid');
        formStatus.confirmPassword = true;
    } else {
        formGroup.classList.remove('valid');
        formGroup.classList.add('invalid');
        formStatus.confirmPassword = false;
    }
}

function checkEmail(event) {
    const emailInput = event.currentTarget;
    const formGroup = emailInput.parentNode;
    const email = String(emailInput.value).toLowerCase();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let feedbackSpan = formGroup.querySelector('.feedback-message');
    if(emailRegex.test(email)) {
        if(feedbackSpan) feedbackSpan.textContent = '';
        formGroup.classList.remove('invalid');
        fetch("/check_email?q="+encodeURIComponent(emailInput.value)).then(fetchResponse).then(checkEmailDB);
    } else {
        formGroup.classList.remove('valid');
        formGroup.classList.add('invalid');

        if (feedbackSpan) {
             feedbackSpan.className = 'feedback-message invalid';
             if (email.length === 0) {
                 feedbackSpan.textContent = '';
             } else {
                 feedbackSpan.textContent = 'Formato email non valido.';
             }
        }
        formStatus.email = false;
        return Promise.resolve();
    }
}

async function checkSignup(event) {
  // Previene l'invio immediato del form
  event.preventDefault();

  // Esegue tutte le validazioni, comprese quelle asincrone, e attende il loro completamento
  await Promise.all([
      checkUsername({ currentTarget: document.getElementById('username') }),
      checkPassword({ currentTarget: document.getElementById('password') }),
      checkConfirmPassword({ currentTarget: document.getElementById('password_confirmation') }),
      checkEmail({ currentTarget: document.getElementById('email') })
  ]);

  // Ora che tutte le verifiche sono terminate, controlla lo stato finale
  if (Object.values(formStatus).includes(false)) {
    console.log('Form non valido, invio bloccato');
  } else {
    console.log('Form valido, invio in corso...');
    // Se il form è valido, lo invia programmaticamente
    event.target.submit();
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

document.getElementById('username').addEventListener('blur', checkUsername);
document.getElementById('password').addEventListener('blur', checkPassword);
document.getElementById('password_confirmation').addEventListener('blur', checkConfirmPassword);
document.getElementById('email').addEventListener('blur', checkEmail);
document.getElementById('register-form').addEventListener('submit', checkSignup);

const toggle_passwords = document.querySelectorAll('.toggle-password-visibility');
for(const toggle_password of toggle_passwords) {
    toggle_password.addEventListener('click',checkPasswordVisibility);
}