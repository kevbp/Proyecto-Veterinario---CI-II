<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogoCondiciones\StoreCatalogoCondicionesRequest;
use App\Http\Requests\CatalogoCondiciones\UpdateCatalogoCondicionesRequest;
use Illuminate\Support\Facades\Cache;
use App\Models\CatalogoCondiciones;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Condiciones')]
class CatalogoCondicionesController extends Controller
{
    #[OA\Get(
        path: '/api/condiciones',
        summary: 'Listar todas las condiciones',
        tags: ['Condiciones'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de condiciones',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: '#/components/schemas/CatalogoCondiciones'
                    )
                )
            ),
        ]
    )]
    public function index()
    {
        $catalogoCondiciones = Cache::remember('maestra_catalogo_condiciones', 86400, function () {
            return CatalogoCondiciones::select('id','nombre')->get()->toArray();
        });
    
        return response()->json($catalogoCondiciones);
    }

    #[OA\Post(
        path: '/api/condiciones',
        summary: 'Crear una nueva condición',
        tags: ['Condiciones'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreCatalogoCondicionesRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Condición creada exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CatalogoCondiciones'
                )
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreCatalogoCondicionesRequest $request)
    {
        $catalogoCondiciones = CatalogoCondiciones::create($request->validated());

        return response()->json($catalogoCondiciones);
    }

    #[OA\Get(
        path: '/api/condiciones/{id}',
        summary: 'Obtener una condición por ID',
        tags: ['Condiciones'],
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
                description: 'Condición obtenida exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CatalogoCondiciones'
                )
            ),
            new OA\Response(response: 404, description: 'Condición no encontrada'),
        ]
    )]
    public function show(CatalogoCondiciones $catalogoCondiciones)
    {
        return response()->json($catalogoCondiciones);
    }

    #[OA\Put(
        path: '/api/condiciones/{id}',
        summary: 'Actualizar una condición por ID',
        tags: ['Condiciones'],
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
                ref: '#/components/schemas/UpdateCatalogoCondicionesRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Condición actualizada exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CatalogoCondiciones'
                )
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
            new OA\Response(response: 404, description: 'Condición no encontrada'),
        ]
    )]
    public function update(UpdateCatalogoCondicionesRequest $request, CatalogoCondiciones $catalogoCondiciones)
    {
        $catalogoCondiciones->update($request->validated());

        return response()->json($catalogoCondiciones);
    }

    #[OA\Delete(
        path: '/api/condiciones/{id}',
        summary: 'Eliminar una condición por ID',
        tags: ['Condiciones'],
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
                description: 'Condición eliminada exitosamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CatalogoCondiciones'
                )
            ),
            new OA\Response(response: 404, description: 'Condición no encontrada'),
        ]
    )]
    public function destroy(CatalogoCondiciones $catalogoCondiciones)
    {
        $catalogoCondiciones->delete();

        return response()->json($catalogoCondiciones);
    }
}
