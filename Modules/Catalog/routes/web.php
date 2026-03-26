<?php

use Illuminate\Support\Facades\Route;
use Modules\Catalog\Http\Controllers\CatalogController;

// Route::middleware(['auth', 'verified'])->group(function () {
// Route::resource('catalogs', CatalogController::class)->names('catalog');
    
// });

Route::get('/catalog', fn () => 'Catalog ok')->name('catalog.index');