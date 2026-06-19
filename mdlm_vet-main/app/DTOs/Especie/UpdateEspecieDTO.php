<?php

namespace App\DTOs\Especie;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateEspecieDTO',
    properties: [
        new OA\Property(property: 'codigo', type: 'string', example: 'CAN'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Canino'),
    ],
    type: 'object'
)]
class UpdateEspecieDTO
{
    public function __construct(
        public readonly ?string $codigo = null,
        public readonly ?string $nombre = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            codigo: $data['codigo'] ?? null,
            nombre: $data['nombre'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
        ], fn($value) => !is_null($value));
    }
}
