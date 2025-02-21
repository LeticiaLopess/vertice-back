<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

Route::middleware(['api', 'cors'])->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/isAuthenticated', [AuthenticatedSessionController::class, 'checkAuth']);
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    });
});
