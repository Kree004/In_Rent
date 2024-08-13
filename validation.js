document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('sign-form');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const usernameError = document.getElementById('username-error');
    const emailError = document.getElementById('email-error');
    const passwordError = document.getElementById('password-error');

    usernameInput.addEventListener('input', checkUsername);
    emailInput.addEventListener('input', checkEmail);
    passwordInput.addEventListener('input', checkPassword);

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (validateForm()) {
            form.submit();
        }
    });

    function checkUsername() {
        const username = usernameInput.value;
        if (username.length < 3) {
            usernameError.textContent = 'Username must be at least 3 characters long*';
            return;
        }

        fetch('validation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `action=check_username&username=${username}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                usernameError.textContent = 'Username is already taken*';
            } else {
                usernameError.textContent = '';
            }
        });
    }

    function checkEmail() {
        const email = emailInput.value;
        fetch('validation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `action=check_email&email=${email}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                emailError.textContent = 'Email is already taken*';
            } else {
                emailError.textContent = '';
            }
        });
    }

    function checkPassword() {
        const password = passwordInput.value;
        if (password.length < 8) {
            passwordError.textContent = 'Password must be at least 8 characters long*';
        } else {
            passwordError.textContent = '';
        }
    }

    function validateForm() {
        checkUsername();
        checkEmail();
        checkPassword();
        return !usernameError.textContent && !emailError.textContent && !passwordError.textContent;
    }
});