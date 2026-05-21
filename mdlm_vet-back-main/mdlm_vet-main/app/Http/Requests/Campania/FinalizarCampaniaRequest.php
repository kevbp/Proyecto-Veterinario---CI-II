<?php

namespace App\Http\Requests\Campania;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FinalizarCampaniaRequest',
    description: 'Request para finalizar una campaña de salud pública',
    required: ['insumos_consumidos'],
    properties: [
        new OA\Property(
            property: 'insumos_consumidos',
            type: 'array',
            description: 'Lista del conteo físico de medicamentos y vacunas (frascos vacíos) que regresaron al almacén.',
            items: new OA\Items(
                type: 'object',
                required: ['medicamento_id', 'cantidad'],
                properties: [
                    new OA\Property(property: 'medicamento_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000', description: 'UUID del insumo/medicamento usado'),
                    new OA\Property(property: 'cantidad', type: 'integer', example: 45, description: 'Cantidad total de unidades (ej. frascos) gastadas')
                ]
            )
        )
    ]
)]
class FinalizarCampaniaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'insumos_consumidos' => 'required|array',
            'insumos_consumidos.*.medicamento_id' => 'required|exists:medicamentos,id',
            'insumos_consumidos.*.cantidad' => 'required|integer|min:0'
        ];
    }
}
