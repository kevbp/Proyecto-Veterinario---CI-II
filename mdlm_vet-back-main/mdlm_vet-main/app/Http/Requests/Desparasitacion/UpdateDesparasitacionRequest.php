<?php

namespace App\Http\Requests\Desparasitacion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateDesparasitacionRequest',
    required: ['animal_id', 'medicamento_id', 'fecha_aplicacion', 'fecha_aplicacion_sgte', 'dosis', 'via', 'personal_id'],
    properties: [
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'medicamento_id', type: 'string', example: 'MED-001'),
        new OA\Property(property: 'fecha_aplicacion', type: 'string', format: 'date', example: '2026-04-13'),
        new OA\Property(property: 'fecha_aplicacion_sgte', type: 'string', format: 'date', example: '2026-04-13'),
        new OA\Property(property: 'dosis', type: 'string', example: '10mg'),
        new OA\Property(property: 'via', type: 'string', example: 'Oral'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Sin observaciones'),
        new OA\Property(property: 'cantidad', type: 'decimal', format: 'float', example: 1.00),
        new OA\Property(property: 'consulta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'campania_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
    ],
    type: 'object'
)]
class UpdateDesparasitacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'animal_id' => 'required_without:consulta_id|exists:animals,id',
            'medicamento_id' => 'required|exists:medicamentos,codigo',
            'fecha_aplicacion' => 'required|date',
            'fecha_aplicacion_sgte' => 'required|date',
            'dosis' => 'required|string',
            'via' => 'required|string',
            'observaciones' => 'nullable|string',
            'cantidad' => 'required|numeric|min:0.1',
            'consulta_id' => 'nullable|required_without:animal_id|exists:consultas,id',
            'campania_id' => 'nullable|exists:campanias,id'
        ];
    }

    public function messages(): array
    {
        return [
            'animal_id.required_without' => 'El animal es obligatorio o la consulta es obligatoria.',
            'animal_id.exists' => 'El animal seleccionado no existe.',
            'medicamento_id.required' => 'El medicamento es obligatorio.',
            'medicamento_id.exists' => 'El medicamento seleccionado no existe.',
            'fecha_aplicacion.required' => 'La fecha de aplicación es obligatoria.',
            'fecha_aplicacion.date' => 'La fecha de aplicación debe ser una fecha válida.',
            'fecha_aplicacion_sgte.required' => 'La fecha de aplicación siguiente es obligatoria.',
            'fecha_aplicacion_sgte.date' => 'La fecha de aplicación siguiente debe ser una fecha válida.',
            'dosis.required' => 'La dosis es obligatoria.',
            'via.required' => 'La vía es obligatoria.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.numeric' => 'La cantidad debe ser un número.',
            'cantidad.min' => 'La cantidad debe ser al menos 0.1.',
            'consulta_id.required_without' => 'La consulta es obligatoria o el animal es obligatorio.',
            'consulta_id.exists' => 'La consulta seleccionada no existe.',
            'campania_id.exists' => 'La campaña seleccionada no existe.',
        ];
    }
}
