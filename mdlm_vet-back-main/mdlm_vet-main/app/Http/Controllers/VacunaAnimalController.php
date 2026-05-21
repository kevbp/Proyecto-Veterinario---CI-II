<?php

namespace App\Http\Controllers;

use App\DTOs\VacunaAnimal\CreateVacunaDTO;
use App\DTOs\VacunaAnimal\UpdateVacunaDTO;
use App\Http\Requests\VacunaAnimal\StoreVacunaAnimalRequest;
use App\Http\Requests\VacunaAnimal\UpdateVacunaAnimalRequest;
use App\Services\Contracts\VacunaServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class VacunaAnimalController extends Controller
{
    public function __construct(
        private VacunaServiceInterface $vacunaService
        ) {}

    #[OA\Get(
        path: '/api/vacunas-animales',
        summary: 'Listar todas las vacunas de animales',
        tags: ['Vacunas Animales'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de vacunas de animales',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/VacunaAnimal')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $vacunas = $this->vacunaService->getAll();

        return response()->json($vacunas);
    }

    #[OA\Post(
        path: '/api/vacunas-animales',
        summary: 'Crear una nueva vacuna de animal',
        tags: ['Vacunas Animales'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreVacunaAnimalRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Vacuna de animal creada correctamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/VacunaAnimal'
                )
            ),
        ]
    )]
    public function store(StoreVacunaAnimalRequest $request): JsonResponse
    {
        $user = auth('api')->user();

        if (! $user || ! $user->personal) {
            abort(403, 'El usuario autenticado no tiene un perfil de personal asignado.');
        }

        $validated = $request->validated();
        $personal_id = $user->personal->id;
        $animal_id = $validated['animal_id'] ?? null;

        if(!empty($validated['consulta_id'])) {
            $consulta = Consulta::findOrFail($validated['consulta_id']);
            $animal_id = $consulta->animal_id;
            $personal_id = $consulta->personal_id;
        }

        $validated['animal_id'] = $animal_id;

        $dto = CreateVacunaDTO::fromRequest(
            $validated,
            $personal_id,
            $validated['consulta_id'] ?? null,
            $validated['campania_id'] ?? null
        );
        $vacuna = $this->vacunaService->create($dto);

        return response()->json($vacuna, 201);
    }

    #[OA\Get(
        path: '/api/vacunas-animales/{id}',
        summary: 'Obtener una vacuna de animal por ID',
        tags: ['Vacunas Animales'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vacuna de animal obtenida correctamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/VacunaAnimal'
                )
            ),
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $vacuna = $this->vacunaService->getById($id);

        return response()->json($vacuna);
    }

    #[OA\Put(
        path: '/api/vacunas-animales/{id}',
        summary: 'Actualizar una vacuna de animal por ID',
        tags: ['Vacunas Animales'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateVacunaAnimalRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vacuna de animal actualizada correctamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/VacunaAnimal'
                )
            ),
        ]
    )]
    public function update(UpdateVacunaAnimalRequest $request, string $id): JsonResponse
    {
        $dto = UpdateVacunaDTO::fromRequest($request->validated());
        $vacuna = $this->vacunaService->update($dto, $id);

        return response()->json($vacuna);
    }

    #[OA\Delete(
        path: '/api/vacunas-animales/{id}',
        summary: 'Eliminar una vacuna de animal por ID',
        tags: ['Vacunas Animales'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vacuna de animal eliminada correctamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/VacunaAnimal'
                )
            ),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->vacunaService->delete($id);

        return response()->json(null, 204);
    }
}
