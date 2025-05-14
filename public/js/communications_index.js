const emailList = document.getElementById("emailList");
const messengerList = document.getElementById("messengerList");

let currentMode = null;
let selectedEmail = null;
const connections = [];

function renderItems(data, container) {
    container.innerHTML = "";
    data.forEach(item => {
        const div = document.createElement("div");
        div.classList.add("item");
        if (typeof item === 'object' && item.email) {
            div.textContent = item.email;  // Для почты
            div.dataset.id =  item.id.toString();  // Для почты
        } else {
            div.textContent = item;
            div.dataset.id = item;
        }
        container.appendChild(div);
    });
    bindItemClickListeners();
}

function loadData() {
    try {
        const emails = window.emails || [];
        const messengers = window.messengers || [];
        const data = window.data || [];

        // 1. Отображаем все почты
        const emailAddresses = emails.map(email => ({ email: email.email, id: email.id }));
        console.log(emailAddresses)
        // const emailAddresses = emails.map(email => email.email);

        renderItems(emailAddresses, emailList);

        // 2. Отображаем все мессенджеры
        const messengerLabels = messengers.map(m => `${m.type}: ${m.value}`);
        renderItems(messengerLabels, messengerList);

        // 3. Восстанавливаем связи
        setTimeout(() => {
            data.forEach(email => {
                const fromEl = [...document.querySelectorAll("#emailList .item")]
                    .find(el => el.dataset.id === email.id.toString());
                if (!fromEl) return;

                // Если у почты есть мессенджеры, восстанавливаем связи для каждого из них
                (email.messengers || []).forEach(messenger => {
                    const label = `${messenger.type}: ${messenger.value}`;
                    const toEl = [...document.querySelectorAll("#messengerList .item")]
                        .find(el => el.textContent === label);
                    if (!toEl) return; // Если нет соответствующего мессенджера, пропускаем

                    // Тип связи: "oneWay" или "twoWay"
                    const type = messenger.pivot.relation_type === "two_way" ? "twoWay" : "oneWay";

                    // Рисуем связь между почтой и мессенджером
                    createArrow(fromEl, toEl, type,true);
                });
            });
        }, 0);

    } catch (error) {
        console.error("Ошибка при загрузке данных:", error);
    }
}


function setupButtonListeners() {
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            selectedEmail = null;
            if (btn.classList.contains("arrow-right")) currentMode = "oneWay";
            else if (btn.classList.contains("arrow-both")) currentMode = "twoWay";
            else if (btn.classList.contains("close")) currentMode = "delete";
            clearSelections();
        });
    });
}

function bindItemClickListeners() {
    document.querySelectorAll("#emailList .item").forEach(email => {
        email.addEventListener("mouseenter", () => {
            if (!currentMode || currentMode === "delete") return;
            email.classList.add("highlighted");
            email.style.cursor = "pointer";
        });

        email.addEventListener("mouseleave", () => {
            email.classList.remove("highlighted");
        });

        email.addEventListener("click", () => {
            if (!currentMode || currentMode === "delete") return;
            clearSelections();
            selectedEmail = email;
            email.classList.add("selected");
        });
    });

    document.querySelectorAll("#messengerList .item").forEach(messenger => {
        messenger.addEventListener("mouseenter", () => {
            if (!selectedEmail || !currentMode || currentMode === "delete") return;
            messenger.classList.add("highlighted");
            messenger.style.cursor = "pointer";
        });

        messenger.addEventListener("mouseleave", () => {
            messenger.classList.remove("highlighted");
        });

        messenger.addEventListener("click", () => {
            if (!selectedEmail || !currentMode || currentMode === "delete") return;
            messenger.classList.add("selected");
            createArrow(selectedEmail, messenger, currentMode);
            selectedEmail = null;
        });
    });
}

function clearSelections() {
    document.querySelectorAll(".item").forEach(el => el.classList.remove("selected", "highlighted"));
}

