<?php

namespace App\Http\Controllers;

use App\DTOs\Especie\CreateEspecieDTO;
use App\DTOs\Especie\UpdateEspecieDTO;
use App\Http\Requests\Especie\StoreEspecieRequest;
use App\Http\Requests\Especie\UpdateEspecieRequest;
use App\Services\Contracts\EspecieServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class EspecieController extends Controller
{
    public function __construct(private readonly EspecieServiceInterface $especieService) {}

    #[OA\Get(
        path: '/api/especies',
        summary: 'Listar todas las especies',
        tags: ['Especies'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de especies',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Especie')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $especies = $this->especieService->getAll();

        return response()->json($especies);
    }

    #[OA\Post(
        path: '/api/especies',
        summary: 'Crear una nueva especie',
        tags: ['Especies'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreEspecieRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Especie creada',
                content: new OA\JsonContent(ref: '#/components/schemas/Especie')
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreEspecieRequest $request): JsonResponse
    {
        $dto = CreateEspecieDTO::fromRequest($request->validated());
        $especie = $this->especieService->create($dto);

        return response()->json($especie, 201);
    }

    #[OA\Get(
        path: '/api/especies/{id}',
        summary: 'Obtener una especie por ID',
        tags: ['Especies'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Especie encontrada',
                content: new OA\JsonContent(ref: '#/components/schemas/Especie')
            ),
            new OA\Response(response: 404, description: 'Especie no encontrada'),
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $especie = $this->especieService->getById($id);

        if (! $especie) {
            return response()->json(['message' => 'Especie no encontrada'], 404);
        }

        return response()->json($especie);
    }

    #[OA\Put(
        path: '/api/especies/{id}',
        summary: 'Actualizar una especie',
        tags: ['Especies'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateEspecieRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Especie actualizada',
                content: new OA\JsonContent(ref: '#/components/schemas/Especie')
            ),
            new OA\Response(response: 404, description: 'Especie no encontrada'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateEspecieRequest $request, string $id): JsonResponse
    {
        $dto = UpdateEspecieDTO::fromRequest($request->validated());
        $especie = $this->especieService->update($id, $dto);

        return response()->json($especie);
    }

    #[OA\Delete(
        path: '/api/especies/{id}',
        summary: 'Eliminar una especie',
        tags: ['Especies'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Especie eliminada'),
            new OA\Response(response: 404, description: 'Especie no encontrada'),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->especieService->delete($id);

        return response()->json(['message' => 'Especie eliminada exitosamente']);
    }
}
