<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\TicketController;

Route::get('/flights', [FlightController::class, 'index']);

Route::post('/tickets', [TicketController::class, 'create']);
Route::delete('/tickets/{id}', [TicketController::class, 'cancel']);
Route::put('/tickets/{ticket}/seat', [TicketController::class, 'updateSeat']);