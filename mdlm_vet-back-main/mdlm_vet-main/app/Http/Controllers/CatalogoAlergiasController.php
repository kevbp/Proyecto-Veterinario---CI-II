<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogoAlergias\StoreCatalogoAlergiasRequest;
use App\Http\Requests\CatalogoAlergias\UpdateCatalogoAlergiasRequest;
use App\Models\CatalogoAlergias;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Alergias')]
class CatalogoAlergiasController extends Controller
{
    #[OA\Get(
        path: '/api/alergias',
        summary: 'Listar todas las alergias',
        tags: ['Alergias'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de alergias',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: '#/components/schemas/CatalogoAlergias'
                    )
                )
            ),
        ]
    )]
    public function index()
    {
        $catalogoAlergias = CatalogoAlergias::all();

        return response()->json($catalogoAlergias);
    }

    #[OA\Post(
        path: '/api/alergias',
        summary: 'Crear una nueva alergia',
        tags: ['Alergias'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreCatalogoAlergiasRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Alergia creada exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CatalogoAlergias'
                )
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreCatalogoAlergiasRequest $request)
    {
        $catalogoAlergias = CatalogoAlergias::create($request->validated());

        return response()->json($catalogoAlergias);
    }

    #[OA\Get(
        path: '/api/alergias/{id}',
        summary: 'Obtener una alergia por ID',
        tags: ['Alergias'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
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
                description: 'Alergia obtenida exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CatalogoAlergias'
                )
            ),
            new OA\Response(response: 404, description: 'Alergia no encontrada'),
        ]
    )]
    public function show(CatalogoAlergias $catalogoAlergias)
    {
        return response()->json($catalogoAlergias);
    }

    #[OA\Put(
        path: '/api/alergias/{id}',
        summary: 'Actualizar una alergia por ID',
        tags: ['Alergias'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string'
                )
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateCatalogoAlergiasRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Alergia actualizada exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CatalogoAlergias'
                )
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 404, description: 'Alergia no encontrada'),
        ]
    )]
    public function update(UpdateCatalogoAlergiasRequest $request, CatalogoAlergias $catalogoAlergias)
    {
        $catalogoAlergias->update($request->validated());

        return response()->json($catalogoAlergias);
    }

    #[OA\Delete(
        path: '/api/alergias/{id}',
        summary: 'Eliminar una alergia por ID',
        tags: ['Alergias'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
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
                description: 'Alergia eliminada exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CatalogoAlergias'
                )
            ),
            new OA\Response(response: 404, description: 'Alergia no encontrada'),
        ]
    )]
    public function destroy(CatalogoAlergias $catalogoAlergias)
    {
        $catalogoAlergias->delete();

        return response()->json($catalogoAlergias);
    }
}
