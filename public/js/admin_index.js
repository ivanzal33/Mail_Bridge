const managersList = document.getElementById('managersList');
const emailHeader = document.getElementById('emailHeader');
const selectedEmail = document.getElementById('selectedEmail');
const emailDropdownBtn = document.getElementById('emailDropdownBtn');
const emailDropdown = document.getElementById('emailDropdown');
const addManagerBtn = document.getElementById('addManagerBtn');

// Обработка раскрытия выпадающего списка email
emailDropdownBtn.addEventListener('click', () => {
    emailDropdown.style.display = emailDropdown.style.display === 'block' ? 'none' : 'block';
});


// Выбор email
// Обработка клика по email-элементам с делегированием событий
emailDropdown.addEventListener('click', (event) => {
    const item = event.target;
    // Проверяем, что клик был на элементе с классом .email-item
    if (item.classList.contains('email-item')) {
        console.log("клик по почте");

        // Сбросить выделение у всех email
        document.querySelectorAll('.email-item').forEach(el => el.classList.remove('selected'));

        // Выделить выбранную почту
        item.classList.add('selected');

        // Отобразить выбранную почту
        selectedEmail.textContent = item.textContent;
        selectedEmail.style.color = '#00BFFF';
        emailDropdown.style.display = 'none';
    }
});



// Добавление нового менеджера
addManagerBtn.addEventListener('click', () => {
    const newDiv = document.createElement('div');
    newDiv.classList.add('manager-item');
    newDiv.textContent = `Менеджер ${managersList.children.length + 1}`;
    managersList.appendChild(newDiv);
});




window.addEventListener('DOMContentLoaded', () => {
    // Перебираем всех пользователей из window.users и отображаем их на странице
    window.users.forEach(user => {
        const newDiv = document.createElement('div');
        newDiv.classList.add('manager-item');
        newDiv.textContent = user.username + "\n(" + user.email + ")";
        newDiv.setAttribute('data-user-id', user.id);  // Добавляем уникальный идентификатор пользователя
        managersList.appendChild(newDiv);
    });
});

// Обработка выбора менеджера
managersList.addEventListener('click', (event) => {
    if (event.target.classList.contains('manager-item')) {
        // Убираем выделение у всех элементов
        document.querySelectorAll('.manager-item').forEach(el => el.classList.remove('selected'));

        // Добавляем класс 'selected' на выбранный элемент
        event.target.classList.add('selected');

        // Получаем ID пользователя из data-атрибута
        const userId = event.target.getAttribute('data-user-id');
        // Отправляем запрос на сервер для получения подключенных email
        fetch(`/get-user-emails/${userId}`)  // Предполагаем, что этот маршрут существует на сервере
            .then(response => response.json())
            .then(data => {
                // Обновляем заголовок с email для выбранного пользователя
                emailHeader.textContent = `Подключенные email для пользователя: ${event.target.textContent}`;

                // Отображаем список email
                const emailDropdown = document.getElementById('emailDropdown');
                emailDropdown.innerHTML = ''; // Очищаем старые email

                data.emails.forEach(email => {
                    const emailDiv = document.createElement('div');
                    emailDiv.classList.add('email-item');
                    emailDiv.textContent = email;
                    emailDropdown.appendChild(emailDiv);
                });

                // Открываем выпадающий список с email
                emailDropdown.style.display = 'block';
            })
            .catch(error => {
                console.error('Ошибка при получении email:', error);
            });
    }

});


function diagramm()
{
    const ctx1 = document.getElementById('messagesChart').getContext('2d');
    const ctx2 = document.getElementById('responseTimeChart').getContext('2d');

    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['01.12.2025', '02.12.2025', '03.12.2025', '04.12.2025', '05.12.2025'],
            datasets: [{
                label: 'Полученные сообщения',
                data: [15, 35, 25, 50, 45],
                fill: false,
                borderColor: 'rgba(0, 123, 255, 1)',
                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                tension: 0, // сглаживание: 0 для угловатой ломаной
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Дата',
                        font: {
                            size: 14
                        }
                    }
                },
                y: {
                    min: 10,
                    max: 60,
                    ticks: {
                        stepSize: 10
                    },
                    title: {
                        display: true,
                        text: 'Полученные сообщения (шт.)',
                        font: {
                            size: 14
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['01.12.2025', '02.12.2025', '03.12.2025', '04.12.2025', '05.12.2025'],
            datasets: [{
                label: 'Среднее время ответа (мин)',
                data: [25, 40, 35, 50, 45],
                fill: false,
                borderColor: 'rgba(40, 167, 69, 1)', // Цвет линии
                backgroundColor: 'rgba(40, 167, 69, 0.5)', // Цвет фона для линии
                tension: 0, // Сглаживание линии (0 для угловатой ломаной)
                pointRadius: 5, // Размер точек
                pointHoverRadius: 7 // Размер точек при наведении
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Дата',
                        font: {
                            size: 14
                        }
                    }
                },
                y: {
                    min: 10,
                    max: 60,
                    ticks: {
                        stepSize: 10
                    },
                    title: {
                        display: true,
                        text: 'Среднее время ответа (мин.)',
                        font: {
                            size: 14
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false // Отключаем легенду, если не нужна
                }
            }
        }
    });
}

