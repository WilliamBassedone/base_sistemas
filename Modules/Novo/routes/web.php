<?php

use Illuminate\Support\Facades\Route;
use Modules\Novo\Http\Controllers\NovoController;

// 
Route::get('novo/turbo', [NovoController::class, 'turboIndex'])->name('novo.turbo');

// 
Route::post('novo/turbo/messages', [NovoController::class, 'turboStore'])->name('novo.turbo.store');

// 
Route::delete('novo/turbo/messages', [NovoController::class, 'turboClear'])->name('novo.turbo.clear');

// 
Route::delete('novo/turbo/messages/{id}', [NovoController::class, 'turboDestroy'])->name('novo.turbo.destroy');

//
