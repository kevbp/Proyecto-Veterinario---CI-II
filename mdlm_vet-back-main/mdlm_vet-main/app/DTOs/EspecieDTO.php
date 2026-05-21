<?php

namespace App\DTOs;

use App\Models\Especie;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EspecieDTO',
    title: 'EspecieDTO',
    description: 'DTO para especies',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'codigo', type: 'string', example: 'CAN'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Canino'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class EspecieDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $codigo,
        public readonly string $nombre,
        public readonly string $created_at,
        public readonly string $updated_at,
    ) {
    }

    public static function fromModel(Especie $especie): self
    {
        return new self(
            id: $especie->id,
            codigo: $especie->codigo,
            nombre: $especie->nombre,
            created_at: $especie->created_at,
            updated_at: $especie->updated_at,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
