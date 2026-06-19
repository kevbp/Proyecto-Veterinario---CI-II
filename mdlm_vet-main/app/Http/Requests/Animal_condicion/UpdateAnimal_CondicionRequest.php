<?php

namespace App\Http\Requests\Animal_condicion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

    #[OA\Schema(
        schema: 'UpdateAnimal_CondicionRequest',
        properties: [
            new OA\Property(property: 'condicion_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
            new OA\Property(property: 'observaciones', type: 'string', example: 'Observaciones'),
            new OA\Property(property: 'severidad', type: 'string', example: 'leve'),
            new OA\Property(property: 'estado_clinico', type: 'string', example: 'estable'),
            new OA\Property(property: 'consulta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000', nullable: true),
        ],
        type: 'object'
    )]
    class UpdateAnimal_CondicionRequest extends FormRequest
    {
        public function authorize(): bool
        {
            return true;
        }
    
        public function rules(): array
        {
            return [
                'condicion_id' => 'sometimes|uuid|exists:catalogo_condiciones,id',
                'observaciones' => 'sometimes|string',
                'severidad' => 'sometimes|string',
                'estado_clinico' => 'sometimes|string',
                'fecha_diagnostico' => 'sometimes|date',
                'consulta_id' => 'nullable|uuid|exists:consultas,id',
            ];
        }

    public function messages(): array
    {
        return [
            'condicion_id.required' => 'El id de la condicion es requerido',
            'condicion_id.exists' => 'El id de la condicion no existe',
            'observaciones.required' => 'Las observaciones son requeridas',
            'severidad.required' => 'La severidad es requerida',
            'estado_clinico.required' => 'El estado clinico es requerido',
            'consulta_id.exists' => 'El id de la consulta no existe',
        ];
    }
}
