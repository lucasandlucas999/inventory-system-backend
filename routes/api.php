<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestingGetController;
use App\Http\Controllers\TestingPostController;
use App\Http\Controllers\GetUsersController;

Route::prefix('health')->group(function () {
    Route::get('', [TestingGetController::class , '__invoke']);
    Route::post('', [TestingPostController::class , '__invoke']);
    Route::get('users', [GetUsersController::class , '__invoke']);
});