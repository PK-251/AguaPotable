<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DeudaController;
use App\Http\Controllers\Api\V1\MeController;
use App\Http\Controllers\Api\V1\PagoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login'])->name('api.v1.auth.login');
    Route::post('auth/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->name('api.v1.auth.logout');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [MeController::class, 'show'])->name('api.v1.me');
        Route::get('deuda', [DeudaController::class, 'show'])->name('api.v1.deuda');
        Route::get('pagos', [PagoController::class, 'index'])->name('api.v1.pagos');
    });
});
