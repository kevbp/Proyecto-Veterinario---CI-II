<?php

namespace App\Http\Requests\Animal_alergia;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

    #[OA\Schema(
        schema: 'StoreAnimal_AlergiaRequest',
        required: ['alergia_id', 'observaciones', 'severidad', 'estado_clinico'],
        properties: [
            new OA\Property(property: 'alergia_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
            new OA\Property(property: 'observaciones', type: 'string', example: 'Observaciones'),
            new OA\Property(property: 'severidad', type: 'string', example: 'leve'),
            new OA\Property(property: 'estado_clinico', type: 'string', example: 'estable'),
        ],
        type: 'object'
    )]
    class StoreAnimal_AlergiaRequest extends FormRequest
    {
        public function authorize(): bool
        {
            return true;
        }
    
        public function rules(): array
        {
            return [
                'alergia_id' => 'required|uuid|exists:catalogo_alergias,id',
                'observaciones' => 'required|string',
                'severidad' => 'required|string',
                'estado_clinico' => 'required|string',
            ];
        }

    public function messages(): array
    {
        return [
            'alergia_id.required' => 'El id de la alergia es requerido',
            'alergia_id.exists' => 'El id de la alergia no existe',
            'observaciones.required' => 'Las observaciones son requeridas',
            'severidad.required' => 'La severidad es requerida',
            'estado_clinico.required' => 'El estado clinico es requerido',
        ];
    }
}
