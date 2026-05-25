<?php

use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\FincaApiController;
use App\Http\Controllers\Api\LoteApiController;
use App\Http\Controllers\Api\ActividadApiController;
use App\Http\Controllers\Api\MultimediaApiController;
use App\Http\Controllers\Api\LecturaIotApiController;

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

// RUTA PARA EL SENSOR (POST): El hardware envía los datos aquí de forma directa
Route::post('/iot/lecturas', [LecturaIotApiController::class, 'store']);

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

    // 🚀 NUEVA: El usuario solicita vincular una finca adicional
    Route::post('/fincas/solicitar', [FincaApiController::class, 'solicitar']);
    
    // Flutter envía el GPS, hectáreas y tipo de suelo
    Route::put('/fincas/{id}/completar', [FincaApiController::class, 'completarPerfil']); 

    // Flutter envía archivos multimedia al Data Lake
    Route::post('/fincas/{id}/multimedia', [FincaApiController::class, 'subirMultimedia']);

    // Rutas para Lotes y Actividades (NUEVAS)
    Route::get('/fincas/{finca_id}/lotes', [LoteApiController::class, 'index']);
    Route::post('/fincas/{finca_id}/lotes', [LoteApiController::class, 'store']); // Flutter crea un lote nuevo
    Route::get('/lotes/{lote_id}/actividades', [ActividadApiController::class, 'index']); // <-- NUEVA
    Route::post('/lotes/{lote_id}/actividades', [ActividadApiController::class, 'store']);

    Route::post('/multimedia/subir', [MultimediaApiController::class, 'store']);

    // Flutter consulta las métricas del lote para dibujar las gráficas
    Route::get('/lotes/{lote_id}/lecturas', [LecturaIotApiController::class, 'index']);

});