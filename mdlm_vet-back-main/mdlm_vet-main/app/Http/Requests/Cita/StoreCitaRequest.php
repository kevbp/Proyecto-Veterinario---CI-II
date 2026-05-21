<?php

namespace App\Http\Requests\Cita;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreCitaRequest',
    required: ['fecha_hora', 'motivo', 'estado_cita_id', 'animal_id', 'personal_id'],
    properties: [
        new OA\Property(property: 'fecha_hora', type: 'string', format: 'date-time', example: '2026-04-01 10:00:00'),
        new OA\Property(property: 'motivo', type: 'string', example: 'Consulta general'),
        new OA\Property(property: 'estado_cita_id', type: 'string', format: 'uuid', example: 'PROGRAMADA'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Consulta general'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
    ]
)]
class StoreCitaRequest extends FormRequest
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
            'fecha_hora' => 'required|date',
            'motivo' => 'required|string',
            'observaciones' => 'nullable|string',
            'estado_cita_id' => 'required|exists:estado_citas,codigo',
            'animal_id' => 'required|exists:animals,id',
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_hora.required' => 'La fecha y hora son obligatorias.',
            'fecha_hora.date' => 'La fecha y hora debe ser una fecha válida.',
            'motivo.required' => 'El motivo es obligatorio.',
            'motivo.string' => 'El motivo debe ser una cadena de texto.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'estado_cita_id.required' => 'El estado de cita es obligatorio.',
            'estado_cita_id.exists' => 'El estado de cita seleccionado no existe.',
            'animal_id.required' => 'El animal es obligatorio.',
            'animal_id.exists' => 'El animal seleccionado no existe.',
        ];
    }
}
