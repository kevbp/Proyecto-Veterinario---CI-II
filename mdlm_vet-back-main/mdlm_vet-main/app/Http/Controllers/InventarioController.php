<?php

namespace App\Http\Controllers;

use App\Services\InventarioService;
use App\Exports\FlujoInventarioExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class InventarioController extends Controller
{
    public function __construct(
        private readonly InventarioService $inventarioService
    ) {}

    #[OA\Post(
        path: '/api/inventario/ingreso-masivo',
        summary: 'Registrar ingreso de medicamentos (Compras/Facturas)',
        description: 'Aumenta el stock de múltiples medicamentos a la vez.',
        tags: ['Inventario y Kardex'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['motivo', 'items'],
                properties: [
                    new OA\Property(property: 'motivo', type: 'string', example: 'Ingreso por Factura F001-0234'),
                    new OA\Property(
                        property: 'items', 
                        type: 'array', 
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'medicamento_id', type: 'string', format: 'uuid'),
                                new OA\Property(property: 'cantidad', type: 'number', format: 'float', example: 50)
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200,
            description: 'Ingreso registrado correctamente',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(
                        property: 'data', 
                        type: 'array', 
                        items: new OA\Items(ref: '#/components/schemas/MovimientoInventario')
                    )
                ]
            ))
        ]
    )]
    public function ingresoMasivo(Request $request): JsonResponse
    {
        // NOTA: Lo ideal es que uses un FormRequest (Ej: IngresoMasivoRequest) para validar el array
        $validated = $request->validate([
            'motivo' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.medicamento_id' => 'required|uuid|exists:medicamentos,id',
            'items.*.cantidad' => 'required|numeric|min:0.1',
        ]);

        try {
            $user = auth('api')->user();
        
        // ¡EL ESCUDO PROTECTOR! Agrega esto:
        if (!$user || !$user->personal) {
            return response()->json([
                'message' => 'El usuario autenticado no tiene un perfil de personal asignado en la base de datos.'
            ], 403);
        }
        
        $personal_id = $user->personal->id;
            
            $movimientos = $this->inventarioService->registroMasivo(
                $validated['items'], 
                $validated['motivo'], 
                $personal_id
            );

            return response()->json([
                'message' => 'Ingreso masivo registrado exitosamente en el Kardex.',
                'data' => $movimientos
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    #[OA\Post(
        path: '/api/inventario/mermas',
        summary: 'Registrar mermas de fin de día',
        description: 'Registra frascos rotos, abiertos vencidos o pérdidas.',
        tags: ['Inventario y Kardex'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['mermas'],
                properties: [
                    new OA\Property(
                        property: 'mermas', 
                        type: 'array', 
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'medicamento_id', type: 'string', format: 'uuid'),
                                new OA\Property(property: 'cantidad', type: 'number', format: 'float', example: 2.5),
                                new OA\Property(property: 'motivo', type: 'string', example: 'Frasco abierto caducado (>24h)')
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200,
            description: 'Mermas registradas correctamente',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(
                        property: 'data', 
                        type: 'array', 
                        items: new OA\Items(ref: '#/components/schemas/MovimientoInventario')
                    )
                ]
            ))
        ]
    )]
    public function registrarMermas(Request $request): JsonResponse
    {
        // NOTA: Igualmente, te sugiero crear un RegistrarMermasRequest
        $validated = $request->validate([
            'mermas' => 'required|array|min:1',
            'mermas.*.medicamento_id' => 'required|uuid|exists:medicamentos,id',
            'mermas.*.cantidad' => 'required|numeric|min:0.1',
            'mermas.*.motivo' => 'required|string|max:255',
        ]);

        try {
            $user = auth('api')->user();
        
        // ¡EL ESCUDO PROTECTOR! Agrega esto:
        if (!$user || !$user->personal) {
            return response()->json([
                'message' => 'El usuario autenticado no tiene un perfil de personal asignado en la base de datos.'
            ], 403);
        }
        
        $personal_id = $user->personal->id;
            
            $movimientos = $this->inventarioService->registrarMermas(
                $validated['mermas'], 
                $personal_id
            );

            return response()->json([
                'message' => 'Mermas descontadas del inventario exitosamente.',
                'data' => $movimientos
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    #[OA\Get(
        path: '/api/inventario/exportar-flujo',
        summary: 'Exportar flujo de inventario',
        description: 'Exporta el flujo de inventario en un archivo Excel.',
        tags: ['Inventario y Kardex'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'fecha_inicio',
                in: 'query',
                description: 'Fecha de inicio del rango',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
            new OA\Parameter(
                name: 'fecha_fin',
                in: 'query',
                description: 'Fecha de fin del rango',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
            new OA\Parameter(
                name: 'medicamento_id',
                in: 'query',
                description: 'ID del medicamento (opcional)',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Archivo Excel generado correctamente',
                content: new OA\MediaType(mediaType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            )
        ]
    )]
    public function exportarFlujoInventario(Request $request)
    {
        $validated = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'medicamento_id' => 'nullable|uuid|exists:medicamentos,id',
        ]);

        try {
            $nombreArchivo = 'Kardex_' . $validated['fecha_inicio'] . '_al_' . $validated['fecha_fin'] . '.xlsx';

            // 2. Usamos Laravel Excel para descargar el archivo
            return Excel::download(
                new FlujoInventarioExport(
                    $validated['fecha_inicio'], 
                    $validated['fecha_fin'], 
                    $validated['medicamento_id'] ?? null
                ), 
                $nombreArchivo
            );

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al generar Excel: ' . $e->getMessage()], 400);
        }
    }
}