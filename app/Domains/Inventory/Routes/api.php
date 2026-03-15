<?php

use App\Domains\Inventory\Controllers\IndexStockMovementsController;
use Illuminate\Support\Facades\Route;


Route::prefix('stock-movements')->group(function () {
    Route::get('', [IndexStockMovementsController::class, '__invoke']);
});
