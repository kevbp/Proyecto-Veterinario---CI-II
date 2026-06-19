<?php

namespace App\Http\Controllers;

use App\Http\Requests\Receta\StoreRecetaRequest;
use App\Http\Requests\Receta\UpdateRecetaRequest;
use App\DTOs\Receta\CreateRecetaDTO;
use App\DTOs\Receta\UpdateRecetaDTO;
use App\Services\Contracts\RecetaServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Http\Resources\RecetaResource;
use Exception;

class RecetaController extends Controller
{
    public function __construct(private readonly RecetaServiceInterface $recetaService){}

    #[OA\Get(
        path: '/api/recetas',
        summary: 'Listar todas las recetas',
        tags: ['Recetas'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de recetas',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Receta')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $recetas = $this->recetaService->getAll();

        return response()->json(RecetaResource::collection($recetas)->response()->getData(true));
    }

    #[OA\Post(
        path: '/api/recetas',
        summary: 'Crear una nueva receta',
        tags: ['Recetas'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreRecetaRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Receta creada exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Receta')
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreRecetaRequest $request): JsonResponse
    {
        try {
            // Intentamos crear la receta
            $receta = $this->recetaService->create(CreateRecetaDTO::fromRequest($request->validated()));

            return response()->json(new RecetaResource($receta), 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'No se pudo procesar la receta.',
                'detalle' => $e->getMessage()
            ], 400);
        }
    }

    #[OA\Get(
        path: '/api/recetas/{id}',
        summary: 'Obtener una receta por ID',
        tags: ['Recetas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Receta encontrada',
                content: new OA\JsonContent(ref: '#/components/schemas/Receta')
            ),
            new OA\Response(response: 404, description: 'Receta no encontrada'),
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $receta = $this->recetaService->getById($id);

        if (!$receta) {
            return response()->json(['message' => 'Receta no encontrada'], 404);
        }

        return response()->json(new RecetaResource($receta));
    }

    #[OA\Put(
        path: '/api/recetas/{id}',
        summary: 'Actualizar una receta por ID',
        tags: ['Recetas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateRecetaRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Receta actualizada exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Receta')
            ),
            new OA\Response(response: 404, description: 'Receta no encontrada'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateRecetaRequest $request, string $id): JsonResponse
    {
        $receta = $this->recetaService->update($id, UpdateRecetaDTO::fromRequest($request->validated()));

        return response()->json($receta);
    }

    #[OA\Delete(
        path: '/api/recetas/{id}',
        summary: 'Eliminar una receta por ID',
        tags: ['Recetas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Receta eliminada exitosamente'),
            new OA\Response(response: 404, description: 'Receta no encontrada'),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->recetaService->delete($id);

        return response()->json(['message' => 'Receta eliminada exitosamente']);
    }
}
