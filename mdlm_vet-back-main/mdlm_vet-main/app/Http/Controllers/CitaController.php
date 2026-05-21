<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\EstadoCita;
use App\Http\Requests\Cita\StoreCitaRequest;
use App\Http\Requests\Cita\UpdateCitaRequest;
use App\DTOs\Cita\CreateCitaDTO;
use App\DTOs\Cita\UpdateCitaDTO;
use App\Services\Contracts\CitaServiceInterface;
use OpenApi\Attributes as OA;

class CitaController extends Controller
{
    public function __construct(private CitaServiceInterface $citaService)
    {
    }

    #[OA\Get(
        path: '/api/citas',
        summary: 'Listar todas las citas',
        tags: ['Citas'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de citas',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Cita')
                )
            )
        ]
    )]
    public function index()
    {
        $citas = $this->citaService->getAll();
        return response()->json($citas);
    }

    #[OA\Post(
        path: '/api/citas',
        summary: 'Crear una nueva cita',
        tags: ['Citas'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreCitaRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Cita creada exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Cita')
            ),
            new OA\Response(
                response: 422,
                description: 'Error de validación',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Error de validación'),
                        new OA\Property(property: 'errors', type: 'object', example: ['fecha_hora' => ['La fecha y hora debe ser una fecha válida.']]),
                    ]
                )
            )
        ]
    )]
    public function store(StoreCitaRequest $request)
    {
        $user = auth('api')->user();

        if (!$user || !$user->personal) {
            abort(403, 'El usuario autenticado no tiene un perfil de personal asignado.');
        }

        $validated = $request->validated();

        if (array_key_exists('estado_cita_id', $validated)) {
            $estadoCita = EstadoCita::where('codigo', $validated['estado_cita_id'])->first();
            if ($estadoCita) {
                $validated['estado_cita_id'] = $estadoCita->id;
            }
        }

        $dto = CreateCitaDTO::fromRequest($validated, $user->personal->id);
        $cita = $this->citaService->create($dto);

        return response()->json($cita, 201);
    }

    #[OA\Get(
        path: '/api/citas/{id}',
        summary: 'Obtener una cita por ID',
        tags: ['Citas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cita encontrada',
                content: new OA\JsonContent(ref: '#/components/schemas/Cita')
            ),
            new OA\Response(
                response: 404,
                description: 'Cita no encontrada',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Cita no encontrada'),
                    ]
                )
            )
        ]
    )]
    public function show(Cita $cita)
    {
        $cita = $this->citaService->getById($cita->id);
        return response()->json($cita);
    }

    #[OA\Put(
        path: '/api/citas/{id}',
        summary: 'Actualizar una cita por ID',
        tags: ['Citas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateCitaRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cita actualizada exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Cita')
            ),
            new OA\Response(
                response: 404,
                description: 'Cita no encontrada',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Cita no encontrada'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Error de validación',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Error de validación'),
                        new OA\Property(property: 'errors', type: 'object', example: ['fecha_hora' => ['La fecha y hora debe ser una fecha válida.']]),
                    ]
                )
            )
        ]
    )]
    public function update(UpdateCitaRequest $request, Cita $cita)
    {
        $user = auth('api')->user();
        $validated = $request->validated();

        if (array_key_exists('personal_id', $validated)) {
            if (!$user->isGestor() && !$user->hasRole('recepcionista')) {
                unset($validated['personal_id']);
            }
        }

        if (array_key_exists('estado_cita_id', $validated)) {
            $estadoCita = EstadoCita::where('codigo', $validated['estado_cita_id'])->first();
            if ($estadoCita) {
                $validated['estado_cita_id'] = $estadoCita->id;
            }
        }

        $dto = UpdateCitaDTO::fromRequest($validated);
        $updatedCita = $this->citaService->update($cita->id, $dto);

        return response()->json($updatedCita);
    }

    #[OA\Delete(
        path: '/api/citas/{id}',
        summary: 'Eliminar una cita por ID',
        tags: ['Citas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cita eliminada exitosamente',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Cita eliminada exitosamente'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Cita no encontrada',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Cita no encontrada'),
                    ]
                )
            )
        ]
    )]
    public function destroy(Cita $cita)
    {
        $this->citaService->delete($cita->id);
        return response()->json([
            'message' => 'Cita eliminada exitosamente',
        ]);
    }
}
