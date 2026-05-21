<?php

namespace App\Http\Controllers;

use App\Http\Requests\Medicamento\StoreMedicamentoRequest;
use App\Http\Requests\Medicamento\UpdateMedicamentoRequest;
use App\DTOs\Medicamento\CreateMedicamentoDTO;
use App\DTOs\Medicamento\UpdateMedicamentoDTO;
use App\Services\Contracts\MedicamentoServiceInterface;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class MedicamentoController extends Controller
{
    public function __construct(private readonly MedicamentoServiceInterface $medicamentoService)
    {
    }

    #[OA\Get(
        path: '/api/medicamentos',
        summary: 'Listar todos los medicamentos',
        tags: ['Medicamentos'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de medicamentos',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Medicamento')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $medicamentos = $this->medicamentoService->getAll();

        return response()->json($medicamentos);
    }

    #[OA\Post(
        path: '/api/medicamentos',
        summary: 'Crear un nuevo medicamento',
        tags: ['Medicamentos'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreMedicamentoRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Medicamento creado exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Medicamento')
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreMedicamentoRequest $request): JsonResponse
    {
        $dto = CreateMedicamentoDTO::fromRequest($request->validated());
        $medicamento = $this->medicamentoService->create($dto);

        return response()->json($medicamento, 201);
    }

    #[OA\Get(
        path: '/api/medicamentos/{id}',
        summary: 'Obtener un medicamento por ID',
        tags: ['Medicamentos'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Medicamento encontrado',
                content: new OA\JsonContent(ref: '#/components/schemas/Medicamento')
            ),
            new OA\Response(response: 404, description: 'Medicamento no encontrado'),
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $medicamento = $this->medicamentoService->getById($id);

        if (!$medicamento) {
            return response()->json(['message' => 'Medicamento no encontrado'], 404);
        }

        return response()->json($medicamento);
    }

    #[OA\Put(
        path: '/api/medicamentos/{id}',
        summary: 'Actualizar un medicamento por ID',
        tags: ['Medicamentos'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateMedicamentoRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Medicamento actualizado exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Medicamento')
            ),
            new OA\Response(response: 404, description: 'Medicamento no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateMedicamentoRequest $request, string $id): JsonResponse
    {
        $dto = UpdateMedicamentoDTO::fromRequest($request->validated());
        $medicamento = $this->medicamentoService->update($id, $dto);

        return response()->json($medicamento);
    }

    #[OA\Delete(
        path: '/api/medicamentos/{id}',
        summary: 'Eliminar un medicamento por ID',
        tags: ['Medicamentos'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Medicamento eliminado exitosamente'),
            new OA\Response(response: 404, description: 'Medicamento no encontrado'),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->medicamentoService->delete($id);

        return response()->json(['message' => 'Medicamento eliminado exitosamente']);
    }
}
