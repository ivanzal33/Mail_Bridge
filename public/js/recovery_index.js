// async function handleReset(event) {
//     event.preventDefault();
//
//     const email = document.getElementById('email').value;
//
//     try {
//         const response = await fetch('/api/auth/forgot-password', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify({ email })
//         });
//
//         if (response.ok) {
//             alert("Ссылка для восстановления пароля отправлена на ваш email.");
//         } else {
//             const result = await response.json();
//             alert(result.detail || "Произошла ошибка при отправке.");
//         }
//     } catch (error) {
//         alert("Ошибка сети: " + error.message);
//     }
// }

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('reset-form');

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        alert("Ссылка для восстановления пароля отправлена на ваш email.");
    });
});
