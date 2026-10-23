<?php

use Illuminate\Support\Facades\Route;
use App\Adapters\Http\Controllers\AuthController;
use App\Adapters\Http\Controllers\BookController;
use App\Adapters\Http\Controllers\MemberController;
use App\Adapters\Http\Controllers\LoanController;

Route::prefix('v1')->group(function () {
    // Ruta pública de autenticación
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Rutas protegidas con JWT
    Route::middleware(['jwt.auth'])->group(function () {
        Route::get('/books', [BookController::class, 'index']);
        Route::post('/books', [BookController::class, 'store']);
        
        Route::get('/members', [MemberController::class, 'index']);
        Route::post('/members', [MemberController::class, 'store']);
        
        Route::post('/loans', [LoanController::class, 'store']);
    });
});