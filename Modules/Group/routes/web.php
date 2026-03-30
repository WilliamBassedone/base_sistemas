<?php

use Illuminate\Support\Facades\Route;
use Modules\Group\Http\Controllers\GroupController;

// Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/configuracoes/grupos', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/configuracoes/grupos/cadastrar', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/configuracoes/grupos', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/configuracoes/grupos/{id}/editar', [GroupController::class, 'edit'])->name('groups.edit');
    Route::put('/configuracoes/grupos/{id}', [GroupController::class, 'update'])->name('groups.update');
// });
