<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimalResource;
use App\Models\Animal;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Cliente', description: 'Vista del cliente autenticado')]
class ClienteController extends Controller
{
    #[OA\Get(
        path: '/api/cliente/perfil',
        summary: 'Obtener datos del cliente autenticado',
        tags: ['Cliente'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Perfil del cliente',
                content: new OA\JsonContent(ref: '#/components/schemas/Propietario')
            ),
            new OA\Response(response: 404, description: 'Perfil no configurado')
        ]
    )]
    public function perfil(): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();

        if (!$user->propietario) {
            return response()->json(['message' => 'El usuario no tiene un perfil de propietario asociado'], 404);
        }

        return response()->json($user->propietario->load('tipoDocumento'));
    }

    #[OA\Get(
        path: '/api/cliente/mascotas',
        summary: 'Obtener mascotas del cliente autenticado',
        tags: ['Cliente'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de mascotas',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Animal')
                )
            ),
        ]
    )]
    public function mascotas(): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();

        if (!$user->propietario) {
            return response()->json([]);
        }

        $mascotas = Animal::where('propietario_id', $user->propietario->id)
            ->with(['especie', 'raza', 'propietario'])
            ->get();

        return response()->json(AnimalResource::collection($mascotas));
    }

    #[OA\Get(
        path: '/api/cliente/mascotas/{id}',
        summary: 'Obtener detalle de mascota',
        tags: ['Cliente'],
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
                description: 'Detalles de la mascota',
                content: new OA\JsonContent(ref: '#/components/schemas/Animal')
            ),
            new OA\Response(response: 404, description: 'Mascota no encontrada o no pertenece al cliente'),
        ]
    )]
    public function mascota(string $id): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();

        if (!$user->propietario) {
            return response()->json(['message' => 'Mascota no encontrada'], 404);
        }

        $mascota = Animal::where('propietario_id', $user->propietario->id)
            ->where('id', $id)
            ->with(['especie', 'raza', 'propietario', 'alergias.alergia', 'condiciones.condicion'])
            ->firstOrFail();

        return response()->json(new AnimalResource($mascota));
    }
}
