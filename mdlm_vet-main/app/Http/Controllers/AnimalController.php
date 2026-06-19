<?php

namespace App\Http\Controllers;

use App\DTOs\Animal\CreateAnimalDTO;
use App\DTOs\Animal\UpdateAnimalDTO;
use App\Http\Requests\Animal\StoreAnimalRequest;
use App\Http\Requests\Animal\UpdateAnimalRequest;
use App\Http\Resources\AnimalResource;
use App\Models\Animal;
use App\Services\Contracts\AnimalServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        parameters: [
            new OA\Parameter(
                name: 'albergue',
                in: 'query',
                description: 'Filtrar solo animales del albergue municipal',
                required: false,
                schema: new OA\Schema(type: 'boolean')
            ),
        ],
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
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['albergue']);
        
        // Si el usuario es propietario, solo puede ver sus propias mascotas
        $user = auth()->user();
        if ($user->isCliente() && $user->propietario) {
            $filters['propietario_id'] = $user->propietario->id;
        }
        
        $animals = $this->animalService->getAll($filters);

        return response()->json(AnimalResource::collection($animals)->response()->getData(true));
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

        return response()->json(new AnimalResource($animal), 201);
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
            new OA\Response(response: 403, description: 'No autorizado'),
        ]
    )]
    public function show(Animal $animal): JsonResponse
    {
        $this->authorizeAccess($animal);
        $animal = $this->animalService->getById($animal->id);

        return response()->json(new AnimalResource($animal));
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
            new OA\Response(response: 403, description: 'No autorizado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateAnimalRequest $request, Animal $animal): JsonResponse
    {
        $this->authorizeAccess($animal);
        $dto = UpdateAnimalDTO::fromRequest($request->validated());
        $animal = $this->animalService->update($animal->id, $dto);

        return response()->json(new AnimalResource($animal));
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
            new OA\Response(response: 403, description: 'No autorizado'),
        ]
    )]
    public function destroy(Animal $animal): JsonResponse
    {
        $this->authorizeAccess($animal);
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
            new OA\Response(response: 403, description: 'No autorizado'),
        ]
    )]
    public function getPropietario(string $id): JsonResponse
    {
        $animal = Animal::findOrFail($id);
        $this->authorizeAccess($animal);
        
        $propietario = $this->animalService->getPropietarioByAnimalId($id);

        return response()->json($propietario);
    }

    #[OA\Patch(
        path: '/api/animales/{id}/fallecimiento',
        summary: 'Registrar fallecimiento de un animal',
        description: 'Marca un animal como fallecido y registra la fecha de fallecimiento.',
        tags: ['Animales'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'UUID del animal', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Fallecimiento registrado exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Animal')
            ),
            new OA\Response(response: 404, description: 'Animal no encontrado'),
            new OA\Response(response: 403, description: 'No autorizado'),
            new OA\Response(response: 409, description: 'El animal ya se encuentra registrado como fallecido'),
        ]
    )]
    public function registrarFallecimiento(string $id): JsonResponse
    {
        try {
            $animal = Animal::findOrFail($id);
            $this->authorizeAccess($animal);
            
            $animal = $this->animalService->registrarFallecimiento($id);

            return response()->json(new AnimalResource($animal));
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }

    /**
     * Autoriza el acceso al animal basándose en el rol del usuario.
     */
    private function authorizeAccess(Animal $animal): void
    {
        $user = auth()->user();
        
        // Si es administrador, gestor o personal interno, tiene acceso total
        if ($user->isAdmin() || $user->isGestor() || $user->isVeterinario() || $user->isRecepcionista()) {
            return;
        }
        
        // Si es propietario, solo puede acceder a sus mascotas
        if ($user->isCliente() && $user->propietario && $animal->propietario_id === $user->propietario->id) {
            return;
        }
        
        abort(403, 'No tiene permiso para realizar esta acción sobre esta mascota.');
    }
}
