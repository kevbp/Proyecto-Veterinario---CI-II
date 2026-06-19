<?php

namespace App\Http\Controllers;

use App\Http\Requests\Examen\StoreExamenRequest;
use App\Http\Requests\Examen\UpdateExamenRequest;
use App\DTOs\Examen\CreateExamenDTO;
use App\DTOs\Examen\UpdateExamenDTO;
use App\Services\Contracts\ExamenServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Exámenes',
    description: 'Endpoints de exámenes',
)]
class ExamenController extends Controller
{
    public function __construct(private ExamenServiceInterface $examenService) {}    

    #[OA\Get(
        path: '/api/examenes',
        summary: 'Obtener todos los exámenes',
        tags: ['Exámenes'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de exámenes',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Examen')
                )
            ),
        ],
        description: 'Retorna una lista de todos los exámenes registrados',
    )]
    public function index(): JsonResponse
    {
        $examenes = $this->examenService->getAll();

        return response()->json($examenes);
    }

    #[OA\Post(
        path: '/api/examenes',
        summary: 'Crear un nuevo examen',
        tags: ['Exámenes'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreExamenRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Examen creado exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Examen'
                )
            ),
        ],
        description: 'Crea un nuevo examen con los datos proporcionados',
    )]
    public function store(StoreExamenRequest $request): JsonResponse
    {
        $dto = CreateExamenDTO::fromRequest($request->validated());
        $examen = $this->examenService->create($dto);

        return response()->json($examen, 201);
    }

    #[OA\Get(
        path: '/api/examenes/{id}',
        summary: 'Obtener un examen específico',
        tags: ['Exámenes'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID del examen',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Examen encontrado',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Examen'
                )
            ),
        ],
        description: 'Retorna un examen específico por su ID',
    )]

    public function show(string $id): JsonResponse
    {
        $examen = $this->examenService->getById($id);

        return response()->json($examen);
    }

    #[OA\Put(
        path: '/api/examenes/{id}',
        summary: 'Actualizar un examen existente',
        tags: ['Exámenes'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID del examen',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateExamenRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Examen actualizado exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Examen'
                )
            ),
        ],
        description: 'Actualiza un examen existente con los datos proporcionados',
    )]

    public function update(UpdateExamenRequest $request, string $id): JsonResponse
    {
        $dto = UpdateExamenDTO::fromRequest($request->validated());
        $updatedExamen = $this->examenService->update($id, $dto);

        return response()->json($updatedExamen);
    }

    #[OA\Delete(
        path: '/api/examenes/{id}',
        summary: 'Eliminar un examen existente',
        tags: ['Exámenes'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID del examen',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Examen eliminado exitosamente'
            ),
        ],
        description: 'Elimina un examen existente por su ID',
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->examenService->delete($id);

        return response()->json(null, 204);
    }
}
