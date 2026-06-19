<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\Contracts\CampaniaServiceInterface;
use App\Http\Resources\Public\CampaniaPublicaResource;
use OpenApi\Attributes as OA;

class VistaPublicaController extends Controller
{
    public function __construct(private CampaniaServiceInterface $campaniaService){}
    
    #[OA\Get(
        path: '/api/public/campanias-activas',
        summary: 'Obtener campañas activas',
        tags: ['Vista Pública'],
        description: 'Retorna una lista de campañas de adopción que están activas.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de campañas activas',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/CampaniaPublicaResource')
                )
            ),
        ]
    )]
    public function index(): AnonymousResourceCollection
    {
        // 1. Pedimos los datos al servicio (sin DTOs porque no hay input)
        $campanias = $this->campaniaService->obtenerCampaniasActivas();

        // 2. Formateamos y protegemos la salida
        return CampaniaPublicaResource::collection($campanias);
    }
}
