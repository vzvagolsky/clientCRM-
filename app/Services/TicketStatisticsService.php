<?php

namespace App\Services;

use App\Repositories\TicketRepository;

class TicketStatisticsService
{
    public function __construct(
        private readonly TicketRepository $tickets
    ) {}

    public function getStatistics(): array
    {
        return $this->tickets->statistics();
    }
}