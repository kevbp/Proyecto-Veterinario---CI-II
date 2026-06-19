<?php

namespace App\Http\Controllers;

use App\DTOs\EsquemaVacuna\CreateEsquemaVacunaDTO;
use App\DTOs\EsquemaVacuna\UpdateEsquemaVacunaDTO;
use App\Http\Requests\EsquemaVacuna\StoreEsquemaVacunaRequest;
use App\Http\Requests\EsquemaVacuna\UpdateEsquemaVacunaRequest;
use App\Models\EsquemaVacuna;
use App\Services\Contracts\EsquemaVacunaServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class EsquemaVacunaController extends Controller
{
    public function __construct(private readonly EsquemaVacunaServiceInterface $esquemaVacunaService)
    {
    }

    #[OA\Get(
        path: '/api/esquema-vacunas',
        summary: 'Listar todos los esquemas de vacunas',
        tags: ['EsquemaVacunas'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de esquemas de vacunas',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/EsquemaVacuna')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $esquemaVacunas = $this->esquemaVacunaService->getAll();

        return response()->json($esquemaVacunas);
    }

    #[OA\Post(
        path: '/api/esquema-vacunas',
        summary: 'Crear un nuevo esquema de vacuna',
        tags: ['EsquemaVacunas'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreEsquemaVacunaRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Esquema de vacuna creado exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/EsquemaVacuna'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Errores de validación'
            ),
        ]
    )]
    public function store(StoreEsquemaVacunaRequest $request): JsonResponse
    {
        $dto = CreateEsquemaVacunaDTO::fromRequest($request->validated());
        $esquemaVacuna = $this->esquemaVacunaService->create($dto);

        return response()->json($esquemaVacuna, Response::HTTP_CREATED);
    }

    #[OA\Get(
        path: '/api/esquema-vacunas/{id}',
        summary: 'Obtener un esquema de vacuna por ID',
        tags: ['EsquemaVacunas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
                example: '123e4567-e89b-12d3-a456-426614174000'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Esquema de vacuna encontrado',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/EsquemaVacuna'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Esquema de vacuna no encontrado'
            ),
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $esquemaVacuna = $this->esquemaVacunaService->getById($id);

        if (!$esquemaVacuna) {
            return response()->json(['message' => 'Esquema de vacuna no encontrado.'], 404);
        }

        return response()->json($esquemaVacuna);
    }

    #[OA\Put(
        path: '/api/esquema-vacunas/{id}',
        summary: 'Actualizar un esquema de vacuna',
        tags: ['EsquemaVacunas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
                example: '123e4567-e89b-12d3-a456-426614174000'
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateEsquemaVacunaRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Esquema de vacuna actualizado exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/EsquemaVacuna'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Esquema de vacuna no encontrado'
            ),
            new OA\Response(
                response: 422,
                description: 'Errores de validación'
            ),
        ]
    )]
    public function update(UpdateEsquemaVacunaRequest $request, string $id): JsonResponse
    {
        $dto = UpdateEsquemaVacunaDTO::fromRequest($request->validated());
        $esquemaVacuna = $this->esquemaVacunaService->update($id, $dto);

        return response()->json($esquemaVacuna);
    }

    #[OA\Delete(
        path: '/api/esquema-vacunas/{id}',
        summary: 'Eliminar un esquema de vacuna',
        tags: ['EsquemaVacunas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
                example: '123e4567-e89b-12d3-a456-426614174000'
            ),
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Esquema de vacuna eliminado exitosamente'
            ),
            new OA\Response(
                response: 404,
                description: 'Esquema de vacuna no encontrado'
            ),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->esquemaVacunaService->delete($id);

        return response()->noContent();
    }
}