<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Finca;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FincaApiController extends Controller
{
    /**
     * Obtener las fincas del usuario autenticado
     */
    public function misFincas(Request $request): JsonResponse
    {
        // $request->user() obtiene al usuario gracias al Token de Sanctum
        $fincas = Finca::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Fincas recuperadas con éxito',
            'data'    => $fincas
        ], 200);
    }

    /**
     * Completar el perfil técnico de la finca (Onboarding progresivo desde Flutter)
     */
    public function completarPerfil(Request $request, $id): JsonResponse
    {
        // 1. Validar los datos que envía Flutter
        $request->validate([
            'ubicacion_gps'     => 'required|string|max:255',
            'hectareas_totales' => 'required|numeric',
            'tipo_suelo'        => 'nullable|string|max:255',
        ]);

        // 2. Buscar la finca y asegurar que le pertenezca a este usuario
        $finca = Finca::where('id', $id)
                      ->where('user_id', $request->user()->id)
                      ->first();

        if (!$finca) {
            return response()->json([
                'success' => false,
                'message' => 'Finca no encontrada o no tienes permisos para editarla.'
            ], 404);
        }

        // 3. Actualizar la finca con los datos técnicos
        $finca->update([
            'ubicacion_gps'     => $request->ubicacion_gps,
            'hectareas_totales' => $request->hectareas_totales,
            'tipo_suelo'        => $request->tipo_suelo,
        ]);

        // 4. Devolver respuesta de éxito
        return response()->json([
            'success' => true,
            'message' => 'Perfil de la finca completado exitosamente',
            'data'    => $finca
        ], 200);
    }
}
