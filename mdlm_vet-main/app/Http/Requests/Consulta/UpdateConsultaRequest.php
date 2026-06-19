<?php

namespace App\Http\Requests\Consulta;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateConsultaRequest',
    properties: [
        new OA\Property(property: 'fecha_hora', type: 'string', format: 'date-time', example: '2026-04-13 14:00:00'),
        new OA\Property(property: 'motivo', type: 'string', example: 'Control mensual'),
        new OA\Property(property: 'diagnostico', type: 'string', example: 'Sano'),
        new OA\Property(property: 'tratamiento', type: 'string', example: 'Vitaminas'),
        new OA\Property(property: 'peso_registrado', type: 'number', format: 'float', example: 10.2),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Ninguna'),
    ]
)]
class UpdateConsultaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_hora' => 'sometimes|date',
            'motivo' => 'sometimes|string',
            'diagnostico' => 'nullable|string',
            'tratamiento' => 'nullable|string',
            'peso_registrado' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
        ];
    }
}
