<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestingGetController;
use App\Http\Controllers\TestingPostController;

Route::prefix('health')->group(function () {
    Route::get('', [TestingGetController::class , '__invoke']);
    Route::post('', [TestingPostController::class , '__invoke']);
});