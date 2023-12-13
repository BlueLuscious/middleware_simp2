<?php

use App\Http\Controllers\DebtController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api'])->group(function () {
    Route::resource('/debts', DebtController::class)->only('index', 'show');
    Route::get('/get-debts', [DebtController::class, 'createDebt']);
    Route::get('/show-debts', [DebtController::class, 'showDebt']);
});