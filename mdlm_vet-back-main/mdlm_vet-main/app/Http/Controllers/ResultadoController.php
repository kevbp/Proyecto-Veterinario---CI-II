<?php

namespace App\Http\Controllers;

use App\Models\Resultado;
use App\DTOs\Resultado\CreateResultadoDTO;
use App\DTOs\Resultado\UpdateResultadoDTO;
use App\Http\Requests\Resultado\StoreResultadoRequest;
use App\Http\Requests\Resultado\UpdateResultadoRequest;
use App\Services\Contracts\ResultadoServiceInterface;
use OpenApi\Attributes as OA;
use Illuminate\Http\JsonResponse;

#[OA\Tag(
    name: 'Resultados',
    description: 'Endpoints de resultados de exámenes',
)]
class ResultadoController extends Controller
{
    public function __construct(private ResultadoServiceInterface $resultadoService){}

    #[OA\Get(
        path: '/api/resultados',
        summary: 'Obtener todos los resultados',
        tags: ['Resultados'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de resultados de exámenes',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Resultado')
                )
            ),
        ],
        description: 'Retorna una lista de todos los resultados de exámenes registrados',
    )]
    public function index(): JsonResponse
    {
        $resultados = $this->resultadoService->getAll();

        return response()->json($resultados);
    }

    #[OA\Post(
        path: '/api/resultados',
        summary: 'Crear un nuevo resultado',
        tags: ['Resultados'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreResultadoRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Resultado creado exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Resultado')
            ),
        ],
        description: 'Crea un nuevo resultado de examen',
    )]
    public function store(StoreResultadoRequest $request): JsonResponse
    {
        $dto = CreateResultadoDTO::fromRequest($request->validated());
        $resultado = $this->resultadoService->create($dto);

        return response()->json($resultado, 201);
    }

    #[OA\Get(
        path: '/api/resultados/{id}',
        summary: 'Obtener un resultado específico',
        tags: ['Resultados'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID del resultado',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Resultado encontrado',
                content: new OA\JsonContent(ref: '#/components/schemas/Resultado')
            ),
            new OA\Response(
                response: 404,
                description: 'Resultado no encontrado'
            )
        ],
        description: 'Retorna un resultado de examen específico por su ID',
    )]
    public function show(string $id): JsonResponse
    {
        $resultado = $this->resultadoService->getById($id);

        return response()->json($resultado);
    }

    #[OA\Put(
        path: '/api/resultados/{id}',
        summary: 'Actualizar un resultado',
        tags: ['Resultados'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID del resultado',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateResultadoRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Resultado actualizado exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Resultado')
            ),
            new OA\Response(
                response: 404,
                description: 'Resultado no encontrado'
            )
        ],
        description: 'Actualiza un resultado de examen específico por su ID',
    )]

    public function update(UpdateResultadoRequest $request, string $id): JsonResponse
    {
        $dto = UpdateResultadoDTO::fromRequest($request->validated());
        $resultado = $this->resultadoService->update($id, $dto);

        return response()->json($resultado);
    }

    #[OA\Delete(
        path: '/api/resultados/{id}',
        summary: 'Eliminar un resultado',
        tags: ['Resultados'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID del resultado',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Resultado eliminado exitosamente'
            ),
            new OA\Response(
                response: 404,
                description: 'Resultado no encontrado'
            )
        ],
        description: 'Elimina un resultado de examen específico por su ID',
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->resultadoService->delete($id);

        return response()->json(null, 204);
    }
}
