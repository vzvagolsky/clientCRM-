<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\Manager\TicketController;
use App\Http\Controllers\Manager\Auth\LoginController;




Route::get('/manager/login', [LoginController::class, 'show'])
    ->name('manager.login');

Route::post('/manager/login', [LoginController::class, 'login'])
    ->name('manager.login.post');

Route::post('/manager/logout', [LoginController::class, 'logout'])
    ->name('manager.logout');


Route::middleware(['auth', 'role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {

        Route::get('/tickets', [TicketController::class, 'index'])
            ->name('tickets.index');

        Route::get('/tickets/{ticket}', [TicketController::class, 'show'])
            ->name('tickets.show');

        Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
            ->name('tickets.status');

        Route::get('/tickets/{ticket}/media/{media}', [TicketController::class, 'downloadMedia'])
            ->name('tickets.media.download');
    });


Route::get('/widget', [WidgetController::class, 'show'])->name('widget.show');

