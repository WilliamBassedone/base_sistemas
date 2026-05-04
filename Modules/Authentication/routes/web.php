<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentication\Http\Controllers\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::get('/autenticacao', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/autenticacao', [AuthenticatedSessionController::class, 'store'])->name('authentication.store');
});

Route::post('/autenticacao/sair', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
