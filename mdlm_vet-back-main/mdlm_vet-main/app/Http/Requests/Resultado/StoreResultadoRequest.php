<?php

namespace App\Http\Requests\Resultado;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreResultadoRequest',
    description: 'Solicitud para crear un nuevo resultado',
    required: ['examen_id', 'hallazgos', 'valores'],
    properties: [
        new OA\Property(property: 'examen_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'hallazgos', type: 'string', example: 'Hallazgos del examen'),
        new OA\Property(property: 'valores', type: 'string', example: 'Valores obtenidos'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Observaciones adicionales'),
        new OA\Property(property: 'interpretacion', type: 'string', example: 'Interpretación de los resultados'),
    ],
    type: 'object'
)]
class StoreResultadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'examen_id' => 'required|exists:examens,id',
            'hallazgos' => 'required|string',
            'valores' => 'required|string',
            'observaciones' => 'nullable|string',
            'interpretacion' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'examen_id.required' => 'El campo examen_id es obligatorio.',
            'examen_id.exists' => 'El examen_id proporcionado no existe.',
            'hallazgos.required' => 'El campo hallazgos es obligatorio.',
            'hallazgos.string' => 'El campo hallazgos debe ser una cadena de texto.',
            'valores.required' => 'El campo valores es obligatorio.',
            'valores.string' => 'El campo valores debe ser una cadena de texto.',
            'observaciones.string' => 'El campo observaciones debe ser una cadena de texto.',
            'interpretacion.string' => 'El campo interpretacion debe ser una cadena de texto.',
        ];
    }
}
