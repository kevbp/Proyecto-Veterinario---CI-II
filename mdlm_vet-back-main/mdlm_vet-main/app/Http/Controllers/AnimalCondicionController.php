<?php

namespace App\Http\Controllers;

use App\DTOs\Animal_Condicion\CreateAnimalCondicionDTO;
use App\DTOs\Animal_Condicion\UpdateAnimalCondicionDTO;
use App\Http\Requests\Animal_condicion\StoreAnimal_CondicionRequest;
use App\Http\Requests\Animal_condicion\UpdateAnimal_CondicionRequest;
use App\Models\Animal_Condicion;
use App\Services\Contracts\AnimalCondicionServiceInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'AnimalCondicion',
    description: 'Endpoints para la gestión de condiciones de animales'
)]
class AnimalCondicionController extends Controller
{
    public function __construct(private readonly AnimalCondicionServiceInterface $animalCondicionService) {}

    #[OA\Get(
        path: '/api/animal-condicion',
        security: [['bearerAuth' => []]],
        summary: 'Get all animal conditions',
        tags: ['AnimalCondicion'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Animal conditions retrieved successfully',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: '#/components/schemas/Animal_Condicion'
                    )
                )
            ),
        ]
    )]
    public function index()
    {
        $animal_Condicion = $this->animalCondicionService->getAll();

        return response()->json($animal_Condicion);
    }

    #[OA\Post(
        path: '/api/animal-condicion',
        security: [['bearerAuth' => []]],
        summary: 'Create an animal condition',
        tags: ['AnimalCondicion'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreAnimal_CondicionRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Animal condition created successfully',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Condicion'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Condicion'
                )
            ),
        ]
    )]
    public function store(StoreAnimal_CondicionRequest $request)
    {
        $dto = CreateAnimalCondicionDTO::fromRequest($request->validated());
        $animal_Condicion = $this->animalCondicionService->create($dto);

        return response()->json($animal_Condicion);
    }

    #[OA\Get(
        path: '/api/animal-condicion/{id}',
        security: [['bearerAuth' => []]],
        summary: 'Get an animal condition by id',
        tags: ['AnimalCondicion'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Animal condition found successfully',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Condicion'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Animal condition not found',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Condicion'
                )
            ),
        ]
    )]
    public function show(Animal_Condicion $animal_Condicion)
    {
        $animal_Condicion = $this->animalCondicionService->getById($animal_Condicion->id);

        return response()->json($animal_Condicion);
    }

    #[OA\Put(
        path: '/api/animal-condicion/{id}',
        security: [['bearerAuth' => []]],
        summary: 'Update an animal condition by id',
        tags: ['AnimalCondicion'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Animal condition updated successfully',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Condicion'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Animal condition not found',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Condicion'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Condicion'
                )
            ),
        ]
    )]
    public function update(UpdateAnimal_CondicionRequest $request, Animal_Condicion $animal_Condicion)
    {
        $dto = UpdateAnimalCondicionDTO::fromRequest($request->validated());
        $animal_Condicion = $this->animalCondicionService->update($animal_Condicion->id, $dto);

        return response()->json($animal_Condicion);
    }

    #[OA\Delete(
        path: '/api/animal-condicion/{id}',
        security: [['bearerAuth' => []]],
        summary: 'Delete an animal condition by id',
        tags: ['AnimalCondicion'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Animal condition deleted successfully',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Condicion'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Animal condition not found',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Condicion'
                )
            ),
        ]
    )]
    public function destroy(Animal_Condicion $animal_Condicion)
    {
        $this->animalCondicionService->delete($animal_Condicion->id);

        return response()->json($animal_Condicion);
    }
}
