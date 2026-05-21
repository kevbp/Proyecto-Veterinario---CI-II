<?php

namespace App\Http\Requests\Animal;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateAnimalRequest',
    properties: [
        new OA\Property(property: 'propietario_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Firulais'),
        new OA\Property(property: 'especie', type: 'string', example: 'CAN'),
        new OA\Property(property: 'raza', type: 'string', example: 'Labrador'),
        new OA\Property(property: 'sexo', type: 'string', enum: ['Macho', 'Hembra'], example: 'Macho'),
        new OA\Property(property: 'color', type: 'string', example: 'Marrón'),
        new OA\Property(property: 'esterilizacion', type: 'boolean', example: true),
    ],
    type: 'object'
)]
class UpdateAnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'propietario_id' => ['sometimes', 'uuid', 'exists:propietarios,id'],
            'nombre' => ['sometimes', 'string', 'max:255'],
            'especie' => ['sometimes', 'string', 'exists:especies,codigo'],
            'raza' => ['sometimes', 'string', 'max:255'],
            'sexo' => ['sometimes', 'string', 'in:Macho,Hembra'],
            'color' => ['sometimes', 'string', 'max:255'],
            'esterilizacion' => ['sometimes', 'boolean'],
        ];
    }
}
