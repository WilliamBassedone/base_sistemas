<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

// Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/configuracoes/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::get('/configuracoes/usuarios/cadastrar', [UserController::class, 'create'])->name('users.create');
    Route::post('/configuracoes/usuarios', [UserController::class, 'store'])->name('users.store');
    Route::get('/configuracoes/usuarios/{id}/visualizar', [UserController::class, 'show'])->name('users.show');
    Route::get('/configuracoes/usuarios/{id}/editar', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/configuracoes/usuarios/{id}', [UserController::class, 'update'])->name('users.update');
// });
