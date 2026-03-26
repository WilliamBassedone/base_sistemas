<?php

use Illuminate\Support\Facades\Route;
use Modules\Novo\Http\Controllers\NovoController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('novos', NovoController::class)->names('novo');
});
