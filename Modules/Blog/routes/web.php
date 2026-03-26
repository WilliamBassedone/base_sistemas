<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;

Route::get('blog/turbo', [BlogController::class, 'turboIndex'])->name('blog.turbo');
Route::post('blog/turbo/messages', [BlogController::class, 'turboStore'])->name('blog.turbo.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('blogs', BlogController::class)->names('blog');
});
