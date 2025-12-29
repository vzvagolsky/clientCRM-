<?php
namespace App\Services;

use App\Models\Ticket;
use App\Repositories\CustomerRepository;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        private CustomerRepository $customers,
        private TicketRepository $tickets
    ) {}

    public function createTicket(array $payload): Ticket
    {
        return DB::transaction(function () use ($payload) {

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

            // 🔹 Загрузка файлов
            if (!empty($payload['attachments'])) {
                foreach ($payload['attachments'] as $file) {
                    $ticket->addMedia($file)->toMediaCollection('attachments');
                }
            }

            if (!$ticket->exists) {
                throw new \RuntimeException('Ticket was not saved');
            }

            return $ticket->load('customer', 'media');
        });
    }
}