<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;  // Import the controller

// Routes for authenticated users only (protected by Sanctum)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('services', ServiceController::class)->except(['index', 'show']);
    Route::post('services/import', [ServiceController::class, 'importServices']);
});

// Public routes (accessible without authentication)
Route::apiResource('services', ServiceController::class)->only(['index', 'show']);

require __DIR__.'/auth.php';
