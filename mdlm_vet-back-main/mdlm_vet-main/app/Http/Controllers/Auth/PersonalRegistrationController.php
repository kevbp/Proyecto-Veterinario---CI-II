<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use App\Models\User;
use App\Models\Role;
use App\DTOs\AuthTokenDTO;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Validation\ValidationException;

#[OA\Tag(name: 'Autenticación', description: 'Endpoints de autenticación y registro')]
class PersonalRegistrationController extends Controller
{
    #[OA\Post(
        path: '/api/auth/registrar-personal',
        summary: 'Registrar credenciales del personal invitado',
        description: 'Permite al personal invitado crear su cuenta mediante su token de invitación.',
        tags: ['Autenticación'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['token', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'token', type: 'string', example: 'abc123xyz890'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'MiClaveSecreta123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'MiClaveSecreta123'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Personal registrado exitosamente', content: new OA\JsonContent(ref: '#/components/schemas/AuthTokenDTO')),
            new OA\Response(response: 400, description: 'Token inválido o expirado'),
        ]
    )]
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $personal = Personal::where('invitation_token', $request->token)->first();

        if (!$personal) {
            throw ValidationException::withMessages(['token' => ['El token de invitación es inválido.']]);
        }

        if ($personal->isInvitationExpired()) {
            throw ValidationException::withMessages(['token' => ['El token de invitación ha expirado.']]);
        }

        if ($personal->hasUser()) {
            throw ValidationException::withMessages(['token' => ['Esta cuenta ya ha sido registrada previamente.']]);
        }

        $user = User::create([
            'name' => $personal->nombre . ' ' . $personal->paterno,
            'email' => $personal->email,
            'password' => $request->password,
        ]);

        $roleToAssign = Role::findByName($personal->rol_sistema, 'api');
        $user->assignRole($roleToAssign);

        $personal->user_id = $user->id;
        $personal->invitation_token = null; // Invalidate token
        $personal->invitation_accepted_at = now();
        $personal->save();

        $token = auth('api')->login($user);
        $dto = AuthTokenDTO::fromToken($token, $user);

        return response()->json($dto->toArray(), 201);
    }
}
