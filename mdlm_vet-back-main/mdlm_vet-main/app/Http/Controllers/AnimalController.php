<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Animal\StoreAnimalRequest;
use App\Http\Requests\Animal\UpdateAnimalRequest;
use App\DTOs\Animal\CreateAnimalDTO;
use App\DTOs\Animal\UpdateAnimalDTO;
use App\Services\Contracts\AnimalServiceInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Animales',
    description: 'Endpoints de animales'
)]
class AnimalController extends Controller
{
    public function __construct(
        private readonly AnimalServiceInterface $animalService,
    ) {}

    #[OA\Get(
        path: '/api/animales',
        summary: 'Listar todos los animales',
        tags: ['Animales'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de animales',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Animal')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $animals = $this->animalService->getAll();

        return response()->json($animals);
    }

    #[OA\Post(
        path: '/api/animales',
        summary: 'Crear un nuevo animal',
        tags: ['Animales'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreAnimalRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Animal creado',
                content: new OA\JsonContent(ref: '#/components/schemas/Animal')
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreAnimalRequest $request): JsonResponse
    {
        $dto = CreateAnimalDTO::fromRequest($request->validated());
        $animal = $this->animalService->create($dto);

        return response()->json($animal, 201);
    }

    #[OA\Get(
        path: '/api/animales/{id}',
        summary: 'Obtener un animal por ID',
        tags: ['Animales'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Animal obtenido',
                content: new OA\JsonContent(ref: '#/components/schemas/Animal')
            ),
            new OA\Response(response: 404, description: 'Animal no encontrado'),
        ]
    )]
    public function show(Animal $animal): JsonResponse
    {
        $animal = $this->animalService->getById($animal->id);

        return response()->json($animal);
    }

    #[OA\Put(
        path: '/api/animales/{id}',
        summary: 'Actualizar un animal',
        tags: ['Animales'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateAnimalRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Animal actualizado',
                content: new OA\JsonContent(ref: '#/components/schemas/Animal')
            ),
            new OA\Response(response: 404, description: 'Animal no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateAnimalRequest $request, Animal $animal): JsonResponse
    {
        $dto = UpdateAnimalDTO::fromRequest($request->validated());
        $animal = $this->animalService->update($animal->id, $dto);

        return response()->json($animal);
    }

    #[OA\Delete(
        path: '/api/animales/{id}',
        summary: 'Eliminar un animal',
        tags: ['Animales'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Animal eliminado'),
            new OA\Response(response: 404, description: 'Animal no encontrado'),
        ]
    )]
    public function destroy(Animal $animal): JsonResponse
    {
        $this->animalService->delete($animal->id);

        return response()->json(null, 204);
    }

    #[OA\Get(
        path: '/api/animales/{id}/propietario',
        summary: 'Obtener propietario de un animal',
        tags: ['Animales'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Propietario encontrado',
                content: new OA\JsonContent(ref: '#/components/schemas/Propietario')
            ),
            new OA\Response(response: 404, description: 'Animal no encontrado'),
        ]
    )]
    public function getPropietario(string $id): JsonResponse
    {
        $propietario = $this->animalService->getPropietarioByAnimalId($id);

        return response()->json($propietario);
    }
}
