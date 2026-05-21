<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\DTOs\AuthTokenDTO;
use App\Http\Requests\Auth\RegisterClienteRequest;
use App\Models\Propietario;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Autenticación', description: 'Endpoints de autenticación y registro')]
class ClienteRegistrationController extends Controller
{
    #[OA\Get(
        path: '/api/auth/invitacion/{token}',
        summary: 'Verificar token de invitación de cliente',
        tags: ['Autenticación'],
        parameters: [
            new OA\Parameter(
                name: 'token',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Token válido',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'valido', type: 'boolean', example: true),
                    new OA\Property(property: 'email', type: 'string', example: 'cliente@ejemplo.com'),
                    new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
                ])
            ),
            new OA\Response(response: 400, description: 'Token inválido o expirado'),
            new OA\Response(response: 404, description: 'Token no encontrado'),
        ]
    )]
    public function verify(string $token): JsonResponse
    {
        $propietario = Propietario::where('invitation_token', $token)->firstOrFail();

        if ($propietario->isInvitationExpired()) {
            return response()->json(['message' => 'El enlace de invitación ha expirado.'], 400);
        }

        if ($propietario->invitation_accepted_at !== null) {
            return response()->json(['message' => 'Esta invitación ya fue utilizada.'], 400);
        }

        return response()->json([
            'valido' => true,
            'email' => $propietario->email,
            'nombre' => $propietario->nombre,
        ]);
    }

    #[OA\Post(
        path: '/api/auth/registrar-cliente',
        summary: 'Registrar credenciales del cliente',
        tags: ['Autenticación'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterClienteRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Cliente registrado exitosamente',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'access_token', type: 'string'),
                    new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                    new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
                    new OA\Property(property: 'user', ref: '#/components/schemas/UserDTO'),
                ])
            ),
            new OA\Response(response: 400, description: 'Token inválido o expirado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function register(RegisterClienteRequest $request): JsonResponse
    {
        $propietario = Propietario::where('invitation_token', $request->token)->firstOrFail();

        if ($propietario->isInvitationExpired() || $propietario->invitation_accepted_at !== null) {
            return response()->json(['message' => 'El enlace de invitación es inválido o ha expirado.'], 400);
        }

        // Crear usuario
        $user = User::create([
            'name' => $propietario->nombre . ' ' . $propietario->paterno,
            'email' => $propietario->email,
            'password' => $request->password,
        ]);

        $user->assignRole(Role::findByName('propietario', 'api'));

        // Actualizar propietario
        $propietario->update([
            'user_id' => $user->id,
            'invitation_accepted_at' => now(),
            'invitation_token' => null, // Invalidar token
        ]);

        // Autenticar y retornar token
        $token = auth('api')->login($user);
        $dto = AuthTokenDTO::fromToken($token, $user);

        return response()->json($dto->toArray(), 201);
    }
}
