<?php
namespace App\Services;

use App\Models\Ticket;
use App\Repositories\TicketRepository;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TicketManagerService
{
    public function __construct(private readonly TicketRepository $repo) {}

    public function statuses(): array
    {
        return config('tickets.statuses', ['new','in_progress','done','rejected']);
    }

    public function paginate(array $filters, int $perPage = 20)
    {
        return $this->repo->paginateForManager($filters, $perPage);
    }

    public function getForShow(int $ticketId): Ticket
    {
        return $this->repo->findForShow($ticketId);
    }

    public function attachments(Ticket $ticket)
    {
        return $ticket->getMedia(Ticket::MEDIA_ATTACHMENTS);
    }

    public function updateStatus(Ticket $ticket, string $status): Ticket
    {
        $answeredAt = $ticket->answered_at;

        if ($status === 'done' && $answeredAt === null) {
            $answeredAt = now();
        }

        return $this->repo->updateStatus($ticket, [
            'status' => $status,
            'answered_at' => $answeredAt,
        ]);
    }

    public function assertTicketMedia(Ticket $ticket, Media $media): void
    {
        if ($media->model_type !== Ticket::class || (int)$media->model_id !== (int)$ticket->id) {
            abort(404);
        }
        if ($media->collection_name !== Ticket::MEDIA_ATTACHMENTS) {
            abort(404);
        }
    }
}