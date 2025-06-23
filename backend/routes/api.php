<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CnabController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user-profile', [AuthController::class, 'userProfile']);

    // CNAB routes
    Route::get('cnab', [CnabController::class, 'index']);
    Route::post('cnab', [CnabController::class, 'store']);
    Route::get('cnab/{id}', [CnabController::class, 'show']);
    Route::get('cnab/{id}/excel', [CnabController::class, 'downloadExcel']);
    Route::get('cnab/{id}/cnab', [CnabController::class, 'downloadCnab']);
    Route::get('funds', [CnabController::class, 'getFunds']);

    // User routes (admin only)
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
}); 