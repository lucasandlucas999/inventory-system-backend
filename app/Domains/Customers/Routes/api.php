<?php
use App\Domains\Customers\Controllers\IndexCustomersController;
use Illuminate\Support\Facades\Route;
use App\Domains\Customers\Controllers\ShowCustomerByIdController;
use App\Domains\Customers\Controllers\StoreCustomerController;
use App\Domains\Customers\Controllers\UpdateCustomerController;
use App\Domains\Customers\Controllers\DeleteCustomerController;


Route::prefix('customers')->group(function () {
    Route::get('', [IndexCustomersController::class , '__invoke']);
    Route::get('/{customer}', [ShowCustomerByIdController::class , '__invoke']);
    Route::post('', [StoreCustomerController::class , '__invoke']);
    Route::put('/{customer}', [UpdateCustomerController::class , '__invoke']);
    Route::delete('/{customer}', [DeleteCustomerController::class , '__invoke']);
});