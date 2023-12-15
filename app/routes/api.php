<?php

use App\Http\Controllers\ConektaController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\RequestDebtController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api'])->group(function () {
    Route::resource('/debts', DebtController::class)->only('index', 'show');
    Route::get('/get-debts', [RequestDebtController::class, 'createDebt']);
    Route::get('/show-debts', [RequestDebtController::class, 'showDebt']);
    Route::get('/checkouts/{debtId}', [ConektaController::class, 'getPaymentLinkConekta']);
    // Route::post(env('CONEKTA_URL_BASE').'/checkouts/{debtId}', [ConektaController::class, 'postDebtToConekta']);
});