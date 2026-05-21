<?php

namespace App\Http\Controllers;

use App\DTOs\Raza\CreateRazaDTO;
use App\DTOs\Raza\UpdateRazaDTO;
use App\Http\Requests\Raza\StoreRazaRequest;
use App\Http\Requests\Raza\UpdateRazaRequest;
use App\Models\Raza;
use App\Services\Contracts\RazaServiceInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Raza',
    description: 'Endpoints para la gestión de razas'
)]
class RazaController extends Controller
{
    public function __construct(private readonly RazaServiceInterface $razaService) {}

    #[OA\Get(
        path: '/api/raza',
        security: [['bearerAuth' => []]],
        summary: 'Get all razas',
        tags: ['Raza'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Razas retrieved successfully',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: '#/components/schemas/Raza'
                    )
                )
            ),
        ]
    )]
    public function index()
    {
        return response()->json($this->razaService->getAll());
    }

    #[OA\Get(
        path: '/api/raza/especie/{codigo}',
        security: [['bearerAuth' => []]],
        summary: 'Get all razas by especie codigo',
        tags: ['Raza'],
        parameters: [
            new OA\Parameter(
                name: 'codigo',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string'
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Razas retrieved successfully',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: '#/components/schemas/Raza'
                    )
                )
            ),
        ]
    )]
    public function getEspecieByCodigo(string $codigo)
    {
        $especie = $this->razaService->getByEspecieId($codigo);

        return response()->json($especie);
    }

    #[OA\Post(
        path: '/api/raza',
        security: [['bearerAuth' => []]],
        summary: 'Create a raza',
        tags: ['Raza'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreRazaRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Raza created successfully',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Raza'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Raza'
                )
            ),
        ]
    )]
    public function store(StoreRazaRequest $request)
    {
        $dto = CreateRazaDTO::fromRequest($request->validated());
        $raza = $this->razaService->create($dto);

        return response()->json($raza, 201);
    }

    #[OA\Get(
        path: '/api/raza/{id}',
        security: [['bearerAuth' => []]],
        summary: 'Get a raza by id',
        tags: ['Raza'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Raza found successfully',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Raza'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Raza not found',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Raza'
                )
            ),
        ]
    )]
    public function show(Raza $raza)
    {
        $raza = $this->razaService->getById($raza->id);

        return response()->json($raza);
    }

    #[OA\Put(
        path: '/api/raza/{id}',
        security: [['bearerAuth' => []]],
        summary: 'Update a raza by id',
        tags: ['Raza'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateRazaRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Raza updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Raza')
            ),
            new OA\Response(
                response: 404,
                description: 'Raza not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Raza')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/Raza')
            ),
        ]
    )]
    public function update(UpdateRazaRequest $request, Raza $raza)
    {
        $dto = UpdateRazaDTO::fromRequest($request->validated());
        $raza = $this->razaService->update($raza->id, $dto);

        return response()->json($raza);
    }

    #[OA\Delete(
        path: '/api/raza/{id}',
        security: [['bearerAuth' => []]],
        summary: 'Delete a raza by id',
        tags: ['Raza'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Raza deleted successfully',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Raza'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Raza not found',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Raza'
                )
            ),
        ]
    )]
    public function destroy(Raza $raza)
    {
        $this->razaService->delete($raza->id);

        return response()->json(null, 204);
    }
}
