<?php

use Illuminate\Support\Facades\Route;
use Modules\Token\Http\Controllers\TokenController;

// Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/configuracoes/tokens', [TokenController::class, 'index'])->name('tokens.index');
    Route::get('/configuracoes/tokens/cadastrar', [TokenController::class, 'create'])->name('tokens.create');
    Route::post('/configuracoes/tokens', [TokenController::class, 'store'])->name('tokens.store');
    Route::get('/configuracoes/tokens/{id}/editar', [TokenController::class, 'edit'])->name('tokens.edit');
    Route::put('/configuracoes/tokens/{id}', [TokenController::class, 'update'])->name('tokens.update');
// });
