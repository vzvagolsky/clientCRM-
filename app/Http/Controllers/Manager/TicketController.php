<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\TicketIndexRequest;
use App\Http\Requests\Manager\TicketUpdateStatusRequest;
use App\Http\Resources\TicketStatusResource;
use App\Models\Ticket;
use App\Services\TicketManagerService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketManagerService $service
    ) {}

    public function index(TicketIndexRequest $request)
    {
        $tickets = $this->service->paginate($request->validatedFilters(), 20);

        return view('manager.tickets.index', [
            'tickets' => $tickets,
            'statuses' => $this->service->statuses(),
        ]);
    }

    public function show(Ticket $ticket)
    {
        $ticket = $this->service->getForShow($ticket->id);
        $attachments = $this->service->attachments($ticket);

        return view('manager.tickets.show', [
            'ticket' => $ticket,
            'attachments' => $attachments,
            'statuses' => $this->service->statuses(),
        ]);
    }

    public function updateStatus(TicketUpdateStatusRequest $request, Ticket $ticket)
    {
        $updated = $this->service->updateStatus($ticket, $request->validated('status'));

        if ($request->expectsJson()) {
            return (new TicketStatusResource($updated))
                ->response()
                ->setStatusCode(200);
        }

        return back()->with('success', 'Статус обновлён.');
    }

    public function downloadMedia(Ticket $ticket, Media $media)
    {
        $this->service->assertTicketMedia($ticket, $media);

        return response()->download($media->getPath(), $media->file_name);
    }
}