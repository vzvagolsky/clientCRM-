<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketStatisticsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'last_day'   => $this['day'],
            'last_week'  => $this['week'],
            'last_month' => $this['month'],
        ];
    }
}