<?php

namespace App\DTOs\Raza;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreateRazaDTO',
    required: ['nombre', 'codigo', 'peligroso', 'especie_id'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Labrador'),
        new OA\Property(property: 'codigo', type: 'string', example: 'CAN001'),
        new OA\Property(property: 'peligroso', type: 'boolean', example: false),
        new OA\Property(property: 'especie_id', type: 'string', example: 'CAN'),
    ],
    type: 'object'
)]
class CreateRazaDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $codigo,
        public readonly bool $peligroso,
        public readonly ?string $especie_id,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            codigo: $data['codigo'],
            peligroso: $data['peligroso'],
            especie_id: $data['especie_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'peligroso' => $this->peligroso,
            'especie_id' => $this->especie_id,
        ];
    }
}
