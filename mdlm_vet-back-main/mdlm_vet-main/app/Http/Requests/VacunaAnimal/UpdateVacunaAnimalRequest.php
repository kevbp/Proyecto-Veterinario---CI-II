<?php

namespace App\Http\Requests\VacunaAnimal;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateVacunaAnimalRequest',
    required: ['fecha_aplicacion', 'dosis', 'lote', 'personal_id'],
    properties: [
        new OA\Property(property: 'fecha_aplicacion', type: 'string', format: 'date', example: '2026-04-13'),
        new OA\Property(property: 'fecha_proxima', type: 'string', format: 'date', example: '2026-04-13'),
        new OA\Property(property: 'dosis', type: 'string', example: '5 ml'),
        new OA\Property(property: 'lote', type: 'string', example: '123456'),
        new OA\Property(property: 'fabricante', type: 'string', example: 'Fabricante'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Sin observaciones'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'cantidad', type: 'integer', example: 1),
        new OA\Property(property: 'esquema_vacuna_id', type: 'string', example: 'ESQ-CAN-001'),
        new OA\Property(property: 'medicamento_id', type: 'string', example: 'MED-CAN-001'),
        new OA\Property(property: 'consulta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'campania_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
    ],
    type: 'object'
)]
class UpdateVacunaAnimalRequest extends FormRequest
{
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
            'fecha_aplicacion' => 'required|date',
            'fecha_proxima' => 'nullable|date',
            'dosis' => 'required|string',
            'lote' => 'required|string',
            'fabricante' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'animal_id' => 'nullable|required_without:consulta_id|uuid|exists:animals,id',
            'cantidad' => 'nullable|integer|min:1',
            'esquema_vacuna_id' => 'nullable|required|uuid|exists:esquema_vacunas,codigo',
            'medicamento_id' => 'nullable|required|exists:medicamentos,codigo',
            'consulta_id' => 'nullable|required_without:animal_id|uuid|exists:consultas,id',
            'campania_id' => 'nullable|uuid|exists:campanias,id'
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_aplicacion.required' => 'La fecha de aplicación es obligatoria.',
            'fecha_aplicacion.date' => 'La fecha de aplicación debe ser una fecha válida.',
            'fecha_proxima.date' => 'La fecha próxima debe ser una fecha válida.',
            'dosis.required' => 'El número de dosis es obligatorio.',
            'dosis.string' => 'El número de dosis debe ser una cadena de texto.',
            'lote.required' => 'El lote es obligatorio.',
            'lote.string' => 'El lote debe ser una cadena de texto.',
            'fabricante.string' => 'El fabricante debe ser una cadena de texto.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'animal_id.required_without' => 'El animal es obligatorio o la consulta es obligatoria.',
            'animal_id.uuid' => 'El animal debe ser un UUID válido.',
            'animal_id.exists' => 'El animal seleccionado no existe.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser un número positivo.',
            'esquema_vacuna_id.required' => 'El esquema de vacuna es obligatorio.',
            'esquema_vacuna_id.uuid' => 'El esquema de vacuna debe ser un UUID válido.',
            'esquema_vacuna_id.exists' => 'El esquema de vacuna seleccionado no existe.',
            'medicamento_id.required' => 'El medicamento es obligatorio.',
            'medicamento_id.exists' => 'El medicamento seleccionado no existe.',
            'consulta_id.required_without' => 'La consulta es obligatoria o el animal es obligatorio.',
            'consulta_id.uuid' => 'La consulta debe ser un UUID válido.',
            'consulta_id.exists' => 'La consulta seleccionada no existe.',
            'campania_id.uuid' => 'La campaña debe ser un UUID válido.',
            'campania_id.exists' => 'La campaña seleccionada no existe.',
        ];
    }
}
