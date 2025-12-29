<?php
namespace App\Repositories;

use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TicketRepository
{
    /* =========================
     |  Создание заявки (у тебя уже было)
     ========================= */
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    /* =========================
     |  Админка: список заявок с фильтрами
     ========================= */
    public function paginateForManager(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Ticket::query()->with('customer');

        if (!empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['email'])) {
            $query->whereHas('customer', function ($q) use ($filters) {
                $q->where('email', 'like', '%' . $filters['email'] . '%');
            });
        }

        if (!empty($filters['phone'])) {
            $query->whereHas('customer', function ($q) use ($filters) {
                $q->where('phone', 'like', '%' . $filters['phone'] . '%');
            });
        }

        return $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /* =========================
     |  Админка: просмотр одной заявки
     ========================= */
    public function findForShow(int $ticketId): Ticket
    {
        return Ticket::query()
            ->with('customer')
            ->findOrFail($ticketId);
    }

    /* =========================
     |  Админка: смена статуса
     ========================= */
    public function updateStatus(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);
        return $ticket->refresh();
    }

    /* =========================
     |  API: статистика
     ========================= */
    public function statistics(): array
    {
        return [
            'day'   => Ticket::query()->lastDay()->count(),
            'week'  => Ticket::query()->lastWeek()->count(),
            'month' => Ticket::query()->lastMonth()->count(),
        ];
    }
}