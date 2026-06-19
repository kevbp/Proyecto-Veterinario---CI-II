<?php

namespace App\Http\Controllers;

use App\DTOs\LineaMedicamento\CreateLineaMedicamentoDTO;
use App\DTOs\LineaMedicamento\UpdateLineaMedicamentoDTO;
use App\Http\Requests\LineaMedicamento\StoreLineaMedicamentoRequest;
use App\Http\Requests\LineaMedicamento\UpdateLineaMedicamentoRequest;
use App\Models\LineaMedicamento;
use App\Services\Contracts\LineaMedicamentoServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class LineaMedicamentoController extends Controller
{
    public function __construct(private readonly LineaMedicamentoServiceInterface $lineaMedicamentoService){}

    #[OA\Get(
        path: '/api/linea-medicamentos',
        summary: 'Listar todas las líneas de medicamento',
        tags: ['Líneas de Medicamento'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de líneas de medicamento',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/LineaMedicamento')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $lineas = $this->lineaMedicamentoService->getAll();

        return response()->json($lineas);
    }

    #[OA\Post(
        path: '/api/linea-medicamentos',
        summary: 'Crear una nueva línea de medicamento',
        tags: ['Líneas de Medicamento'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreLineaMedicamentoRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Línea de medicamento creada exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/LineaMedicamento')
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreLineaMedicamentoRequest $request): JsonResponse
    {
        $linea = $this->lineaMedicamentoService->create($request->validated());

        return response()->json($linea, 201);
    }

    #[OA\Get(
        path: '/api/linea-medicamentos/{id}',
        summary: 'Obtener una línea de medicamento por ID',
        tags: ['Líneas de Medicamento'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Línea de medicamento encontrada',
                content: new OA\JsonContent(ref: '#/components/schemas/LineaMedicamento')
            ),
            new OA\Response(response: 404, description: 'Línea de medicamento no encontrada'),
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $linea = $this->lineaMedicamentoService->getById($id);

        if (!$linea) {
            return response()->json(['message' => 'Línea de medicamento no encontrada'], 404);
        }

        return response()->json($linea);
    }

    #[OA\Put(
        path: '/api/linea-medicamentos/{id}',
        summary: 'Actualizar una línea de medicamento por ID',
        tags: ['Líneas de Medicamento'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateLineaMedicamentoRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Línea de medicamento actualizada exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/LineaMedicamento')
            ),
            new OA\Response(response: 404, description: 'Línea de medicamento no encontrada'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateLineaMedicamentoRequest $request, string $id): JsonResponse
    {
        $linea = $this->lineaMedicamentoService->update($id, $request->validated());

        return response()->json($linea);
    }

    #[OA\Delete(
        path: '/api/linea-medicamentos/{id}',
        summary: 'Eliminar una línea de medicamento por ID',
        tags: ['Líneas de Medicamento'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Línea de medicamento eliminada exitosamente'),
            new OA\Response(response: 404, description: 'Línea de medicamento no encontrada'),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->lineaMedicamentoService->delete($id);

        return response()->json(['message' => 'Línea de medicamento eliminada exitosamente']);
    }

    #[OA\Get(
        path: '/api/recetas/{recetaId}/linea-medicamentos',
        summary: 'Obtener las líneas de medicamento de una receta',
        tags: ['Líneas de Medicamento'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'recetaId', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Líneas de medicamento de la receta',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/LineaMedicamento')
                )
            ),
            new OA\Response(response: 404, description: 'Receta no encontrada'),
        ]
    )]
    public function getByReceta(string $recetaId): JsonResponse
    {
        $lineas = $this->lineaMedicamentoService->getByRecetaId($recetaId);

        return response()->json($lineas);
    }
}
