<?php

namespace App\Http\Controllers;

use App\DTOs\Desparasitacion\CreateDesparasitacionDTO;
use App\DTOs\Desparasitacion\UpdateDesparasitacionDTO;
use App\Http\Requests\Desparasitacion\StoreDesparasitacionRequest;
use App\Http\Requests\Desparasitacion\UpdateDesparasitacionRequest;
use App\Services\Contracts\DesparasitacionServiceInterface;
use OpenApi\Attributes as OA;
use Illuminate\Http\JsonResponse;

class DesparasitacionController extends Controller
{
    public function __construct(private DesparasitacionServiceInterface $desparasitacionService){}
    
    #[OA\Get(
        path: '/api/desparasitaciones',
        summary: 'Listar todas las desparasitaciones',
        tags: ['Desparasitaciones'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de desparasitaciones',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Desparasitacion')
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {   
        $desparasitaciones = $this->desparasitacionService->getAll();
        return response()->json($desparasitaciones);
    }

    #[OA\Post(
        path: '/api/desparasitaciones',
        summary: 'Crear una nueva desparasitacion',
        tags: ['Desparasitaciones'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreDesparasitacionRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Desparasitacion creada correctamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Desparasitacion'
                )
            )
        ]
    )]
    public function store(StoreDesparasitacionRequest $request): JsonResponse
    {
        $user = auth('api')->user();

        if (!$user || !$user->personal) abort(403, 'El usuario autenticado no tiene un perfil de personal asignado.');
        
        $validated = $request->validated();
        $personal_id = $user->personal->id;
        $animal_id = $validated['animal_id'] ?? null;

        //PASAMOS INFORMACION DE LA CONSULTA AL DTO SI ES QUE VIENE EN EL REQUEST
        if (!empty($validated['consulta_id'])) {
            $consulta = Consulta::findOrFail($validated['consulta_id']);
            $animal_id = $consulta->animal_id;
            $personal_id = $consulta->personal_id;
        }

        // Le pasamos el animal_id definitivo al arreglo
        $validated['animal_id'] = $animal_id;

        $dto = CreateDesparasitacionDTO::fromRequest(
            $validated,
            $personal_id,
            $validated['consulta_id'] ?? null,
            $validated['campania_id'] ?? null
        );

        $desparasitacion = $this->desparasitacionService->create($dto);

        return response()->json($desparasitacion, 201);
    }

    #[OA\Get(
        path: '/api/desparasitaciones/{id}',
        summary: 'Obtener una desparasitacion por ID',
        tags: ['Desparasitaciones'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Desparasitacion obtenida correctamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Desparasitacion'
                )
            )
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $desparasitacion = $this->desparasitacionService->getById($id);
        return response()->json($desparasitacion);
    }

    #[OA\Put(
        path: '/api/desparasitaciones/{id}',
        summary: 'Actualizar una desparasitacion por ID',
        tags: ['Desparasitaciones'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateDesparasitacionRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Desparasitacion actualizada correctamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Desparasitacion'
                )
            )
        ]
    )]
    public function update(UpdateDesparasitacionRequest $request, string $id): JsonResponse
    {
        $dto = UpdateDesparasitacionDTO::fromRequest($request->validated());
        $desparasitacion = $this->desparasitacionService->update($dto, $id);
        return response()->json($desparasitacion);
    }

    #[OA\Delete(
        path: '/api/desparasitaciones/{id}',
        summary: 'Eliminar una desparasitacion por ID',
        tags: ['Desparasitaciones'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Desparasitacion eliminada correctamente',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Desparasitacion'
                )
            )
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->desparasitacionService->delete($id);
        return response()->json(null, 204);
    }
}
