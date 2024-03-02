<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::post('/clientes/{id}/transacoes',[ClientController::class, 'storeTransaction']);
Route::get('/clientes/{id}/extrato', [ClientController::class, 'getExtract']);
