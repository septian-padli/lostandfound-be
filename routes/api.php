<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthApiController;

// prefix api
// Route for Google login
Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user/{idOrEmail}', [UserController::class, 'show']);
    Route::patch('/user/{idOrEmail}', [UserController::class, 'update']);
});
