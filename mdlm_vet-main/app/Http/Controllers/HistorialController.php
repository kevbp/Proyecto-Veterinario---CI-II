<?php

namespace App\Http\Controllers;

use App\Services\Contracts\HistorialServiceInterface;
use App\Http\Resources\HistorialClinicoResource;
use OpenApi\Attributes as OA;

class HistorialController extends Controller
{
    public function __construct(private HistorialServiceInterface $historialService) {}

    #[OA\Get(
        path: '/api/animales/{animal_id}/historial',
        summary: 'Obtener la línea de tiempo del historial clínico de un animal',
        tags: ['Historial Clínico'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'animal_id', in: 'path', required: true, description: 'UUID del animal', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Historial obtenido exitosamente')
        ]
    )]
    public function getTimeline(string $animal_id)
    {
        $timeline = $this->historialService->getTimelineByAnimalId($animal_id);

        return HistorialClinicoResource::collection($timeline);
    }
}