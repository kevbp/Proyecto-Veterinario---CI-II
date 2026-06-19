<?php

namespace App\Http\Requests\Adopcion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreAdopcionRequest',
    description: 'Request para registrar la adopción de un animal',
    required: ['propietario_nuevo_id', 'fecha_adopcion'],
    properties: [
        new OA\Property(property: 'propietario_nuevo_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'campania_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'El nuevo propietario tiene experiencia previa con mascotas.'),
    ]
)]
class StoreAdopcionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'propietario_nuevo_id' => 'required|uuid|exists:propietarios,id',
            'fecha_adopcion' => 'required|date|date_format:Y-m-d',
            'observaciones' => 'nullable|string|max:1000',
            'campania_id' => [
                'nullable',
                'uuid',
                Rule::exists('campanias', 'id') // ->where('estado', EstadoCampania::EN_CURSO->value) (Opcional)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'propietario_nuevo_id.required' => 'El ID del nuevo propietario es obligatorio.',
            'propietario_nuevo_id.uuid' => 'El ID del nuevo propietario debe ser un UUID válido.',
            'propietario_nuevo_id.exists' => 'El nuevo propietario no existe en el sistema.',
            'fecha_adopcion.required' => 'La fecha de adopción es obligatoria.',
            'fecha_adopcion.date' => 'La fecha de adopción debe ser una fecha válida.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 1000 caracteres.',
            'campania_id.uuid' => 'El ID de la campaña debe ser un UUID válido.',
            'campania_id.exists' => 'La campaña especificada no existe en el sistema.',
        ];
    }
}
