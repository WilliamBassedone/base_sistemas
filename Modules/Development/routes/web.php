<?php

use Illuminate\Support\Facades\Route;
use Modules\Development\Http\Controllers\DevelopmentController;

Route::middleware('auth')->group(function () {
    Route::get('/desenvolvimento/componentes', [DevelopmentController::class, 'index'])->name('components.index');
    Route::get('/desenvolvimento/componentes/cadastrar', [DevelopmentController::class, 'create'])->name('components.create');
    Route::get('/desenvolvimento/componentes/configurar', [DevelopmentController::class, 'builder'])->name('components.builder');
});
