<?php

namespace App\Http\Requests\Consulta;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreConsultaRequest',
    required: ['motivo'],
    properties: [
        new OA\Property(property: 'motivo', type: 'string', example: 'Vómito y diarrea'),
        new OA\Property(property: 'diagnostico', type: 'string', example: 'Infección estomacal'),
        new OA\Property(property: 'tratamiento', type: 'string', example: 'Antibióticos por 5 días'),
        new OA\Property(property: 'peso_registrado', type: 'number', format: 'float', example: 12.5),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Paciente colaborador'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'cita_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000', description: 'Opcional. Si no se provee, se iniciará una cita de atención inmediata.'),
    ]
)]
class StoreConsultaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'motivo' => 'required|string',
            'diagnostico' => 'nullable|string',
            'tratamiento' => 'nullable|string',
            'peso_registrado' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
            'animal_id' => 'required_without:cita_id|nullable|uuid|exists:animals,id',
            'cita_id' => 'required_without:animal_id|nullable|uuid|exists:citas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'motivo.required' => 'El motivo es obligatorio.',
            'motivo.string' => 'El motivo debe ser una cadena de texto.',
            'diagnostico.string' => 'El diagnóstico debe ser una cadena de texto.',
            'tratamiento.string' => 'El tratamiento debe ser una cadena de texto.',
            'peso_registrado.numeric' => 'El peso registrado debe ser un número.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'animal_id.required_without' => 'El animal es obligatorio o la cita es obligatoria.',
            'animal_id.exists' => 'El animal seleccionado no existe.',
            'cita_id.required_without' => 'La cita es obligatoria o el animal es obligatorio.',
            'cita_id.exists' => 'La cita seleccionada no existe.',
        ];
    }
}
