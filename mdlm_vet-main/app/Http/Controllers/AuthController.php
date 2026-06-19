<?php

namespace App\Http\Controllers;

use App\DTOs\AuthTokenDTO;
use App\DTOs\UserDTO;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Get(
        path: '/api/auth/perfil-prueba',
        summary: 'Prueba de integración SSO',
        description: 'Endpoint de prueba para verificar que el token del SSO funciona y el usuario local se está obteniendo correctamente.',
        security: [['bearerAuth' => []]],
        tags: ['Autenticación'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Token válido y usuario local obtenido'
            ),
            new OA\Response(
                response: 401,
                description: 'No autorizado (Token inválido o expirado)'
            )
        ]
    )]
    public function perfilPrueba(): JsonResponse
    {
        $user = auth('api')->user();

        return response()->json([
            'mensaje' => '¡Has entrado a la Veterinaria usando el SSO!',
            'sso_id' => $user->id,
            // 'roles_locales' => $user->getRoleNames(),
            'usuario_local' => $user
        ]);
    }

    #[OA\Get(
        path: '/api/auth/me',
        summary: 'Obtener usuario autenticado',
        tags: ['Autenticación'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Datos del usuario autenticado',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/UserDTO')
                )
            ),
            new OA\Response(response: 401, description: 'No autenticado'),
        ]
    )]
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();

        $dto = UserDTO::fromModel($user);

        return response()->json($dto->toArray());
    }

    #[OA\Get(
        path: '/api/auth/debug-permisos',
        summary: 'Debug de permisos del usuario (solo desarrollo)',
        tags: ['Autenticación'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Información de depuración de permisos'
            ),
        ]
    )]
    public function debugPermisos(): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();
        
        $user->loadMissing('roles.permissions', 'permissions');
        
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'roles' => $user->roles->map(fn($role) => [
                'name' => $role->name,
                'permissions_count' => $role->permissions->count(),
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ])->toArray(),
            'direct_permissions' => $user->permissions->pluck('name')->toArray(),
            'total_permissions_count' => collect()
                ->merge($user->permissions->pluck('name'))
                ->merge($user->roles->flatMap(fn($r) => $r->permissions->pluck('name')))
                ->unique()
                ->count()
        ]);
    }

    #[OA\Post(
        path: '/api/auth/logout',
        summary: 'Cerrar sesión',
        tags: ['Autenticación'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Sesión cerrada exitosamente',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'Sesión cerrada exitosamente'),
                ])
            ),
            new OA\Response(response: 401, description: 'No autenticado'),
        ]
    )]
    public function logout(): JsonResponse
    {
        return response()->json([
            'message' => 'Sesión cerrada exitosamente',
        ]);
    }


}
