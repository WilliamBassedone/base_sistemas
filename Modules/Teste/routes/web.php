<?php

use Illuminate\Support\Facades\Route;
use Modules\Teste\Http\Controllers\TesteController;

Route::middleware('auth')->group(function () {
    Route::get('/configuracoes', [TesteController::class, 'index'])->name('configuracoes');

    Route::resource('testes', TesteController::class)->names('teste');
});
