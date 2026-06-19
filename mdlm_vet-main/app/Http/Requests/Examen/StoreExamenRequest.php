<?php

namespace App\Http\Requests\Examen;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreExamenRequest',
    required: ['nombre', 'tipo_examen_id', 'descripcion', 'estado', 'fecha_solicitud'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Hemograma'),
        new OA\Property(property: 'tipo_examen_id', type: 'string', example: 'RX-001'),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Examen completo de sangre'),
        new OA\Property(property: 'estado', type: 'string', example: 'Pendiente'),
        new OA\Property(property: 'fecha_hora', type: 'string', format: 'date-time', example: '2024-06-01T10:00:00Z'),
        new OA\Property(property: 'fecha_resultado', type: 'string', format: 'date-time', example: '2024-06-01T10:00:00Z'),
        new OA\Property(property: 'consulta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
    ],
    type: 'object'
)]
class StoreExamenRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:255'],
            'tipo_examen_id' => ['required', 'exists:tipo_examens,codigo'],
            'descripcion' => ['required', 'string'],
            'estado' => ['required', 'string', 'in:Pendiente,En Progreso,Completado'],
            'fecha_hora' => ['required', 'date'],
            'fecha_resultado' => ['nullable', 'date', 'after_or_equal:fecha_hora'],
            'consulta_id' => ['nullable', 'uuid', 'exists:consultas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del examen es obligatorio.',
            'tipo_examen_id.required' => 'El tipo de examen es obligatorio.',
            'tipo_examen_id.exists' => 'El tipo de examen especificado no existe.',
            'descripcion.required' => 'La descripción del examen es obligatoria.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de los valores permitidos.',
            'fecha_hora.required' => 'La fecha y hora son obligatorias.',
            'fecha_hora.date' => 'La fecha y hora deben ser una fecha válida.',
            'fecha_resultado.date' => 'La fecha de resultado debe ser una fecha válida.',
            'fecha_resultado.after_or_equal' => 'La fecha de resultado debe ser igual o posterior a la fecha y hora.',
            'consulta_id.uuid' => 'La consulta debe ser un UUID válido.',
            'consulta_id.exists' => 'La consulta especificada no existe.',
        ];
    }
}
