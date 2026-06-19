<?php

namespace App\Http\Requests\Receta;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateRecetaRequest',
    description: 'Request para actualizar una receta',
    properties: [
        new OA\Property(property: 'estado_consulta', type: 'string', example: 'finalizada'),
        new OA\Property(property: 'indicaciones_generales', type: 'string', example: 'El paciente debe estar relajado durante la administración del medicamento.'),
        new OA\Property(property: 'fecha_emision', type: 'string', format: 'date-time'),
        new OA\Property(property: 'fecha_vencimiento', type: 'string', format: 'date-time'),
    ]
)]
class UpdateRecetaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'estado_consulta' => ['sometimes', 'string'],
            'indicaciones_generales' => ['sometimes', 'string'],
            'fecha_emision' => ['sometimes', 'string', 'date_format:Y-m-d H:i:s'],
            'fecha_vencimiento' => ['sometimes', 'string', 'date_format:Y-m-d H:i:s'],
        ];
    }

    public function messages(): array
    {
        return [
            'estado_consulta.string' => 'El estado de la consulta debe ser una cadena de texto',
            'indicaciones_generales.string' => 'Las indicaciones generales deben ser una cadena de texto',
            'fecha_emision.string' => 'La fecha de emisión debe ser una cadena de texto',
            'fecha_emision.date_format' => 'La fecha de emisión debe tener el formato Y-m-d H:i:s',
            'fecha_vencimiento.string' => 'La fecha de vencimiento debe ser una cadena de texto',
            'fecha_vencimiento.date_format' => 'La fecha de vencimiento debe tener el formato Y-m-d H:i:s',
        ];
    }
}
