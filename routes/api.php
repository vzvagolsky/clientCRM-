<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TicketStatisticsController;

Route::post('/tickets', [TicketController::class, 'store']);
Route::get('/tickets/statistics', TicketStatisticsController::class);
