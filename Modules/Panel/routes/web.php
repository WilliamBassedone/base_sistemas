<?php

use Illuminate\Support\Facades\Route;
use Modules\Panel\Http\Controllers\PanelController;

Route::get('/dashboard', [PanelController::class, 'index'])->name('dashboard');
