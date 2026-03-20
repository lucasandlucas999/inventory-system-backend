<?php

use App\Domains\Products\Controllers\DeleteProductController;
use App\Domains\Products\Controllers\IndexCategoriesController;
use App\Domains\Products\Controllers\IndexProductsController;
use App\Domains\Products\Controllers\ShowProductController;
use Illuminate\Support\Facades\Route;
use App\Domains\Products\Controllers\StoreProductController;
use App\Domains\Products\Controllers\UpdateProductController;

Route::prefix('categories')->group(function () {
    Route::get('', [IndexCategoriesController::class, '__invoke']);
});

Route::prefix('products')->group(function () {
    Route::get('', [IndexProductsController::class, '__invoke']);
    Route::get('{product}', [ShowProductController::class, '__invoke']);
    Route::post('', [StoreProductController::class, '__invoke']);
    Route::put('{product}', [UpdateProductController::class, '__invoke']);
    Route::delete('{product}', [DeleteProductController::class, '__invoke']);
});
