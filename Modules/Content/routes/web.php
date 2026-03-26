<?php

use Illuminate\Support\Facades\Route;
use Modules\Content\Http\Controllers\ContentController;

Route::get('/conteudos', [ContentController::class, 'index'])->name('contents.index');
