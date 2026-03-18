<?php

use App\Domains\Products\Controllers\IndexCategoriesController;
use App\Domains\Products\Controllers\IndexProductsController;
use App\Domains\Products\Controllers\ShowProductController;
use Illuminate\Support\Facades\Route;


Route::prefix('categories')->group(function () {
    Route::get('', [IndexCategoriesController::class, '__invoke']);
});

Route::prefix('products')->group(function () {
    Route::get('', [IndexProductsController::class, '__invoke']);
});


Route::prefix('products')->group(function () {
    Route::get('{product}', [ShowProductController::class, '__invoke']);
});