<?php

namespace App\Http\Requests\Animal_alergia;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

    #[OA\Schema(
        schema: 'UpdateAnimal_AlergiaRequest',
        properties: [
            new OA\Property(property: 'alergia_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
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
                'alergia_id' => 'sometimes|uuid|exists:catalogo_alergias,id',
                'observaciones' => 'sometimes|string',
                'severidad' => 'sometimes|string',
                'estado_clinico' => 'sometimes|string',
            ];
        }
    }
