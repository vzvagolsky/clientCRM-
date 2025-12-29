<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; 

class TicketController extends Controller

{
	
	
    public function __construct(private TicketService $service) {}
	
   
		public function store(StoreTicketRequest $request): JsonResponse
       {
    $data  = $request->validated();                 // текстовые поля
    $files = $request->file('attachments', []);     // файлы отдельно

    $ticket = $this->service->createTicket($data, $files);

    return (new TicketResource($ticket))
        ->response()
        ->setStatusCode(201);
      }
		
   
	 
	
}