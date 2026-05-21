<?php

namespace App\Http\Controllers;

use App\DTOs\Consulta\CreateConsultaDTO;
use App\DTOs\Consulta\UpdateConsultaDTO;
use App\Http\Requests\Consulta\StoreConsultaRequest;
use App\Http\Requests\Consulta\UpdateConsultaRequest;
use App\Models\Consulta;
use App\Services\Contracts\ConsultaServiceInterface;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class ConsultaController extends Controller
{
    public function __construct(private ConsultaServiceInterface $consultaService) {}

    #[OA\Get(
        path: '/api/consultas',
        summary: 'Listar todas las consultas',
        tags: ['Consultas'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de consultas',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Consulta')
                )
            ),
        ]
    )]
    public function index()
    {
        $consultas = $this->consultaService->getAll();

        return response()->json($consultas);
    }

    #[OA\Post(
        path: '/api/consultas',
        summary: 'Crear una nueva consulta',
        description: '
                        Si no se provee un cita_id, el backend generará automáticamente una Cita de "ATENCION INMEDIATA"; y será requerido el animal_id.
                        Si se provee un cita_id, se validará que la cita exista y que su animal_id sea válido.
                        La fecha_hora de la consulta será la fecha_hora actual.
                        ',
        tags: ['Consultas'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreConsultaRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Consulta creada exitosamente',
                content: new OA\JsonContent(ref: '#/components/schemas/Consulta')
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreConsultaRequest $request)
    {
        $user = auth('api')->user();

        if (! $user || ! $user->personal) {
            abort(403, 'El usuario autenticado no tiene un perfil de personal asignado.');
        }

        $validated = $request->validated();

        $dto = CreateConsultaDTO::fromRequest(
            $validated,
            $user->personal->id,
            $validated['cita_id'] ?? null // Puede ser null si es walk-in
        );

        $consulta = DB::transaction(function () use ($dto) {
            return $this->consultaService->create($dto);
        });

        return response()->json($consulta, 201);
    }

    #[OA\Get(
        path: '/api/consultas/{id}',
        summary: 'Obtener una consulta por ID',
        tags: ['Consultas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Consulta encontrada', content: new OA\JsonContent(ref: '#/components/schemas/Consulta')),
            new OA\Response(response: 404, description: 'Consulta no encontrada'),
        ]
    )]
    public function show(Consulta $consulta)
    {
        $consulta = $this->consultaService->getById($consulta->id);

        return response()->json($consulta);
    }

    #[OA\Put(
        path: '/api/consultas/{id}',
        summary: 'Actualizar una consulta',
        tags: ['Consultas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateConsultaRequest')
        ),
        responses: [
            new OA\Response(response: 200, description: 'Consulta actualizada', content: new OA\JsonContent(ref: '#/components/schemas/Consulta')),
            new OA\Response(response: 404, description: 'Consulta no encontrada'),
        ]
    )]
    public function update(UpdateConsultaRequest $request, Consulta $consulta)
    {
        $dto = UpdateConsultaDTO::fromRequest($request->validated());
        $updatedConsulta = $this->consultaService->update($consulta->id, $dto);

        return response()->json($updatedConsulta);
    }

    #[OA\Delete(
        path: '/api/consultas/{id}',
        summary: 'Eliminar una consulta',
        tags: ['Consultas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Consulta eliminada exitosamente'),
            new OA\Response(response: 404, description: 'Consulta no encontrada'),
        ]
    )]
    public function destroy(Consulta $consulta)
    {
        $this->consultaService->delete($consulta->id);

        return response()->json(['message' => 'Consulta eliminada exitosamente']);
    }
}
