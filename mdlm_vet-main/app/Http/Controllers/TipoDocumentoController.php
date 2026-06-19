<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class TipoDocumentoController extends Controller
{
    #[OA\Get(
        path: '/api/tipo-documentos',
        summary: 'Listar todos los tipos de documentos',
        tags: ['Maestras'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de tipos de documentos',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/TipoDocumento')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $tipoDocumentos = Cache::remember('maestra_tipo_documento', 86400, function () {
            return TipoDocumento::select('id', 'codigo', 'nombre')->get()->toArray();
        });

        return response()->json($tipoDocumentos);
    }
}
