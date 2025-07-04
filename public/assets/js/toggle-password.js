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


const toggle_passwords = document.querySelectorAll('.toggle-password-visibility, .toggle-password-visibility2');
for(const toggle_password of toggle_passwords) {
    toggle_password.addEventListener('click',checkPasswordVisibility);
}