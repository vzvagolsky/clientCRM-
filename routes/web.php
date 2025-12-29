<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WidgetController;


Route::get('/widget', [WidgetController::class, 'show'])->name('widget.show');

