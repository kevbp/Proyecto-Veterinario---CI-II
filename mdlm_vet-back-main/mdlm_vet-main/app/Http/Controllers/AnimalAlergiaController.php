<?php

namespace App\Http\Controllers;

use App\DTOs\Animal_Alergia\CreateAnimalAlergiaDTO;
use App\DTOs\Animal_Alergia\UpdateAnimalAlergiaDTO;
use App\Http\Requests\Animal_alergia\StoreAnimal_AlergiaRequest;
use App\Http\Requests\Animal_alergia\UpdateAnimal_AlergiaRequest;
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
        path: '/api/animal-alergia',
        security: [['bearerAuth' => []]],
        summary: 'Get all animal alergias',
        tags: ['Animal Alergia'],
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

        return response()->json($animalAlergias);
    }

    #[OA\Post(
        path: '/api/animal-alergia',
        security: [['bearerAuth' => []]],
        summary: 'Create a new animal alergia',
        tags: ['Animal Alergia'],
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
    public function store(StoreAnimal_AlergiaRequest $request)
    {
        $dto = CreateAnimalAlergiaDTO::fromRequest($request->validated());
        $animalAlergia = $this->animalAlergiaService->create($dto);

        return response()->json($animalAlergia);
    }

    #[OA\Get(
        path: '/api/animal-alergia/{id}',
        security: [['bearerAuth' => []]],
        summary: 'Get an animal alergia by id',
        tags: ['Animal Alergia'],
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
        return response()->json($animal_Alergia);
    }

    #[OA\Put(
        path: '/api/animal-alergia/{id}',
        security: [['bearerAuth' => []]],
        summary: 'Update an animal alergia by id',
        tags: ['Animal Alergia'],
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

        return response()->json($animalAlergia);
    }

    #[OA\Delete(
        path: '/api/animal-alergia/{id}',
        security: [['bearerAuth' => []]],
        summary: 'Delete an animal alergia by id',
        tags: ['Animal Alergia'],
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

        return response()->json($animal_Alergia);
    }
}
