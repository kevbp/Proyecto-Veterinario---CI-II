<?php

namespace App\Http\Controllers;

use App\DTOs\Propietario\CreatePropietarioDTO;
use App\DTOs\Propietario\UpdatePropietarioDTO;
use App\Http\Requests\Propietario\StorePropietarioRequest;
use App\Http\Requests\Propietario\UpdatePropietarioRequest;
use App\Http\Resources\PropietarioResource;
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

        return response()->json(PropietarioResource::collection($propietarios)->response()->getData(true));
    }

    #[OA\Post(
        path: '/api/propietarios',
        summary: 'Crear propietario',
        description: 'Registra un nuevo propietario. Retorna los datos del propietario y la URL del SSO-IAM donde debe registrarse para activar su acceso web.',
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
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Propietario'),
                        new OA\Property(
                            property: 'aviso_sso',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'mensaje', type: 'string'),
                                new OA\Property(property: 'url_registro', type: 'string'),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function store(StorePropietarioRequest $request): JsonResponse
    {
        $dto = CreatePropietarioDTO::fromRequest($request->validated());
        $propietario = $this->propietarioService->create($dto);

        return response()->json([
            'data' => new PropietarioResource($propietario),
            'aviso_sso' => [
                'mensaje' => 'Propietario registrado correctamente. '
                    . 'Indíquele al vecino que debe ingresar a la siguiente URL, '
                    . 'registrarse y activar su cuenta con el código que le llegará al correo. '
                    . 'Una vez hecho eso, podrá acceder a los registros de su mascota por la web.',
                'url_registro' => $this->propietarioService->getSsoRegistroUrl(),
            ],
        ], 201);
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

        return response()->json(new PropietarioResource($propietario));
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

        return response()->json(new PropietarioResource($propietario));
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

    #[OA\Get(
        path: '/api/propietarios/{id}/direccion',
        summary: 'Obtener dirección de vivienda del propietario',
        description: 'Retorna la dirección literal y las coordenadas geográficas (latitud/longitud) de la vivienda del propietario.',
        tags: ['Propietarios'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'UUID del propietario',
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dirección encontrada',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'direccion', type: 'string', example: 'Av. La Molina 123, Lima, Perú'),
                        new OA\Property(property: 'latitud', type: 'number', format: 'double', example: -12.0773588),
                        new OA\Property(property: 'longitud', type: 'number', format: 'double', example: -76.9438497),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Propietario no encontrado o sin dirección registrada'),
        ]
    )]
    public function obtenerDireccion(string $id): JsonResponse
    {
        $direccion = $this->propietarioService->obtenerDireccion($id);

        if (! $direccion) {
            return response()->json(['message' => 'El propietario no tiene dirección de vivienda registrada.'], 404);
        }

        return response()->json($direccion);
    }
}
