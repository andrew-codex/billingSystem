<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ConsumerAuthController;


Route::prefix('consumer')->group(function () {
   
    Route::post('/login', [ConsumerAuthController::class, 'login']);

    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [ConsumerAuthController::class, 'me']);
        Route::post('/logout', [ConsumerAuthController::class, 'logout']);
    });
});
