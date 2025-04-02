<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\FinanceController;

// Rotas de autenticação
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Grupo de rotas protegidas por autenticação Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'show']);
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::get('/finances', [FinanceController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('update-password', [AuthController::class, 'updatePassword']);
});

