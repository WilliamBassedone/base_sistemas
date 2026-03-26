<?php

use App\Http\Controllers\AudioTriageController;
use App\Http\Controllers\GeminiModelsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/sobre', function () {
    return view('about');
});

Route::get('/audio/gravador', function () {
    return view('record');
});

Route::get('/gemini/models', GeminiModelsController::class);
Route::post('/audio/triage', AudioTriageController::class);
