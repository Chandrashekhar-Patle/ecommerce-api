<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

// Public routes — anyone can browse products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Protected routes — must be logged in
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json([
        'user' => $request->user()
    ]);
});
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::patch('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
});