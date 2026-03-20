<?php
use App\Domains\Customers\Controllers\IndexCustomersController;
use App\Domains\Customers\Controllers\RegisterCustomerController;
use Illuminate\Support\Facades\Route;


Route::prefix('customers')->group(function () {
    Route::get('', [IndexCustomersController::class , '__invoke']);
    Route::post('', [RegisterCustomerController::class , '__invoke']);
});