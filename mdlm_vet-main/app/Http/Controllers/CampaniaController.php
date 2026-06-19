<?php

namespace App\Http\Controllers;

use App\DTOs\Campania\CreateCampaniaDTO;
use App\DTOs\Campania\UpdateCampaniaDTO;
use App\DTOs\Campania\FinalizarCampaniaDTO;
use App\Http\Requests\Campania\StoreCampaniaRequest;
use App\Http\Requests\Campania\UpdateCampaniaRequest;
use App\Http\Requests\Campania\FinalizarCampaniaRequest;
use App\Services\Contracts\CampaniaServiceInterface;
use Illuminate\Http\JsonResponse;

use App\Http\Resources\CampaniaResource;
use OpenApi\Attributes as OA;

class CampaniaController extends Controller
{
    public function __construct(private readonly CampaniaServiceInterface $campaniaService){}

    #[OA\Get(
        path: '/api/campanias',
        summary: 'Listar todas las campañas',
        tags: ['Campañas'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de campañas',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Campania')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $campanias = $this->campaniaService->getAll();

        return response()->json(CampaniaResource::collection($campanias)->response()->getData(true));
    }

    #[OA\Post(
        path: '/api/campanias',
        summary: 'Crear una nueva campaña',
        tags: ['Campañas'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreCampaniaRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Campaña creada exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Campania')
            ),
            new OA\Response(response: 422, description: 'Error de validación')
        ]
    )]
    public function store(StoreCampaniaRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $dto = CreateCampaniaDTO::fromRequest($validated);
        $campania = $this->campaniaService->create($dto);

        return response()->json(new CampaniaResource($campania), 201);
    }

    #[OA\Get(
        path: '/api/campanias/{id}',
        summary: 'Obtener una campaña por su ID',
        tags: ['Campañas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'UUID de la campaña', schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Campaña encontrada',
                content: new OA\JsonContent(ref: '#/components/schemas/Campania')
            ),
            new OA\Response(response: 404, description: 'Campaña no encontrada')
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $campania = $this->campaniaService->getById($id);

        return response()->json(new CampaniaResource($campania));
    }

    #[OA\Put(
        path: '/api/campanias/{id}',
        summary: 'Actualizar una campaña',
        tags: ['Campañas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'UUID de la campaña', schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateCampaniaRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Campaña actualizada exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Campania')
            ),
            new OA\Response(response: 404, description: 'Campaña no encontrada'),
            new OA\Response(response: 422, description: 'Error de validación')
        ]
    )]
    public function update(UpdateCampaniaRequest $request, string $id): JsonResponse
    {
        try {
            $dto = UpdateCampaniaDTO::fromRequest($request->validated());
            $campania = $this->campaniaService->update($id, $dto);

            return response()->json(new CampaniaResource($campania));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    #[OA\Delete(
        path: '/api/campanias/{id}',
        summary: 'Eliminar una campaña',
        tags: ['Campañas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'UUID de la campaña', schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Campaña eliminada'),
            new OA\Response(response: 404, description: 'Campaña no encontrada')
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->campaniaService->delete($id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    #[OA\Patch(
        path: '/api/campanias/{id}/iniciar',
        summary: 'Iniciar una campaña',
        description: 'Cambia el estado de una campaña de "Planificada" a "En Progreso".',
        tags: ['Campañas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'UUID de la campaña', schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Campaña iniciada exitosamente', content: new OA\JsonContent(ref: '#/components/schemas/Campania')),
            new OA\Response(response: 400, description: 'Error de validación (ej. la campaña ya estaba iniciada)')
        ]
    )]
    public function iniciar(string $id): JsonResponse
    {
        try {
            $campania = $this->campaniaService->iniciarCampania($id);
            return response()->json(new CampaniaResource($campania));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    #[OA\Post(
        path: '/api/campanias/{id}/finalizar',
        summary: 'Finalizar campaña y descontar inventario',
        description: 'Cierra la campaña y descuenta masivamente los insumos físicos consumidos.',
        tags: ['Campañas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'UUID de la campaña', schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/FinalizarCampaniaRequest')
        ),
        responses: [
            new OA\Response(response: 200, description: 'Campaña finalizada e inventario actualizado', content: new OA\JsonContent(ref: '#/components/schemas/Campania')),
            new OA\Response(response: 400, description: 'Error de stock o estado inválido')
        ]
    )]
    public function finalizar(FinalizarCampaniaRequest $request, string $id): JsonResponse
    {
        try {
            $dto = FinalizarCampaniaDTO::fromRequest($request->validated());
            $campania = $this->campaniaService->finalizarCampania($id, $dto);

            return response()->json(new CampaniaResource($campania));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    #[OA\Patch(
        path: '/api/campanias/{id}/cancelar',
        summary: 'Cancelar una campaña',
        description: 'Cambia el estado a "Cancelada". No se puede cancelar una campaña finalizada.',
        tags: ['Campañas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'UUID de la campaña', schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Campaña cancelada', content: new OA\JsonContent(ref: '#/components/schemas/Campania')),
            new OA\Response(response: 400, description: 'Error de validación de estado')
        ]
    )]
    public function cancelar(string $id): JsonResponse
    {
        try {
            $campania = $this->campaniaService->cancelarCampania($id);
            return response()->json(new CampaniaResource($campania));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    #[OA\Get(
        path: '/api/campanias/{id}/estadisticas',
        summary: 'Obtener estadísticas en tiempo real',
        description: 'Devuelve un conteo de vacunas y desparasitaciones agrupadas por especie animal.',
        tags: ['Campañas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'UUID de la campaña', schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: 'Estadísticas generadas', 
                // Aquí definimos una respuesta libre (sin schema estricto) porque es un reporte dinámico
                content: new OA\JsonContent(
                    example: [
                        'campania_id' => '123e...',
                        'resumen_general' => ['total_vacunas_aplicadas' => 450, 'total_desparasitaciones_aplicadas' => 300, 'total_intervenciones' => 750],
                        'desglose_vacunas' => ['Perro' => 300, 'Gato' => 150]
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Campaña no encontrada')
        ]
    )]
    public function estadisticas(string $id): JsonResponse
    {
        try {
            $estadisticas = $this->campaniaService->obtenerEstadisticas($id);
            return response()->json($estadisticas);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Campaña no encontrada.'], 404);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error en estadisticas de campaña: ' . $e->getMessage(), [
                'campania_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }
}
