<?php

namespace App\DTOs\Examen;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateExamenDTO',
    properties: [
        new OA\Property(property: 'descripcion', type: 'string', example: 'Examen completo de sangre'),
        new OA\Property(property: 'estado', type: 'string', example: 'Pendiente'),
        new OA\Property(property: 'fecha_resultado', type: 'string', format: 'date-time', example: '2024-06-01T10:00:00Z'),
    ],
    type: 'object'
)]
class UpdateExamenDTO
{
    public function __construct(
        public readonly ?string $descripcion = null,
        public readonly ?string $estado = null,
        public readonly ?string $fecha_resultado = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            descripcion: $data['descripcion'] ?? null,
            estado: $data['estado'] ?? null,
            fecha_resultado: $data['fecha_resultado'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'fecha_resultado' => $this->fecha_resultado,
        ];
    }
}
