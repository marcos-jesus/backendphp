<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['jwt.verify']], function() {

    Route::get('produtos', [ProductController::class, 'index']);
    Route::get('produtos/{id}', [ProductController::class, 'show']);
    Route::post('produtos', [ProductController::class, 'store']);
    Route::put('produtos/{id}', [ProductController::class, 'update']);
    Route::delete('produtos/{id}', [ProductController::class, 'destroy']);

    Route::post('cart', [CartController::class, 'store']);
    Route::get('cart', [CartController::class, 'index']);
    Route::delete('cart/{id}', [CartController::class, 'destroy']);
});
