<?php

namespace App\Http\Requests\Cita;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateCitaRequest',
    properties: [
        new OA\Property(property: 'fecha_hora', type: 'string', format: 'date-time', example: '2022-01-01T10:00:00.000000Z'),
        new OA\Property(property: 'motivo', type: 'string', example: 'Consulta general'),
        new OA\Property(property: 'estado_cita_id', type: 'string', format: 'uuid', example: 'PROGRAMADA'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Consulta general'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'personal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
    ]
)]
class UpdateCitaRequest extends FormRequest
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
            'fecha_hora' => 'sometimes|date',
            'motivo' => 'sometimes|string',
            'observaciones' => 'nullable|string',
            'estado_cita_id' => 'sometimes|exists:estado_citas,codigo',
            'animal_id' => 'sometimes|exists:animals,id',
            'personal_id' => 'sometimes|exists:personal,id',
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_hora.sometimes' => 'La fecha y hora debe ser una fecha válida.',
            'motivo.sometimes' => 'El motivo debe ser una cadena de texto.',
            'observaciones.nullable' => 'Las observaciones deben ser una cadena de texto.',
            'estado_cita_id.sometimes' => 'El estado de cita debe ser una cadena de texto.',
            'estado_cita_id.exists' => 'El estado de cita seleccionado no existe.',
            'animal_id.sometimes' => 'El animal debe ser una cadena de texto.',
            'animal_id.exists' => 'El animal seleccionado no existe.',
            'personal_id.sometimes' => 'El personal debe ser una cadena de texto.',
            'personal_id.exists' => 'El personal seleccionado no existe.',
        ];
    }
}
