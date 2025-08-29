<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SyncController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/sync/receive', [SyncController::class, 'receive']);
});
