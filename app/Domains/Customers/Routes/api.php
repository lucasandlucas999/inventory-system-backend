<?php
use App\Domains\Customers\Controllers\IndexCustomersController;
use Illuminate\Support\Facades\Route;


Route::prefix('customers')->group(function () {
    Route::get('', [IndexCustomersController::class , '__invoke']);
});