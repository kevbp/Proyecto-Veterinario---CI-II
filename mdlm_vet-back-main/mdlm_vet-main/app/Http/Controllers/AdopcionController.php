<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Adopcion\EstadisticaCampaniaRequest;
use App\Http\Requests\Adopcion\EstadisticaFechasRequest;
use App\Http\Requests\Adopcion\StoreAdopcionRequest;
use App\DTOs\Adopcion\EstadisticaCampaniaDTO;
use App\DTOs\Adopcion\EstadisticaFechasDTO;
use App\DTOs\Animal\RegistrarAdopcionDTO;
use App\Services\Contracts\AdopcionServiceInterface;
use OpenApi\Attributes as OA;

class AdopcionController extends Controller
{
    public function __construct(
        private readonly AdopcionServiceInterface $adopcionService
    ) {}

    #[OA\Post(
        path: '/api/animales/{animal_id}/adopciones',
        summary: 'Registrar adopción de un animal',
        tags: ['Adopciones'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'animal_id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreAdopcionRequest')),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Adopción registrada',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'La adopción ha sido registrada exitosamente.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Adopcion')
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Animal no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function setAdopcion(string $animal_id, StoreAdopcionRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $propietario_nuevo_id = $validated['propietario_nuevo_id'];
            $dto = RegistrarAdopcionDTO::fromRequest($validated, $animal_id, $propietario_nuevo_id);
            $adopcion = $this->adopcionService->registrarAdopcion($dto);

            return response()->json([
                'message' => 'Adopción registrada exitosamente.',
                'data' => $adopcion
            ], 201);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    #[OA\Get(
        path: '/api/adopciones/estadisticas-adopcion-por-campania',
        summary: 'Obtener estadísticas de adopción por campaña',
        tags: ['Adopciones'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'campania_id', in: 'query', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Estadísticas de adopción obtenidas',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'total_adopciones', type: 'integer'),
                        new OA\Property(property: 'desglose_especies', type: 'array', items: new OA\Items(ref: '#/components/schemas/Especie'))
                    ]
                )
            )
        ]
    )]

    public function estadisticasAdopcionPorCampaña(EstadisticaCampaniaRequest $request): JsonResponse
    {
        $dto = EstadisticaCampaniaDTO::fromRequest($request->validated());
        $estadisticas = $this->adopcionService->obtenerEstadisticasCampania($dto);

        return response()->json($estadisticas);
    }

    #[OA\Get(
        path: '/api/adopciones/estadisticas-adopcion-por-fechas',
        summary: 'Obtener estadísticas de adopción por fechas',
        tags: ['Adopciones'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'fecha_inicio', in: 'query', required: true, schema: new OA\Schema(type: 'string', format: 'date')),
            new OA\Parameter(name: 'fecha_fin', in: 'query', required: true, schema: new OA\Schema(type: 'string', format: 'date'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Estadísticas de adopción obtenidas',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'fecha_inicio', type: 'string', format: 'date'),
                        new OA\Property(property: 'fecha_fin', type: 'string', format: 'date'),
                        new OA\Property(property: 'total_adopciones', type: 'integer'),
                        new OA\Property(property: 'desglose_especies', type: 'array', items: new OA\Items(ref: '#/components/schemas/Especie'))
                    ]
                )
            )
        ]
    )]
    public function estadisticasAdopcionFechas(EstadisticaFechasRequest $request): JsonResponse
    {
        $dto = EstadisticaFechasDTO::fromRequest($request->validated());
        $estadisticas = $this->adopcionService->obtenerEstadisticasFechas($dto);

        return response()->json(array_merge(
            ['fecha_inicio' => $dto->fecha_inicio, 'fecha_fin' => $dto->fecha_fin],
            $estadisticas
        ));
    }
}
