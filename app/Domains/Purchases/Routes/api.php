<?php

use App\Domains\Purchases\Controllers\IndexSuppliersController;
use App\Domains\Purchases\Controllers\IndexPurchaseOrdersController;
use Illuminate\Support\Facades\Route;


Route::prefix('suppliers')->group(function () {
    Route::get('', [IndexSuppliersController::class, '__invoke']);
});

Route::prefix('purchase-orders')->group(function () {
    Route::get('', [IndexPurchaseOrdersController::class, '__invoke']);
});
