<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Finca;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LoteApiController extends Controller
{
    /**
     * Devuelve todos los lotes pertenecientes a una finca específica
     */
    public function index(Request $request, $finca_id): JsonResponse
    {
        // 1. Validar que la finca exista y pertenezca al usuario del Token y su estado sea aprovado
        $finca = Finca::where('id', $finca_id)
                      ->where('user_id', $request->user()->id)
                      ->first();

        // if (!$finca) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Finca no encontrada o acceso denegado.'
        //     ], 404);
        // }


        if (!$finca || $finca->estado !== 'aprobado') {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado. La finca no existe o aún no ha sido aprobada.'
            ], 403);
        }

        // 2. Obtener los lotes
        $lotes = $finca->lotes()->get();

        return response()->json([
            'success' => true,
            'data'    => $lotes
        ], 200);
    }

    /**
     * Permite al agricultor crear un nuevo lote desde la app móvil (Flutter)
     */
    public function store(Request $request, $finca_id): JsonResponse
    {
        // 1. Validar los datos que el celular debe enviar
        $request->validate([
            'nombre'        => 'required|string|max:255',
            'hectareas'     => 'required|numeric|min:0.01',
            'tipo_cultivo'  => 'required|string|max:255',
            'variedad'      => 'nullable|string|max:255',
            'fecha_siembra' => 'nullable|date',
            'latitud'       => 'nullable|numeric|between:-90,90',
            'longitud'      => 'nullable|numeric|between:-180,180',
        ]);

        // 2. Seguridad de hierro: Verificar que la finca existe y es propiedad del usuario
        $finca = Finca::where('id', $finca_id)
                      ->where('user_id', $request->user()->id)
                      ->first();

        // if (!$finca) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Finca no encontrada o no tienes permisos.'
        //     ], 404);
        // }

        if (!$finca || $finca->estado !== 'aprobado') {
            return response()->json([
                'success' => false,
                'message' => 'No puedes crear lotes. La finca no existe o aún no ha sido aprobada.'
            ], 403);
        }

        // 3. Crear el lote en la base de datos
        $lote = $finca->lotes()->create([
            'nombre'        => $request->nombre,
            'hectareas'     => $request->hectareas,
            'tipo_cultivo'  => $request->tipo_cultivo,
            'variedad'      => $request->variedad,
            'fecha_siembra' => $request->fecha_siembra,
            'latitud'       => $request->latitud,
            'longitud'      => $request->longitud,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lote creado exitosamente desde el campo',
            'data'    => $lote
        ], 201);
    }
}