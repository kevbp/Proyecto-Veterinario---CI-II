<?php

namespace App\DTOs\Especie;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreateEspecieDTO',
    required: ['codigo', 'nombre'],
    properties: [
        new OA\Property(property: 'codigo', type: 'string', example: 'CAN'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Canino'),
    ],
    type: 'object'
)]
class CreateEspecieDTO
{
    public function __construct(
        public readonly string $codigo,
        public readonly string $nombre,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            codigo: $data['codigo'],
            nombre: $data['nombre'],
        );
    }

    public function toArray(): array
    {
        return [
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
        ];
    }
}
