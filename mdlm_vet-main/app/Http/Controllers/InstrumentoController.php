<?php

namespace App\Http\Controllers;

use App\Http\Requests\Instrumento\StoreInstrumentoRequest;
use App\Http\Requests\Instrumento\UpdateInstrumentoRequest;
use App\Services\Contracts\InstrumentoServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class InstrumentoController extends Controller
{
    public function __construct(private readonly InstrumentoServiceInterface $instrumentoService)
    {
    }

    #[OA\Get(
        path: '/api/instrumentos',
        summary: 'Listar todos los instrumentos',
        tags: ['Instrumentos'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de instrumentos',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/InstrumentoDTO')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $instrumentos = $this->instrumentoService->getAll();

        return response()->json($instrumentos);
    }

    #[OA\Post(
        path: '/api/instrumentos',
        summary: 'Crear un nuevo instrumento',
        tags: ['Instrumentos'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreInstrumentoRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Instrumento creado',
                content: new OA\JsonContent(ref: '#/components/schemas/InstrumentoDTO')
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreInstrumentoRequest $request): JsonResponse
    {
        $instrumento = $this->instrumentoService->create($request->validated());

        return response()->json($instrumento, 201);
    }

    #[OA\Get(
        path: '/api/instrumentos/{id}',
        summary: 'Obtener un instrumento por ID',
        tags: ['Instrumentos'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Instrumento obtenido',
                content: new OA\JsonContent(ref: '#/components/schemas/InstrumentoDTO')
            ),
            new OA\Response(response: 404, description: 'Instrumento no encontrado'),
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $instrumento = $this->instrumentoService->getById($id);

        if (!$instrumento) {
            return response()->json(['message' => 'Instrumento no encontrado'], 404);
        }

        return response()->json($instrumento);
    }

    #[OA\Put(
        path: '/api/instrumentos/{id}',
        summary: 'Actualizar un instrumento',
        tags: ['Instrumentos'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateInstrumentoRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Instrumento actualizado',
                content: new OA\JsonContent(ref: '#/components/schemas/InstrumentoDTO')
            ),
            new OA\Response(response: 404, description: 'Instrumento no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateInstrumentoRequest $request, string $id): JsonResponse
    {
        $instrumento = $this->instrumentoService->update($id, $request->validated());

        return response()->json($instrumento);
    }

    #[OA\Delete(
        path: '/api/instrumentos/{id}',
        summary: 'Eliminar un instrumento',
        tags: ['Instrumentos'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Instrumento eliminado'),
            new OA\Response(response: 404, description: 'Instrumento no encontrado'),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->instrumentoService->delete($id);

        return response()->json(['message' => 'Instrumento eliminado exitosamente']);
    }
}
