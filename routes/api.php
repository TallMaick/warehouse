<?php

use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

//Rutas que no Necesitan el Tolen
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login']);
});

//Rutas que Requieren el Token Bearer
Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::get('/me',          [AuthApiController::class, 'me']);
    Route::post('/logout',     [AuthApiController::class, 'logout']);
    Route::post('/logout-all', [AuthApiController::class, 'logoutAll']);
});