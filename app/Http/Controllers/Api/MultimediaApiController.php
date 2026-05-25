<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MultimediaApiController extends Controller
{
    /**
     * Sube uno o múltiples archivos y los enlaza polimórficamente a un Lote, Finca o Actividad
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validar que venga el arreglo 'archivos', y validar las reglas de CADA archivo por dentro
        $request->validate([
            'archivos'    => 'required|array',
            'archivos.*'  => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi,mp3,m4a,wav,pdf|max:51200', // Máx 50MB
            'modelo_tipo' => 'required|string|in:actividad,lote,finca', 
            'modelo_id'   => 'required|integer' 
        ]);

        // // 2. Mapear a qué modelo de Laravel pertenece
        // $clasesPermitidas = [
        //     'actividad' => \App\Models\Actividad::class,
        //     'lote'      => \App\Models\Lote::class,
        //     'finca'     => \App\Models\Finca::class,
        // ];

        // $claseDestino = $clasesPermitidas[$request->modelo_tipo];
        // $entidad = $claseDestino::find($request->modelo_id);

        // if (!$entidad) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'El registro destino no existe.'
        //     ], 404);
        // }

        // $multimediaGuardada = []; // Lista para devolver las respuestas

        // // 3. Recorremos CADA archivo (ya sea 1 solo, o sean 10)
        // foreach ($request->file('archivos') as $archivo) {
            
        //     // Guardar en el disco duro
        //     $rutaArchivo = $archivo->store('data_lake', 'public');
        //     $tipoArchivo = $archivo->getClientOriginalExtension();

        //     // Conectar a la base de datos
        //     $multimedia = $entidad->archivos()->create([
        //         'ruta_archivo' => $rutaArchivo, 
        //         'tipo_archivo' => $tipoArchivo  
        //     ]);

        //     $multimediaGuardada[] = $multimedia; // Agregar a la lista de éxito
        // }

        // // 4. Devolver la confirmación final
        // return response()->json([
        //     'success' => true,
        //     'message' => count($multimediaGuardada) . ' archivo(s) multimedia guardado(s) exitosamente.',
        //     'data'    => $multimediaGuardada
        // ], 201);

        if (!$request->hasFile('archivos') && !$request->filled('texto')) {
            return response()->json([
                'success' => false,
                'message' => 'Debes enviar al menos un archivo o una nota de texto.'
            ], 400);
        }

        // 1. RASTREO JERÁRQUICO: Averiguar a qué Finca pertenece este archivo
        $finca = null;

        if ($request->modelo_tipo === 'finca') {
            $finca = \App\Models\Finca::find($request->modelo_id);
        } elseif ($request->modelo_tipo === 'lote') {
            $lote = \App\Models\Lote::find($request->modelo_id);
            $finca = $lote ? $lote->finca : null;
        } elseif ($request->modelo_tipo === 'actividad') {
            $actividad = \App\Models\Actividad::find($request->modelo_id);
            $finca = ($actividad && $actividad->lote) ? $actividad->lote->finca : null;
        }

        // 2. CANDADO MAESTRO: Si la finca raíz no existe, no es del usuario o no está aprobada, se bloquea la subida
        if (!$finca || $finca->user_id !== $request->user()->id || $finca->estado !== 'aprobado') {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado. No puedes subir archivos a una entidad asociada a una finca bloqueada o inexistente.'
            ], 403);
        }

        // 3. Lógica original polimórfica para guardar el archivo si pasó la seguridad
        $modeloClase = match ($request->modelo_tipo) {
            'finca'     => \App\Models\Finca::class,
            'lote'      => \App\Models\Lote::class,
            'actividad' => \App\Models\Actividad::class,
        };

        $entidad = $modeloClase::findOrFail($request->modelo_id);
        $archivosGuardados = [];

        // foreach ($request->file('archivos') as $archivo) {
        //     $path = $archivo->store('multimedia', 'public');

        //     $archivosGuardados[] = \App\Models\ArchivoMultimedia::create([
        //         'modelo_tipo' => $modeloClase,
        //         'modelo_id'   => $entidad->id,
        //         'ruta_archivo'=> $path,
        //         'tipo_archivo'=> $archivo->getClientMimeType(),
        //         'peso_bytes'  => $archivo->getSize(),
        //     ]);
        // }

        // 5. Guardar Archivos (Usando fileable_type y fileable_id)
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $path = $archivo->store('multimedia', 'public');

                $archivosGuardados[] = \App\Models\ArchivoMultimedia::create([
                    'fileable_type' => $modeloClase,  // 🚀 Estándar de Laravel
                    'fileable_id'   => $entidad->id,  // 🚀 Estándar de Laravel
                    'ruta_archivo'  => $path,
                    'tipo_archivo'  => $archivo->getClientMimeType(),
                    'peso_bytes'    => $archivo->getSize(),
                ]);
            }
        }

        // 6. Guardar Texto (Usando fileable_type y fileable_id)
        if ($request->filled('texto')) {
            $archivosGuardados[] = \App\Models\ArchivoMultimedia::create([
                'fileable_type'   => $modeloClase,    // 🚀 Estándar de Laravel
                'fileable_id'     => $entidad->id,    // 🚀 Estándar de Laravel
                'contenido_texto' => $request->texto,
                'tipo_archivo'    => 'nota_texto',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => count($archivosGuardados) . ' archivo(s) subido(s) correctamente.',
            'data'    => $archivosGuardados
        ], 201);
    }
}