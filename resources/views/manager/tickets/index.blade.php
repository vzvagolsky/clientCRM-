@extends('manager.layout')

@section('title', 'Заявки')

@section('content')
<div style="max-width: 1100px; margin: 0 auto;">
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 12px;">
        <h2 style="margin:0;">Заявки</h2>
    </div>

    {{-- Фильтры --}}
    <form method="GET" action="{{ route('manager.tickets.index') }}"
          style="display:grid; grid-template-columns: repeat(6, 1fr); gap:10px; align-items:end; margin-bottom: 16px;">

        <div>
            <label style="display:block; font-size: 14px;">Дата от</label>
            <input type="date" name="from" value="{{ request('from') }}" style="width:100%;">
        </div>

        <div>
            <label style="display:block; font-size: 14px;">Дата до</label>
            <input type="date" name="to" value="{{ request('to') }}" style="width:100%;">
        </div>

        <div>
            <label style="display:block; font-size: 14px;">Статус</label>
            <select name="status" style="width:100%;">
                <option value="">— все —</option>
                @foreach(($statuses ?? []) as $st)
                    <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display:block; font-size: 14px;">Email</label>
            <input type="text" name="email" value="{{ request('email') }}" placeholder="например, test@"
                   style="width:100%;">
        </div>

        <div>
            <label style="display:block; font-size: 14px;">Телефон (E.164)</label>
            <input type="text" name="phone" value="{{ request('phone') }}" placeholder="+49123456789"
                   style="width:100%;">
        </div>

        <div style="display:flex; gap:8px;">
            <button type="submit">Фильтр</button>
            <a href="{{ route('manager.tickets.index') }}">Сброс</a>
        </div>
    </form>

    {{-- Таблица --}}
    <div style="overflow-x:auto;">
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
            <thead>
            <tr>
                <th>ID</th>
                <th>Дата</th>
                <th>Статус</th>
                <th>Тема</th>
                <th>Email</th>
                <th>Телефон</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->created_at?->format('d.m.Y H:i') }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>{{ $ticket->customer?->email ?? '—' }}</td>
                    <td>{{ $ticket->customer?->phone ?? '—' }}</td>
                    <td>
                        <a href="{{ route('manager.tickets.show', $ticket) }}">Открыть</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Ничего не найдено.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Пагинация --}}
    <div style="margin-top: 12px;">
        {{ $tickets->links() }}
    </div>
</div>
@endsection