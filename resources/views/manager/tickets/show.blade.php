@extends('manager.layout')

@section('title', 'Заявка #' . $ticket->id)

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div style="max-width: 900px; margin: 0 auto;">
    <div style="display:flex; justify-content: space-between; align-items:center; gap:12px; margin-bottom: 12px;">
        <h2 style="margin:0;">Заявка #{{ $ticket->id }}</h2>
        <a href="{{ route('manager.tickets.index') }}">← к списку</a>
    </div>

    <div id="flashBox">
        @if(session('success'))
            <div style="margin:12px 0; padding:10px; border:1px solid #3a3; background:#efe;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    {{-- Инфо по заявке --}}
    <div style="border: 1px solid #ddd; padding: 12px;">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
            <div><b>Статус:</b> <span id="currentStatus">{{ $ticket->status }}</span></div>
            <div><b>Создано:</b> {{ $ticket->created_at?->format('d.m.Y H:i') }}</div>
            <div><b>Answered at:</b> <span id="answeredAt">{{ $ticket->answered_at?->format('d.m.Y H:i') ?? '—' }}</span></div>
            <div><b>Обновлено:</b> {{ $ticket->updated_at?->format('d.m.Y H:i') }}</div>
        </div>

        <hr style="margin:12px 0;">

        <div><b>Тема:</b> {{ $ticket->subject }}</div>

        <div style="margin-top:10px;">
            <b>Сообщение:</b>
            <div style="white-space: pre-wrap; border:1px solid #eee; padding:10px; margin-top:6px;">
                {{ $ticket->message }}
            </div>
        </div>

        <hr style="margin:12px 0;">

        <div><b>Клиент:</b> {{ $ticket->customer?->name ?? ('Customer #' . $ticket->customer_id) }}</div>
        <div><b>Email:</b> {{ $ticket->customer?->email ?? '—' }}</div>
        <div><b>Телефон:</b> {{ $ticket->customer?->phone ?? '—' }}</div>
    </div>

    {{-- Файлы --}}
    <div style="margin-top: 14px; border: 1px solid #ddd; padding: 12px;">
        <h3 style="margin:0 0 8px;">Файлы</h3>

        @if($attachments->isEmpty())
            <div>Файлов нет.</div>
        @else
            <ul style="margin:0; padding-left:18px;">
                @foreach($attachments as $media)
                    <li style="margin: 6px 0;">
                        {{ $media->file_name }}
                        ({{ number_format($media->size / 1024, 1) }} KB)
                        — <a href="{{ route('manager.tickets.media.download', [$ticket->id, $media->id]) }}">Скачать</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Смена статуса (AJAX) --}}
    <div style="margin-top: 14px; border: 1px solid #ddd; padding: 12px;">
        <h3 style="margin:0 0 10px;">Смена статуса</h3>

        <div style="display:flex; gap:10px; align-items:center;">
            <select id="statusSelect">
                @foreach(($statuses ?? []) as $st)
                    <option value="{{ $st }}" @selected($ticket->status === $st)>{{ $st }}</option>
                @endforeach
            </select>

            <button type="button" id="saveStatusBtn">Сохранить</button>

            <span id="statusMsg" style="margin-left:10px;"></span>
        </div>

        <div id="statusError" style="color:#b00; margin-top:8px;"></div>
    </div>
</div>

<script>
(function () {
    const btn = document.getElementById('saveStatusBtn');
    const select = document.getElementById('statusSelect');
    const msg = document.getElementById('statusMsg');
    const errBox = document.getElementById('statusError');
    const currentStatus = document.getElementById('currentStatus');
    const answeredAt = document.getElementById('answeredAt');

    const url = @json(route('manager.tickets.status', $ticket));
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function setMsg(text, isError = false) {
        msg.textContent = text;
        msg.style.color = isError ? '#b00' : '#060';
    }

    function clearError() {
        errBox.textContent = '';
    }

    btn.addEventListener('click', async () => {
        btn.disabled = true;
        clearError();
        setMsg('Сохраняю...');

        try {
            const res = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                credentials: 'same-origin',
                body: JSON.stringify({ status: select.value })
            });

            if (!res.ok) {
                const data = await res.json().catch(() => null);

                if (res.status === 401) {
                    window.location.href = @json(route('manager.login'));
                    return;
                }

                const first = data?.errors ? Object.values(data.errors)[0]?.[0] : null;
                errBox.textContent = first || data?.message || 'Ошибка сохранения';
                setMsg('Ошибка', true);
                return;
            }

            const data = await res.json();
            const payload = data.data || data;

            currentStatus.textContent = payload.status;

            if (payload.answered_at) {
                const d = new Date(payload.answered_at);
                answeredAt.textContent = d.toLocaleString();
            }

            setMsg('Готово ✅');
        } catch (e) {
            setMsg('Сеть/сервер недоступен', true);
        } finally {
            btn.disabled = false;
        }
    });
})();
</script>
@endsection