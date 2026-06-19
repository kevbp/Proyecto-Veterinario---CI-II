<?php

namespace App\Http\Requests\Animal;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreAnimalRequest',
    required: ['propietario_id', 'nombre', 'especie_id', 'raza_id', 'sexo', 'color', 'esterilizacion'],
    properties: [
        new OA\Property(property: 'propietario_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Firulais'),
        new OA\Property(property: 'especie_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'raza_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'sexo', type: 'string', enum: ['Macho', 'Hembra'], example: 'Macho'),
        new OA\Property(property: 'color', type: 'string', example: 'Marrón'),
        new OA\Property(property: 'esterilizacion', type: 'boolean', example: false),
        new OA\Property(property: 'fallecido', type: 'boolean', example: false),
        new OA\Property(property: 'fecha_fallecimiento', type: 'string', format: 'date', nullable: true, example: '2026-05-07'),
    ],
    type: 'object'
)]
class StoreAnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'propietario_id' => ['required', 'uuid', 'exists:propietarios,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'especie_id' => ['required', 'uuid', 'exists:especies,id'],
            'raza_id' => ['required', 'uuid', 'exists:razas,id'],
            'sexo' => ['required', 'string', 'in:Macho,Hembra'],
            'color' => ['required', 'string', 'max:255'],
            'esterilizacion' => ['required', 'boolean'],
            'fallecido' => ['sometimes', 'boolean'],
            'fecha_fallecimiento' => ['nullable', 'date', 'before_or_equal:today', 'required_if:fallecido,true'],
        ];
    }

    public function messages(): array
    {
        return [
            'propietario_id.required' => 'El propietario es requerido.',
            'propietario_id.uuid' => 'El propietario debe ser un UUID válido.',
            'propietario_id.exists' => 'El propietario no existe.',
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre debe tener como máximo 255 caracteres.',
            'especie_id.required' => 'La especie es requerida.',
            'especie_id.uuid' => 'La especie debe ser un UUID válido.',
            'especie_id.exists' => 'La especie no existe.',
            'raza_id.required' => 'La raza es requerida.',
            'raza_id.uuid' => 'La raza debe ser un UUID válido.',
            'raza_id.exists' => 'La raza no existe.',
            'sexo.required' => 'El sexo es requerido.',
            'sexo.in' => 'El sexo debe ser Macho o Hembra.',
            'color.required' => 'El color es requerido.',
            'color.max' => 'El color debe tener como máximo 255 caracteres.',
            'esterilizacion.required' => 'La esterilización es requerida.',
            'esterilizacion.boolean' => 'La esterilización debe ser true o false.',
            'fallecido.sometimes' => 'El fallecimiento debe ser true o false.',
            'fecha_fallecimiento.nullable' => 'La fecha de fallecimiento es opcional.',
            'fecha_fallecimiento.date' => 'La fecha de fallecimiento debe ser una fecha válida.',
            'fecha_fallecimiento.before_or_equal' => 'La fecha de fallecimiento no puede ser futura.',
            'fecha_fallecimiento.required_if' => 'La fecha de fallecimiento es requerida si el animal está fallecido.',
        ];
    }
}
