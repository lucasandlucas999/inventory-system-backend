<?php

use App\Domains\Purchases\Controllers\IndexSuppliersController;
use App\Domains\Purchases\Controllers\StoreSupplierController;
use App\Domains\Purchases\Controllers\UpdateSupplierController;
use App\Domains\Purchases\Controllers\DeleteSupplierController;
use App\Domains\Purchases\Controllers\IndexPurchaseOrdersController;
use App\Domains\Purchases\Controllers\StorePurchaseOrderController;
use App\Domains\Purchases\Controllers\UpdatePurchaseOrderController;
use App\Domains\Purchases\Controllers\DeletePurchaseOrderController;
use Illuminate\Support\Facades\Route;


Route::prefix('suppliers')->group(function () {
    Route::get('', [IndexSuppliersController::class, '__invoke']);
    Route::post('', [StoreSupplierController::class, '__invoke']);
    Route::put('{supplier}', [UpdateSupplierController::class, '__invoke']);
    Route::delete('{supplier}', [DeleteSupplierController::class, '__invoke']);
});

Route::prefix('purchase-orders')->group(function () {
    Route::get('', [IndexPurchaseOrdersController::class, '__invoke']);
    Route::post('', [StorePurchaseOrderController::class, '__invoke']);
    Route::put('{purchaseOrder}', [UpdatePurchaseOrderController::class, '__invoke']);
    Route::delete('{purchaseOrder}', [DeletePurchaseOrderController::class, '__invoke']);
});
