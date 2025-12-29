# Support Tickets (Widget + Manager Panel)

Laravel проект: публичный виджет отправки обращений + панель менеджера для просмотра/фильтрации заявок, смены статуса и скачивания вложений.

## Возможности
- Публичный виджет `/widget`:
  - отправка заявки (имя/email/телефон/тема/сообщение)
  - вложения (до  файлов, типы: jpg/jpeg/png/pdf/txt, до 50MB каждый)
- API:
  - `POST /api/tickets` — создание заявки
  - `GET /api/tickets/statistics` — статистика (за день/неделю/месяц)
- Панель менеджера:
  - `/manager/login`
  - список заявок с фильтрами по дате/статусу/email/телефону
  - карточка заявки + вложения + смена статуса (AJAX)
  - скачивание вложений
- Защита:
  - вход только пользователю с ролью `manager` (Spatie Permission)
- Вложения: Spatie MediaLibrary (collection: `attachments`)
login manager
passw password
---

## Требования
- PHP 8.2+
- Composer
- MySQL 8+