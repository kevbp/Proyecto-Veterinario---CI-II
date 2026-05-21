<?php

namespace App\DTOs\Resultado;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreateResultadoDTO',
    description: 'DTO para crear un nuevo resultado',
    required: ['examen_id', 'hallazgos', 'valores'],
    properties: [
        new OA\Property(property: 'examen_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'hallazgos', type: 'string', example: 'Hallazgos del examen'),
        new OA\Property(property: 'valores', type: 'string', example: 'Valores obtenidos'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Observaciones adicionales'),
        new OA\Property(property: 'interpretacion', type: 'string', example: 'Interpretación de los resultados'),
    ]
)]

class CreateResultadoDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $examen_id,
        public string $hallazgos,
        public string $valores,
        public ?string $observaciones = null,
        public ?string $interpretacion = null,
    ){}

    public static function fromRequest(array $data): self
    {
        return new self(
            examen_id: $data['examen_id'],
            hallazgos: $data['hallazgos'],
            valores: $data['valores'],
            observaciones: $data['observaciones'],
            interpretacion: $data['interpretacion'],
        );
    }

    public function toArray(): array
    {
        return [
            'examen_id' => $this->examen_id,
            'hallazgos' => $this->hallazgos,
            'valores' => $this->valores,
            'observaciones' => $this->observaciones,
            'interpretacion' => $this->interpretacion,
        ];
    }
}
