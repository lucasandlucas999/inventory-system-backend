<?php

use App\Domains\AI\Controllers\ChatAIController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('ai')->group(function () {
    Route::post('chat', [ChatAIController::class, '__invoke']);
});
