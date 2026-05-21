<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\TipoDocumento;
use App\Http\Requests\Personal\StorePersonalRequest;
use App\Http\Requests\Personal\UpdatePersonalRequest;
use App\DTOs\Personal\CreatePersonalDTO;
use App\DTOs\Personal\UpdatePersonalDTO;
use App\Services\Contracts\PersonalServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Personal', description: 'Endpoints de administración de personal')]
class PersonalController extends Controller
{
    public function __construct(private PersonalServiceInterface $personalService) {}

    #[OA\Get(
        path: '/api/personal',
        summary: 'Listar personal',
        tags: ['Personal'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de personal',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Personal'))
            )
        ]
    )]
    public function index(): JsonResponse
    {
        return response()->json($this->personalService->getAll());
    }

    #[OA\Post(
        path: '/api/personal',
        summary: 'Crear e invitar personal',
        description: 'Crea a la persona y le envía un correo electrónico de invitación para que genere su credencial.',
        tags: ['Personal'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StorePersonalRequest')),
        responses: [
            new OA\Response(response: 201, description: 'Personal creado e invitado', content: new OA\JsonContent(ref: '#/components/schemas/Personal'))
        ]
    )]
    public function store(StorePersonalRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        
        if (isset($validatedData['tipo_doc_id'])) {
            $validatedData['tipo_doc_id'] = TipoDocumento::where('codigo', $validatedData['tipo_doc_id'])->value('id');
        }

        $dto = CreatePersonalDTO::fromRequest($validatedData);
        $personal = $this->personalService->create($dto);
        
        return response()->json($personal, 201);
    }

    #[OA\Get(
        path: '/api/personal/{id}',
        summary: 'Ver un personal',
        tags: ['Personal'],
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [
            new OA\Response(response: 200, description: 'Detalle del personal', content: new OA\JsonContent(ref: '#/components/schemas/Personal')),
            new OA\Response(response: 404, description: 'Personal no encontrado')
        ]
    )]
    public function show(string $id): JsonResponse
    {
        return response()->json($this->personalService->getById($id));
    }

    #[OA\Put(
        path: '/api/personal/{id}',
        summary: 'Actualizar personal',
        tags: ['Personal'],
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdatePersonalRequest')),
        responses: [
            new OA\Response(response: 200, description: 'Personal actualizado', content: new OA\JsonContent(ref: '#/components/schemas/Personal'))
        ]
    )]
    public function update(UpdatePersonalRequest $request, string $id): JsonResponse
    {
        $validatedData = $request->validated();
        
        if (isset($validatedData['tipo_doc_id'])) {
            $validatedData['tipo_doc_id'] = TipoDocumento::where('codigo', $validatedData['tipo_doc_id'])->value('id');
        }
        
        $dto = UpdatePersonalDTO::fromRequest($validatedData);
        $personal = $this->personalService->update($id, $dto);
        
        return response()->json($personal);
    }

    #[OA\Delete(
        path: '/api/personal/{id}',
        summary: 'Eliminar personal',
        tags: ['Personal'],
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [
            new OA\Response(response: 204, description: 'Personal eliminado')
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->personalService->delete($id);
        return response()->json(null, 204);
    }

    #[OA\Post(
        path: '/api/personal/{id}/reenviar-invitacion',
        summary: 'Reenviar invitación',
        description: 'Renueva el token y reenvía el correo de acceso a este personal.',
        tags: ['Personal'],
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [
            new OA\Response(response: 200, description: 'Invitación reenviada', content: new OA\JsonContent(ref: '#/components/schemas/Personal')),
            new OA\Response(response: 404, description: 'Personal no encontrado')
        ]
    )]
    public function resendInvitation(string $id): JsonResponse
    {
        $personal = $this->personalService->resendInvitation($id);
        return response()->json($personal);
    }
}
