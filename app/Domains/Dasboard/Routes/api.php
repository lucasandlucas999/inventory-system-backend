<?php
use Illuminate\Support\Facades\Route;
use App\Domains\Dasboard\Controllers\DashboardController;

Route::prefix('dashboard')->group(function () {
    Route::get('', [DashboardController::class , '__invoke']);
});