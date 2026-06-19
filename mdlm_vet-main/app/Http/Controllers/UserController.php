<?php

namespace App\Http\Controllers;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Usuarios',
    description: 'Gestión de usuarios internos (admin/gestor)',
)]
class UserController extends Controller
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {}

    #[OA\Get(
        path: '/api/usuarios',
        summary: 'Listar usuarios',
        tags: ['Usuarios'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de usuarios',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/UserDTO')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $users = $this->userService->getAll();
        return response()->json($users);
    }

    #[OA\Post(
        path: '/api/usuarios',
        summary: 'Crear usuario interno',
        description: 'Admin puede crear cualquier rol. Gestor solo puede crear recepcionista y veterinario.',
        tags: ['Usuarios'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreUserRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Usuario creado',
                content: new OA\JsonContent(ref: '#/components/schemas/UserDTO')
            ),
            new OA\Response(response: 403, description: 'No autorizado para asignar este rol'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreUserRequest $request): JsonResponse
    {
        $dto = CreateUserDTO::fromRequest($request->validated());
        /** @var \App\Models\User $creator */
        $creator = auth('api')->user();
        $user = $this->userService->create($dto, $creator);
        return response()->json($user, 201);
    }

    #[OA\Get(
        path: '/api/usuarios/{id}',
        summary: 'Obtener usuario por ID',
        tags: ['Usuarios'],
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
                description: 'Usuario encontrado',
                content: new OA\JsonContent(ref: '#/components/schemas/UserDTO')
            ),
            new OA\Response(response: 404, description: 'Usuario no encontrado'),
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $user = $this->userService->findById($id);
        return response()->json($user);
    }

    #[OA\Put(
        path: '/api/usuarios/{id}',
        summary: 'Actualizar usuario',
        tags: ['Usuarios'],
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
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateUserRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Usuario actualizado',
                content: new OA\JsonContent(ref: '#/components/schemas/UserDTO')
            ),
            new OA\Response(response: 403, description: 'No autorizado'),
            new OA\Response(response: 404, description: 'Usuario no encontrado'),
        ]
    )]
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $dto = UpdateUserDTO::fromRequest($request->validated());
        /** @var \App\Models\User $editor */
        $editor = auth('api')->user();
        $user = $this->userService->update($id, $dto, $editor);
        return response()->json($user);
    }

    #[OA\Delete(
        path: '/api/usuarios/{id}',
        summary: 'Eliminar usuario',
        tags: ['Usuarios'],
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
            new OA\Response(response: 204, description: 'Usuario eliminado'),
            new OA\Response(response: 403, description: 'No autorizado'),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        /** @var \App\Models\User $deleter */
        $deleter = auth('api')->user();
        $this->userService->delete($id, $deleter);
        return response()->json(null, 204);
    }

    #[OA\Get(
        path: '/api/usuarios/roles-asignables',
        summary: 'Obtener roles que el usuario autenticado puede asignar',
        tags: ['Usuarios'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de roles asignables',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    example: ['recepcionista', 'veterinario']
                )
            ),
        ]
    )]
    public function rolesAsignables(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();
        $roles = $this->userService->getAssignableRoles($user);
        return response()->json($roles);
    }
}
