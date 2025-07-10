<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\Auth\AuthApiController;

// prefix api
// Route for Google login
Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user/{idOrEmail}', [UserController::class, 'show']);
    Route::patch('/user/{idOrEmail}', [UserController::class, 'update']);

    // Route for categories
    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/{idCategoryOrSlug}', [CategoryController::class, 'show']);
    Route::post('/category', [CategoryController::class, 'store']);

    // route province
    Route::get('/province', [ProvinceController::class, 'index']);
    Route::get('/province/{province}', [ProvinceController::class, 'show']);

    // route city
    Route::get('/city', [CityController::class, 'index']);
    Route::get('/city/{idCityOrSlug}', [CityController::class, 'show']);

    Route::get('/item', [ItemController::class, 'index']);
    Route::post('/item', [ItemController::class, 'store']);
    Route::get('/item/{id}', [ItemController::class, 'show']);
    Route::patch('/item/{id}', [ItemController::class, 'update']);
    Route::post('/item/{idItem}/images', [ImageController::class, 'store']);
    Route::patch('/item/{idItem}/found', [ItemController::class, 'markAsFound']);

    Route::prefix('comment')->group(function () {
        // Create a new comment (parent or reply)
        Route::post('/', [CommentController::class, 'store']);

        // Get comments by item with replies (infinite scroll)
        Route::get('/getByItem/{idItem}', [CommentController::class, 'getByItem']);

        // Update a comment
        Route::patch('/{idComment}', [CommentController::class, 'update']);

        // Delete a comment
        Route::delete('/{idComment}', [CommentController::class, 'destroy']);
    });
});
