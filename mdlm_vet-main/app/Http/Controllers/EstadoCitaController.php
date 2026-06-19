<?php

namespace App\Http\Controllers;

use App\Models\EstadoCita;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

#[OA\Controller()]
class EstadoCitaController extends Controller
{
    #[OA\Get(
        path: '/api/estado-citas',
        summary: 'Listar todos los estados de cita',
        tags: ['Maestras'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de estados de cita obtenida exitosamente',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/EstadoCita')
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        // Datos estaticos, no cambian frecuentemente, por lo que se cachean para mejorar rendimiento.
        $estados = Cache::rememberForever('maestra_estado_citas', function () {
            return EstadoCita::select('id', 'codigo', 'nombre', 'color_hex')->get()->toArray();
        });

        return response()->json($estados);
    }
}
