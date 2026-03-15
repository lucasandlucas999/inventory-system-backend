<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestingGetController;
use App\Http\Controllers\TestingPostController;
use App\Http\Controllers\GetUsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatabaseHealthController;
use App\Domains\Customers\Controllers\IndexCustomersController;

Route::prefix('health')->group(function () {
    Route::get('', [TestingGetController::class , '__invoke']);
    Route::post('', [TestingPostController::class , '__invoke']);
    Route::get('db', [DatabaseHealthController::class , '__invoke']);
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class , 'login']);
    Route::post('logout', [AuthController::class , 'logout']);
    Route::post('refresh', [AuthController::class , 'refresh']);
    Route::post('me', [AuthController::class , 'me']);
});

Route::prefix('customers')->group(function () {
    Route::get('', [IndexCustomersController::class , '__invoke']);
}); 