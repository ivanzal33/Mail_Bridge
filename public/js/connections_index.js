let currentType = '';
let editIndex = -1; // Индекс редактируемого контакта
let emailList = []; // Массив для хранения почтовых контактов
let messengerList = []; // Массив для хранения мессенджеров

window.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('messenger-select');
    arrTypeMessenger.forEach(type => {
        const option = document.createElement('option');
        option.value = type;
        option.textContent = type;
        select.appendChild(option);
    });
});
function toggleSideMenu(type) {
    currentType = type;
    editIndex = -1; // Сбросить индекс редактирования
    document.getElementById('input-address').value = '';
    document.getElementById('input-password').value = '';

    const sideMenu = document.getElementById('side-menu');
    const sideMenuTitle = document.getElementById('side-menu-title');
    const inputPassword = document.getElementById('input-password');
    const inputAddress = document.getElementById('input-address');
    const messengerSelect = document.getElementById('messenger-select');

    if (type === 'emails') {
        sideMenuTitle.innerText = 'Электронные почты';
        inputPassword.style.display = 'block';
        inputAddress.placeholder = 'Email Address';
        messengerSelect.style.display = 'none';
    } else {
        sideMenuTitle.innerText = 'Мессенджеры';
        inputPassword.style.display = 'none';
        inputAddress.placeholder = 'Messenger Contact';
        messengerSelect.style.display = 'block'; // Показать выбор мессенджера
    }

    // Показываем меню
    sideMenu.classList.add('active');

    renderContactList();
}
function renderContactList() {
    const listContainer = document.getElementById('account-list');
    listContainer.innerHTML = '';

    if (currentType === 'emails') {
        arrEmails.forEach((contact, index) => {
            const displayText = contact.email;

            const li = document.createElement('li');
            li.className = 'account-item';
            li.innerHTML = `
            ${displayText}
            <div>
                <button onclick="deleteContact(${index})">Удалить</button>
            </div>
        `;
            listContainer.appendChild(li);
        });
    } else if (currentType === 'messengers') {
        arrMessengers.forEach((contact, index) => {
            const displayText = `${contact.type} (${contact.value})`;

            const li = document.createElement('li');
            li.className = 'account-item';
            li.innerHTML = `
            ${displayText}
            <div>
                <button onclick="deleteContact(${index})">Удалить</button>
            </div>
        `;
            listContainer.appendChild(li);
        });
    }
}

function closeSideMenu() {
    const sideMenu = document.getElementById('side-menu');
    sideMenu.classList.remove('active');
}
function deleteContact(index) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (currentType === 'emails') {
        const email = arrEmails[index];

        fetch('/connections/email/delete', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                emailAddress: email.email
            })
        })
            .then(res => res.json())
            .then(data => {
                console.log(data);

                // Удаляем из массива и обновляем UI
                arrEmails.splice(index, 1);
                renderContactList();
                showToast('Почта удалена!', 'Почтовый контакт успешно удалён.');
            })
            .catch(err => {
                console.error(err);
                showToast('Ошибка при удалении почты.', 'Попробуйте еще раз.');
            });

    } else if (currentType === 'messengers') {
        const messenger = arrMessengers[index];

        fetch('/connections/messenger/delete', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                type: messenger.type,
                value: messenger.value
            })
        })
            .then(res => res.json())
            .then(data => {
                console.log(data);

                // Удаляем из массива и обновляем UI
                arrMessengers.splice(index, 1);
                renderContactList();
                showToast('Мессенджер удалён!', 'Контакт успешно удалён.');
            })
            .catch(err => {
                console.error(err);
                showToast('Ошибка при удалении мессенджера.', 'Попробуйте еще раз.');
            });
    }
}


function editContact(index) {

}

function saveContact(index)
{
    if (currentType === 'emails') {
        const address = document.getElementById('input-address').value;
        const password = document.getElementById('input-password').value;
        fetch('/connections/email/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                emailAddress: address,
                emailPassword: password
            })
        })
            .then(res => res.json())
            .then(data => {
                console.log(data);

                // Добавляем в массив (если успех)
                arrEmails.push({
                    email: address,
                    password: password // или data.email, если backend возвращает
                });

                // Обновляем интерфейс
                renderContactList();
                showToast('Почта успешно добавлена!', 'Теперь вы можете использовать этот email для связи.');
            })
            .catch(err => {
                console.error(err);
                showToast('Произошла ошибка при добавлении почты.', 'Попробуйте еще раз.');
            });
    } else if (currentType === 'messengers') {
        const messengerType = document.getElementById('messenger-select').value;
        const value = document.getElementById('input-address').value;

        fetch('/connections/messenger/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                type: messengerType,
                value: value
            })
        })
            .then(res => res.json())
            .then(data => {
                console.log(data);

                // Добавляем в массив (если успех)
                arrMessengers.push({
                    type: messengerType,
                    value: value
                });

                // Обновляем интерфейс
                renderContactList();
                showToast('Мессенджер успешно добавлен!', 'Теперь вы можете использовать этот мессенджер для связи.');
            })
            .catch(err => {
                console.error(err);
                showToast('Произошла ошибка при добавлении мессенджера.', 'Попробуйте еще раз.');
            });
    }
}

function showToast(message) {
    // Создаем контейнер для тоста
    const toast = document.createElement('div');
    toast.innerHTML = `<div>${message}</div>`;

    // Применяем стили для всплывающего сообщения
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

    // Плавное появление тоста
    setTimeout(() => {
        toast.style.opacity = '1';
    }, 10);

    // Через 3 секунды исчезновение и редирект
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 500);
    }, 3000);
}


