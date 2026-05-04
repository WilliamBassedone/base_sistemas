<?php

use Illuminate\Support\Facades\Route;
use Modules\Panel\Http\Controllers\PanelController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PanelController::class, 'index'])->name('dashboard');
});
