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
    #[OA\Post(
        path: '/api/auth/login',
        summary: 'Iniciar sesión',
        tags: ['Autenticación'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@veterinaria.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login exitoso',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'access_token', type: 'string'),
                    new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                    new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
                    new OA\Property(property: 'user', type: 'object', properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'Juan Pérez'),
                        new OA\Property(property: 'email', type: 'string', example: 'juan@example.com'),
                        new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string')),
                    ]),
                ])
            ),
            new OA\Response(
                response: 401,
                description: 'Credenciales inválidas',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'Credenciales inválidas'),
                ])
            ),
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $token = auth('api')->attempt($credentials);

        if (! $token) {
            return response()->json([
                'message' => 'Credenciales inválidas',
            ], 401);
        }

        /** @var User $user */
        $user = auth('api')->user();

        // DISPARAMOS EL LOG MANUALMENTE
        activity('Seguridad')
            ->causedBy($user)
            ->withProperties([
                'ip' => Request::ip(),
                'user_agent' => Request::userAgent()
            ])
            ->log('Inicio de sesión exitoso');

        $dto = AuthTokenDTO::fromToken($token, $user);

        return response()->json($dto->toArray());
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
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Juan Pérez'),
                    new OA\Property(property: 'email', type: 'string', example: 'juan@example.com'),
                    new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string')),
                ])
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
        /** @var User $user */
        $user = auth('api')->user();

        // DISPARAMOS EL LOG MANUALMENTE
        activity('Seguridad')
            ->causedBy($user)
            ->withProperties([
                'ip' => Request::ip(),
                'user_agent' => Request::userAgent()
            ])
            ->log('Cierre de sesión');
        auth('api')->logout();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente',
        ]);
    }

    #[OA\Post(
        path: '/api/auth/refresh',
        summary: 'Refrescar token JWT',
        tags: ['Autenticación'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Token refrescado exitosamente',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'access_token', type: 'string'),
                    new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                    new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
                    new OA\Property(property: 'user', type: 'object', properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'Juan Pérez'),
                        new OA\Property(property: 'email', type: 'string', example: 'juan@example.com'),
                        new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string')),
                    ]),
                ])
            ),
            new OA\Response(response: 401, description: 'No autenticado'),
        ]
    )]
    public function refresh(): JsonResponse
    {
        $token = auth('api')->refresh();

        /** @var User $user */
        $user = auth('api')->user();

        $dto = AuthTokenDTO::fromToken($token, $user);

        return response()->json($dto->toArray());
    }
}
