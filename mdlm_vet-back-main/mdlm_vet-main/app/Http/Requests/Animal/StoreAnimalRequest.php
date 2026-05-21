<?php

namespace App\Http\Requests\Animal;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreAnimalRequest',
    required: ['propietario_id', 'nombre', 'especie', 'raza', 'sexo', 'color', 'esterilizacion'],
    properties: [
        new OA\Property(property: 'propietario_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Firulais'),
        new OA\Property(property: 'especie', type: 'string', example: 'CAN'),
        new OA\Property(property: 'raza', type: 'string', example: 'Labrador'),
        new OA\Property(property: 'sexo', type: 'string', enum: ['Macho', 'Hembra'], example: 'Macho'),
        new OA\Property(property: 'color', type: 'string', example: 'Marrón'),
        new OA\Property(property: 'esterilizacion', type: 'boolean', example: false),
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
            'especie' => ['required', 'string', 'exists:especies,codigo'],
            'raza' => ['required', 'string', 'max:255'],
            'sexo' => ['required', 'string', 'in:Macho,Hembra'],
            'color' => ['required', 'string', 'max:255'],
            'esterilizacion' => ['required', 'boolean'],
        ];
    }
}
