<?php

namespace App\Http\Controllers;

use App\DTOs\Animal_Alergia\CreateAnimalAlergiaDTO;
use App\DTOs\Animal_Alergia\UpdateAnimalAlergiaDTO;
use App\Http\Requests\Animal_alergia\StoreAnimal_AlergiaRequest;
use App\Http\Requests\Animal_alergia\UpdateAnimal_AlergiaRequest;
use App\Http\Resources\AnimalAlergiaResource;
use App\Models\Animal_Alergia;
use App\Services\Contracts\AnimalAlergiaServiceInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Animal Alergia',
    description: 'Endpoints para la gestión de alergias de animales'
)]
class AnimalAlergiaController extends Controller
{
    public function __construct(private AnimalAlergiaServiceInterface $animalAlergiaService) {}

    #[OA\Get(
        path: '/api/animales/{id}/alergias',
        security: [['bearerAuth' => []]],
        summary: 'Get all animal alergias',
        tags: ['Animal Alergia'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: '#/components/schemas/Animal_Alergia'
                    )
                )
            ),
        ]
    )]
    public function index()
    {
        $animalAlergias = $this->animalAlergiaService->getAll();

        return response()->json(AnimalAlergiaResource::collection($animalAlergias)->response()->getData(true));
    }

    #[OA\Post(
        path: '/api/animales/{id}/alergias',
        security: [['bearerAuth' => []]],
        summary: 'Lista todas las alergias de un animal',
        tags: ['Animal Alergia'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
                description: 'ID del animal'
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreAnimal_AlergiaRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Successful response',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Alergia'
                )
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreAnimal_AlergiaRequest $request, string $id)
    {
        $dto = CreateAnimalAlergiaDTO::fromRequest($request->validated(), $id);
        $animalAlergia = $this->animalAlergiaService->create($dto);

        return response()->json(new AnimalAlergiaResource($animalAlergia->load('alergia')), 201);
    }

    #[OA\Get(
        path: '/api/animales/{id}/alergias/{animal_Alergia}',
        security: [['bearerAuth' => []]],
        summary: 'Get an animal alergia by id',
        tags: ['Animal Alergia'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
                description: 'ID del animal'
            ),
            new OA\Parameter(
                name: 'animal_Alergia',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
                description: 'ID de la alergia del animal'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Alergia'
                )
            ),
            new OA\Response(response: 404, description: 'Animal alergia no encontrado'),
        ]
    )]
    public function show(Animal_Alergia $animal_Alergia)
    {
        return response()->json(new AnimalAlergiaResource($animal_Alergia->load('alergia')));
    }

    #[OA\Put(
        path: '/api/animales/{id}/alergias/{animal_Alergia}',
        security: [['bearerAuth' => []]],
        summary: 'Update an animal alergia by id',
        tags: ['Animal Alergia'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
                description: 'ID del animal'
            ),
            new OA\Parameter(
                name: 'animal_Alergia',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
                description: 'ID de la alergia del animal'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Alergia'
                )
            ),
            new OA\Response(response: 404, description: 'Animal alergia no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateAnimal_AlergiaRequest $request, Animal_Alergia $animal_Alergia)
    {
        $dto = UpdateAnimalAlergiaDTO::fromRequest($request->validated());
        $animalAlergia = $this->animalAlergiaService->update($animal_Alergia->id, $dto);

        return response()->json(new AnimalAlergiaResource($animalAlergia));
    }

    #[OA\Delete(
        path: '/api/animales/{id}/alergias/{animal_Alergia}',
        security: [['bearerAuth' => []]],
        summary: 'Delete an animal alergia by id',
        tags: ['Animal Alergia'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
                description: 'ID del animal'
            ),
            new OA\Parameter(
                name: 'animal_Alergia',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
                description: 'ID de la alergia del animal'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Animal_Alergia'
                )
            ),
            new OA\Response(response: 404, description: 'Animal alergia no encontrado'),
        ]
    )]
    public function destroy(Animal_Alergia $animal_Alergia)
    {
        $this->animalAlergiaService->delete($animal_Alergia->id);

        return response()->json(null, 204);
    }
}
