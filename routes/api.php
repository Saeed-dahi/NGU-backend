<?php

use App\Http\Controllers\ClosingAccountController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('/login', [LoginController::class, 'loginUserApi']);

    Route::middleware(['auth:sanctum', 'language_middleware'])->group(function () {

        Route::get('/profile', function (Request $request) {
            return  $request->user();
        });

        Route::apiResource('closing-account', ClosingAccountController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    });
});
