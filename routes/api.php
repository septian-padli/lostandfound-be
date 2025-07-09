<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthApiController;

// prefix api
Route::prefix('api')->group(function () {
    // Route for Google login
    Route::post('/login', [AuthApiController::class, 'login']);
});
