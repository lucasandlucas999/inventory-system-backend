<?php

use App\Domains\Sales\Controllers\IndexInvoicesController;
use Illuminate\Support\Facades\Route;


Route::prefix('invoices')->group(function () {
    Route::get('', [IndexInvoicesController::class, '__invoke']);
});
