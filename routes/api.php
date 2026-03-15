<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestingGetController;
use App\Http\Controllers\TestingPostController;
use App\Http\Controllers\GetUsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatabaseHealthController;

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

// Domain Routes with authentication
Route::middleware('auth:api')->group(function () {
    // Customers Domain
    require __DIR__ . '/../app/Domains/Customers/Routes/api.php';

    // Products Domain
    require __DIR__ . '/../app/Domains/Products/Routes/api.php';

    // Purchases Domain
    require __DIR__ . '/../app/Domains/Purchases/Routes/api.php';

    // Sales Domain
    require __DIR__ . '/../app/Domains/Sales/Routes/api.php';

    // Inventory Domain
    require __DIR__ . '/../app/Domains/Inventory/Routes/api.php';
}); 