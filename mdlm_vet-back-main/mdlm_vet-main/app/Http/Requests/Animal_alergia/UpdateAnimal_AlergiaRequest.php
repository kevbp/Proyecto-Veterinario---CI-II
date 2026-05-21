<?php

namespace App\Http\Requests\Animal_alergia;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateAnimal_AlergiaRequest',
    properties: [
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'alergia_id', type: 'string', example: 'AL001'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Observaciones'),
        new OA\Property(property: 'severidad', type: 'string', example: 'leve'),
        new OA\Property(property: 'estado_clinico', type: 'string', example: 'estable'),
    ],
    type: 'object'
)]
class UpdateAnimal_AlergiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'animal_id' => 'required|string|exists:animals,id',
            'alergia_id' => 'required|string|exists:catalogo_alergias,codigo',
            'observaciones' => 'required|string',
            'severidad' => 'required|string',
            'estado_clinico' => 'required|string',
        ];
    }
}
