<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryController;

    Route::resource('inventories', InventoryController::class)->names('inventory');
