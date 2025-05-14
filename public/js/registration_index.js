const passwordInput = document.getElementById("password");
const togglePasswordButton = document.getElementById("togglePassword");
const confirmPasswordInput = document.getElementById("confirm-password");
const toggleConfirmPasswordButton = document.getElementById("toggleConfirmPassword");

togglePasswordButton.addEventListener("click", function() {
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        togglePasswordButton.classList.remove('bx-show');
        togglePasswordButton.classList.add('bx-hide');
    } else {
        passwordInput.type = "password";
        togglePasswordButton.classList.remove('bx-hide');
        togglePasswordButton.classList.add('bx-show');
    }
});

toggleConfirmPasswordButton.addEventListener("click", function() {
    if (confirmPasswordInput.type === "password") {
        confirmPasswordInput.type = "text";
        toggleConfirmPasswordButton.classList.remove('bx-show');
        toggleConfirmPasswordButton.classList.add('bx-hide');
    } else {
        confirmPasswordInput.type = "password";
        toggleConfirmPasswordButton.classList.remove('bx-hide');
        toggleConfirmPasswordButton.classList.add('bx-show');
    }
});


function validatePasswords() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    // Проверка длины пароля
    if (password.length < 8) {
        alert("Пароль должен содержать не менее 8 символов!");
        return false; // Предотвращаем отправку формы
    }

    // Проверка наличия заглавных и строчных букв, цифр и спецсимволов
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumbers = /[0-9]/.test(password);
    const hasSymbols = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(password); // Расширенный список спецсимволов

    if (!hasUppercase || !hasLowercase || !hasNumbers || !hasSymbols) {
        alert("Пароль должен содержать заглавные и строчные буквы, цифры и специальные символы!");
        return false; // Предотвращаем отправку формы
    }

    // Проверка совпадения паролей
    if (password !== confirmPassword) {
        alert("Пароли не совпадают!");
        return false; // Предотвращаем отправку формы
    }
    return true; // Разрешаем отправку формы
}



document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('registration-form');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        if (!validatePasswords()) {
            return;
        }

        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const repeatPassword = document.getElementById('confirm-password').value;
        const rules = document.getElementById('consent').checked;

        fetch('/auth/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                username: username,
                email: email,
                password: password,
                repeat_password: repeatPassword,
                rules: rules ? 'on' : ''
            })
        })
            .then(async (res) => {
                const data = await res.json();

                if (!res.ok) {
                    if (res.status === 422 && data.errors) {
                        let messages = Object.values(data.errors)
                            .flat()
                            .join('\n');
                        alert("Ошибка:\n" + messages);
                    } else {
                        alert(data.message || 'Произошла ошибка');
                    }
                    return;
                }

                showToast(
                    data.message || 'Пользователь успешно зарегистрирован',
                    'Перенаправление на страницу входа...',
                    data.redirect
                );


                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }, 3000);
            })
            .catch((err) => {
                console.error(err);
                alert("Произошла ошибка соединения с сервером.");
            });
        });

    });


function showToast(initialMessage, followUpMessage, redirectUrl) {
    const toast = document.createElement('div');
    toast.innerHTML = `<div>${initialMessage}</div>`;  // Исправлено

    Object.assign(toast.style, {
        position: 'fixed',
        top: '20px',
        left: '50%',
        transform: 'translateX(-50%)',
        backgroundColor: '#4caf50',
        color: '#fff',
        padding: '15px 25px',
        borderRadius: '8px',
        boxShadow: '0 4px 12px rgba(0, 0, 0, 0.2)',
        fontSize: '16px',
        zIndex: '9999',
        opacity: '0',
        textAlign: 'center',
        transition: 'opacity 0.5s ease-in-out',
    });

    document.body.appendChild(toast);

    // Плавное появление
    setTimeout(() => {
        toast.style.opacity = '1';
    }, 10);

    // Через 1 секунду добавить вторую строку
    setTimeout(() => {
        const secondLine = document.createElement('div');
        secondLine.style.marginTop = '5px';
        secondLine.textContent = followUpMessage;
        toast.appendChild(secondLine);
    }, 1000);

    // Через 3 секунды исчезновение и редирект
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(toast);
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        }, 500);
    }, 3000);
}
