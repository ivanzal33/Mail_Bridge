const loginForm = document.getElementById("login-form");
const passwordInput = document.getElementById("password");
const usernameInput = document.getElementById("username");
const togglePasswordButton = document.getElementById("togglePassword");

const maxAttempts = 5;
const lockDuration = 1 * 60 * 1000; // 30 мин

const timerElement = document.getElementById("timer");
const overlay = document.getElementById("lockOverlay");
const retryBtn = document.getElementById("retryBtn");

togglePasswordButton.addEventListener("click", function () {
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    togglePasswordButton.classList.toggle("bx-show");
    togglePasswordButton.classList.toggle("bx-hide");
});

function getAttempts() {
    return parseInt(localStorage.getItem("loginAttempts")) || 0;
}

function setAttempts(value) {
    localStorage.setItem("loginAttempts", value);
}

function lockForm() {
    const lockUntil = Date.now() + lockDuration;
    localStorage.setItem("lockUntil", lockUntil);
    overlay.style.display = "flex";
    startTimer(lockUntil);
}

function unlockForm() {
    localStorage.removeItem("lockUntil");
    localStorage.removeItem("loginAttempts");
    overlay.style.display = "none";
    retryBtn.style.display = "none";
    timerElement.textContent = "";
}

function updateTimer(lockUntil) {
    const now = Date.now();
    const timeLeft = lockUntil - now;

    if (timeLeft <= 0) {
        timerElement.textContent = "Время истекло";
        retryBtn.style.display = "inline-block";
        return;
    }

    const minutes = Math.floor(timeLeft / 60000);
    const seconds = Math.floor((timeLeft % 60000) / 1000);
    timerElement.textContent = `Осталось: ${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
}

function startTimer(lockUntil) {
    updateTimer(lockUntil);
    const interval = setInterval(() => {
        updateTimer(lockUntil);
        if (Date.now() >= lockUntil) {
            clearInterval(interval);
        }
    }, 1000);
}

retryBtn.addEventListener("click", () => {
    unlockForm();
});

function checkLock() {
    const lockUntil = localStorage.getItem("lockUntil");
    if (lockUntil && Date.now() < lockUntil) {
        overlay.style.display = "flex";
        startTimer(parseInt(lockUntil));
    } else {
        unlockForm();
    }
}

document.addEventListener("DOMContentLoaded", function () {
    console.log("121212")
    const form = document.getElementById('login-form');

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        if (overlay.style.display === "flex") {
            alert("Форма заблокирована. Пожалуйста, подождите.");
            return;
        }

        const attempts = getAttempts();
        if (attempts >= maxAttempts) {
            lockForm();
            return;
        }

        const username = usernameInput.value;
        const password = passwordInput.value;

        try {
            const response = await fetch('/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    username: usernameInput.value,
                    password: passwordInput.value
                })
            });

            const result = await response.json();

            if (response.ok) {
                console.log("я тут")
                localStorage.removeItem("loginAttempts");
                if (result.two_factor) {
                    showTwoFactorModal(); // Показываем форму для ввода кода
                } else if (result.redirect) {
                    window.location.href = result.redirect; // Только если redirect реально есть!
                }
            }
            else {
                setAttempts(getAttempts() + 1);
                const remaining = maxAttempts - getAttempts();
                alert(`Ошибка: ${result.detail || "Неверный логин или пароль"}. Осталось попыток: ${remaining}`);
                if (remaining <= 0) lockForm();
            }
        } catch (error) {
            alert("Ошибка при входе: " + error.message);
        }
    });
});

function showTwoFactorModal() {
    document.getElementById('twoFactorModal').classList.add('active');
}

function hideTwoFactorModal() {
    document.getElementById('twoFactorModal').classList.remove('active');
}


async function submitTwoFactorCode() {
    const code = document.getElementById('twoFactorInput').value;
    const errorDiv = document.getElementById('twoFactorError');

    try {
        const response = await fetch('/auth/verify-2fa', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ two_factor_code: code })
        });

        const result = await response.json();

        if (response.ok && result.redirect) {
            hideTwoFactorModal();
            window.location.href = result.redirect;
        } else {
            errorDiv.textContent = result.detail || 'Код неверный или истёк';
        }
    } catch (error) {
        errorDiv.textContent = "Ошибка соединения!";
    }
}


checkLock(); // Запускаем при загрузке
