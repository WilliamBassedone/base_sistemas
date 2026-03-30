<?php

use Illuminate\Support\Facades\Route;
use Modules\Token\Http\Controllers\TokenController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('tokens', TokenController::class)->names('token');
});