function createArrow(fromEl, toEl, type, isRestored = false) {
    const exists = connections.find(c => c.fromEl === fromEl && c.toEl === toEl);
    if (exists) return;

    const config = {
        color: '#00BFFF',
        size: 3,
        path: 'straight',
        startPlug: type === "twoWay" ? 'arrow3' : 'disc',
        endPlug: 'arrow3',
    };

    const line = new LeaderLine(fromEl, toEl, config);

    const invisibleDiv = document.createElement("div");
    invisibleDiv.className = "hitbox";
    document.body.appendChild(invisibleDiv);
    const lineObj = { fromEl, toEl, line, hitbox: invisibleDiv };

    updateHitboxPosition(lineObj);

    invisibleDiv.addEventListener("mouseenter", () => {
        if (currentMode === "delete") {
            line.setOptions({ color: "#ffff00" });
        }
    });

    invisibleDiv.addEventListener("mouseleave", () => {
        if (currentMode === "delete") {
            line.setOptions({ color: "#00BFFF" });
        }
    });

    invisibleDiv.addEventListener("click", () => {
        if (currentMode === "delete") {
            line.remove();
            invisibleDiv.remove();
            const index = connections.indexOf(lineObj);
            if (index > -1) connections.splice(index, 1);

            onCommunicationDeleted(fromEl.dataset.id, toEl.textContent);
        }
    });

    connections.push(lineObj);
    if (!isRestored) {
        onConnectionCreated(fromEl.dataset.id, toEl.textContent, type);
    }


    window.addEventListener("resize", () => updateHitboxPosition(lineObj));
    window.addEventListener("scroll", () => updateHitboxPosition(lineObj));
}

function updateHitboxPosition({ fromEl, toEl, hitbox }) {
    const fromRect = fromEl.getBoundingClientRect();
    const toRect = toEl.getBoundingClientRect();

    const x1 = fromRect.left + fromRect.width / 2;
    const y1 = fromRect.top + fromRect.height / 2;
    const x2 = toRect.left + toRect.width / 2;
    const y2 = toRect.top + toRect.height / 2;

    const length = Math.hypot(x2 - x1, y2 - y1);
    const angle = Math.atan2(y2 - y1, x2 - x1);

    hitbox.style.position = 'absolute';
    hitbox.style.width = `${length}px`;
    hitbox.style.height = '10px';
    hitbox.style.left = `${x1 + window.scrollX}px`;
    hitbox.style.top = `${y1 + window.scrollY - 5}px`; // Центрируем по оси Y
    hitbox.style.transformOrigin = '0 50%';
    hitbox.style.transform = `rotate(${angle}rad)`;
    hitbox.style.zIndex = '10';
    hitbox.style.pointerEvents = 'auto';
    hitbox.style.background = 'transparent';
}


document.addEventListener("DOMContentLoaded", () => {
    loadData();
    setupButtonListeners();
});

function onConnectionCreated(fromId, toText, type) {
    const [messengerType, messengerValue] = toText.split(': ');
    const messenger = window.messengers.find(m => m.type === messengerType && m.value === messengerValue);

    if (messenger) {
        fetch('/communications/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                emailId: fromId,  // ID почты
                messengerId: messenger.id,  // ID мессенджера
                type: type
            })
        })
            .then(res => res.json())
            .then(data => {
                console.log(data);
                showToast('Связь успешно добавлена!');
            })
            .catch(err => {
                console.error(err);
                showToast('Произошла ошибка при добавлении связи!');
            });
    }
}

function onCommunicationDeleted(fromId, toText) {
    const [messengerType, messengerValue] = toText.split(': ');
    const messenger = window.messengers.find(m => m.type === messengerType && m.value === messengerValue);

    if (messenger) {
        fetch('/communications/delete', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                emailId: fromId,  // ID почты
                messengerId: messenger.id  // ID мессенджера
            })
        })
            .then(res => res.json())
            .then(data => {
                console.log(data);
                showToast('Связь успешно удалена!');
            })
            .catch(err => {
                console.error(err);
                showToast('Произошла ошибка при удалении связи!');
            });
    }
}



function showToast(message) {
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
