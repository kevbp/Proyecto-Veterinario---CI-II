<?php

namespace App\Http\Controllers;

use App\DTOs\TipoExamen\CreateTipoExamenDTO;
use App\DTOs\TipoExamen\UpdateTipoExamenDTO;
use App\Http\Requests\TipoExamen\StoreTipoExamenRequest;
use App\Http\Requests\TipoExamen\UpdateTipoExamenRequest;
use App\Models\TipoExamen;
use App\Services\Contracts\TipoExamenServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class TipoExamenController extends Controller
{
    public function __construct(private TipoExamenServiceInterface $tipoExamenService) {}

    #[OA\Get(
        path: '/api/tipos-examenes',
        summary: 'Listar todos los tipos de examenes',
        tags: ['Tipo Examenes'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de tipos de examenes',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/TipoExamen')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $tipoExamenes = $this->tipoExamenService->getAll();

        return response()->json($tipoExamenes);
    }

    #[OA\Post(
        path: '/api/tipos-examenes',
        summary: 'Crear un nuevo tipo de examen',
        tags: ['Tipo Examenes'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreTipoExamenRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tipo de examen creado',
                content: new OA\JsonContent(ref: '#/components/schemas/TipoExamen')
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreTipoExamenRequest $request): JsonResponse
    {
        $dto = CreateTipoExamenDTO::fromRequest($request->validated());
        $tipoExamen = $this->tipoExamenService->create($dto);

        return response()->json($tipoExamen, 201);
    }

    #[OA\Get(
        path: '/api/tipos-examenes/{id}',
        summary: 'Obtener un tipo de examen por ID',
        tags: ['Tipo Examenes'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tipo de examen encontrado',
                content: new OA\JsonContent(ref: '#/components/schemas/TipoExamen')
            ),
            new OA\Response(response: 404, description: 'Tipo de examen no encontrado'),
        ]
    )]
    public function show(TipoExamen $tipoExamen): JsonResponse
    {
        $tipoExamen = $this->tipoExamenService->getById($tipoExamen->id);

        if (! $tipoExamen) {
            return response()->json(['message' => 'Tipo de examen no encontrado'], 404);
        }

        return response()->json($tipoExamen);
    }

    #[OA\Put(
        path: '/api/tipos-examenes/{id}',
        summary: 'Actualizar un tipo de examen',
        tags: ['Tipo Examenes'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateTipoExamenRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tipo de examen actualizado',
                content: new OA\JsonContent(ref: '#/components/schemas/TipoExamen')
            ),
            new OA\Response(response: 404, description: 'Tipo de examen no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateTipoExamenRequest $request, string $id): JsonResponse
    {
        $dto = UpdateTipoExamenDTO::fromRequest($request->validated());
        $tipoExamen = $this->tipoExamenService->update($id, $dto);

        return response()->json($tipoExamen);
    }

    #[OA\Delete(
        path: '/api/tipos-examenes/{id}',
        summary: 'Eliminar un tipo de examen',
        tags: ['Tipo Examenes'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Tipo de examen eliminado'),
            new OA\Response(response: 404, description: 'Tipo de examen no encontrado'),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->tipoExamenService->delete($id);

        return response()->json(['message' => 'Tipo de examen eliminado exitosamente']);
    }
}
