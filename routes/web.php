<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\FormController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/formulario', function () {
    return view('formulario');
});

// Asumiendo que tu formulario está en la ruta raíz '/' o '/formulario'
Route::post('/solicitud', [FormController::class, 'store'])->name('solicitud.store');