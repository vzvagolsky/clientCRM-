<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Widget</title>
    <style>
        body { margin:0; font-family: Arial, sans-serif; }
        .box { max-width: 520px; padding: 14px; }
        label { display:block; margin: 10px 0 4px; font-size: 13px; }
        input, textarea { width:100%; padding: 8px; box-sizing: border-box; }
        textarea { min-height: 90px; }
        button { margin-top: 12px; padding: 10px 12px; cursor: pointer; }
        .msg { margin-top: 12px; padding: 10px; display:none; }
        .ok { background: #e7fff1; }
        .err { background: #ffe7e7; }
        ul { margin: 8px 0 0; padding-left: 18px; }
        code { background:#f3f4f6; padding:2px 5px; }
    </style>
</head>
<body>
<div class="box">
    <h3>Обратная связь</h3>

    <form id="ticketForm">
        <label>Имя</label>
        <input name="name" required>

        <label>Email</label>
        <input name="email" type="email" required>

        <label>Телефон (E.164)</label>
        <input name="phone" placeholder="+491761234567" required>

        <label>Тема</label>
        <input name="subject" required>

        <label>Сообщение</label>
        <textarea name="message" required></textarea>

        <label>Файлы (необязательно)</label>
        <input name="attachments[]" type="file" multiple>

        <button id="submitBtn" type="submit">Отправить</button>

        <div id="okMsg" class="msg ok"></div>
        <div id="errMsg" class="msg err">
            <div id="errText"></div>
            <ul id="errList"></ul>
        </div>

        <p style="font-size:12px; color:#666;">
            Встраивание: <code>&lt;iframe src="https://your-domain.com/widget"&gt;&lt;/iframe&gt;</code>
        </p>
    </form>
</div>

<script>
    const form = document.getElementById('ticketForm');
    const btn = document.getElementById('submitBtn');
    const okMsg = document.getElementById('okMsg');
    const errMsg = document.getElementById('errMsg');
    const errText = document.getElementById('errText');
    const errList = document.getElementById('errList');

    function showOk(text) {
        okMsg.textContent = text;
        okMsg.style.display = 'block';
        errMsg.style.display = 'none';
    }

    function showErr(text, errors = null) {
        errText.textContent = text;
        errList.innerHTML = '';
        if (errors) {
            Object.keys(errors).forEach((field) => {
                errors[field].forEach((m) => {
                    const li = document.createElement('li');
                    li.textContent = m;
                    errList.appendChild(li);
                });
            });
        }
        errMsg.style.display = 'block';
        okMsg.style.display = 'none';
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        btn.disabled = true;
        okMsg.style.display = 'none';
        errMsg.style.display = 'none';

        try {
            // FormData нужен, чтобы поддержать файлы
            const fd = new FormData(form);

            const res = await fetch('/api/tickets', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd
            });

            const data = await res.json().catch(() => ({}));

            if (res.ok) {
                showOk('Заявка отправлена. Спасибо!');
                form.reset();
                return;
            }

            if (res.status === 422 && data.errors) {
                showErr('Ошибки валидации:', data.errors);
                return;
            }

            showErr(data.message || 'Ошибка отправки. Попробуйте позже.');

        } catch (error) {
            showErr('Сеть недоступна или сервер не отвечает.');
        } finally {
            btn.disabled = false;
        }
    });
</script>
</body>
</html>