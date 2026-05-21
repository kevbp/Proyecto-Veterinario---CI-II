<?php

namespace App\DTOs;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreateLineaMedicamentoDTO',
    title: 'CreateLineaMedicamentoDTO',
    description: 'DTO para crear una línea de medicamento en una receta',
    required: ['receta_id', 'medicamento_id', 'cantidad', 'dosis', 'frecuencia', 'duracion'],
    properties: [
        new OA\Property(property: 'receta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'medicamento_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'cantidad', type: 'integer', example: 2),
        new OA\Property(property: 'dosis', type: 'string', example: '1 comprimido'),
        new OA\Property(property: 'frecuencia', type: 'string', example: 'cada 12 horas'),
        new OA\Property(property: 'duracion', type: 'string', example: '5 días'),
    ]
)]
class CreateLineaMedicamentoDTO
{
    public function __construct(
        public readonly string $receta_id,
        public readonly string $medicamento_id,
        public readonly int $cantidad,
        public readonly string $dosis,
        public readonly string $frecuencia,
        public readonly string $duracion
    ) {}
}