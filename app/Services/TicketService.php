<?php
namespace App\Services;

use App\Models\Ticket;
use App\Repositories\CustomerRepository;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;


class TicketService
{
    public function __construct(
        private CustomerRepository $customers,
        private TicketRepository $tickets
    ) {}

    public function createTicket(array $payload, array $attachments = []): Ticket
    {
        return DB::transaction(function () use ($payload, $attachments) {

            $customer = $this->customers->findOrCreateByContacts(
                email: $payload['email'],
                phone: $payload['phone'],
                name:  $payload['name'],
            );

            $ticket = $this->tickets->create([
                'customer_id' => $customer->id,
                'subject'     => $payload['subject'],
                'message'     => $payload['message'],
                'status'      => 'new',
            ]);

            // ✅ Загрузка файлов (только реальные UploadedFile)
            foreach ($attachments as $file) {
                if ($file instanceof UploadedFile) {
                    $ticket->addMedia($file)->toMediaCollection('attachments');
                }
            }

            return $ticket->load('customer', 'media');
        });
    }
}