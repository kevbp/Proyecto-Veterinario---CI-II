<?php

namespace App\Http\Controllers;

use App\DTOs\Propietario\CreatePropietarioDTO;
use App\DTOs\Propietario\UpdatePropietarioDTO;
use App\Http\Requests\Propietario\StorePropietarioRequest;
use App\Http\Requests\Propietario\UpdatePropietarioRequest;
use App\Services\Contracts\PropietarioServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Propietarios',
    description: 'Endpoints de propietarios',
)]
class PropietarioController extends Controller
{
    public function __construct(
        private readonly PropietarioServiceInterface $propietarioService
    ) {}

    #[OA\Get(
        path: '/api/propietarios',
        summary: 'Listar propietarios',
        tags: ['Propietarios'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de propietarios',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Propietario')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $propietarios = $this->propietarioService->getAll();
        return response()->json($propietarios);
    }

    #[OA\Post(
        path: '/api/propietarios',
        summary: 'Crear propietario',
        tags: ['Propietarios'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StorePropietarioRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Propietario creado',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Propietario'
                )
            ),
        ]
    )]
    public function store(StorePropietarioRequest $request): JsonResponse
    {
        $dto = CreatePropietarioDTO::fromRequest($request->validated());
        $propietario = $this->propietarioService->create($dto);
        return response()->json($propietario, 201);
    }

    #[OA\Get(
        path: '/api/propietarios/{id}',
        summary: 'Obtener propietario por ID',
        tags: ['Propietarios'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Propietario encontrado',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Propietario'
                )
            ),
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $propietario = $this->propietarioService->findById($id);
        return response()->json($propietario);
    }

    #[OA\Put(
        path: '/api/propietarios/{id}',
        summary: 'Actualizar propietario',
        tags: ['Propietarios'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdatePropietarioRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Propietario actualizado',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Propietario'
                )
            ),
        ]
    )]
    public function update(UpdatePropietarioRequest $request, string $id): JsonResponse
    {
        $dto = UpdatePropietarioDTO::fromRequest($request->validated());
        $propietario = $this->propietarioService->update($id, $dto);
        return response()->json($propietario);
    }

    #[OA\Delete(
        path: '/api/propietarios/{id}',
        summary: 'Eliminar propietario',
        tags: ['Propietarios'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Propietario eliminado',
            ),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->propietarioService->delete($id);
        return response()->json(null, 204);
    }

    #[OA\Post(
        path: '/api/propietarios/{id}/reenviar-invitacion',
        summary: 'Reenviar invitación a cliente',
        description: 'Invalida el token anterior, genera uno nuevo y vuelve a enviar el correo al cliente.',
        tags: ['Propietarios'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Invitación reenviada exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Propietario'
                )
            ),
            new OA\Response(response: 404, description: 'Propietario no encontrado'),
        ]
    )]
    public function resendInvitation(string $id): JsonResponse
    {
        $propietario = $this->propietarioService->resendInvitation($id);
        return response()->json($propietario);
    }
}
