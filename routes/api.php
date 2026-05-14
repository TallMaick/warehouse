<?php

use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\FincaApiController;

// //Rutas que no Necesitan el Tolen
// Route::prefix('auth')->group(function () {
//     Route::post('/login', [AuthApiController::class, 'login']);
// });

// //Rutas que Requieren el Token Bearer
// Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
//     Route::get('/me',          [AuthApiController::class, 'me']);
//     Route::post('/logout',     [AuthApiController::class, 'logout']);
//     Route::post('/logout-all', [AuthApiController::class, 'logoutAll']);
// });

// RUTA PÚBLICA (No requiere Token)
// Aquí es donde Flutter envía el correo y la contraseña para obtener acceso.
Route::post('/login', [AuthApiController::class, 'login']);

// RUTAS PROTEGIDAS (Requieren Token Bearer)
// Todo lo que esté dentro de este grupo exige que Flutter envíe un token válido.
Route::middleware('auth:sanctum')->group(function () {
    
    // -- Módulo de Usuario y Sesión --
    Route::get('/me', [AuthApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::post('/logout-all', [AuthApiController::class, 'logoutAll']);

    // -- Módulo de Producción Agrícola (Data Warehouse) --
    // Flutter pide el cascarón de la finca
    Route::get('/mis-fincas', [FincaApiController::class, 'misFincas']); 
    
    // Flutter envía el GPS, hectáreas y tipo de suelo
    Route::put('/fincas/{id}/completar', [FincaApiController::class, 'completarPerfil']); 

});