<?php

namespace App\DTOs\EsquemaVacuna;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreateEsquemaVacunaDTO',
    required: ['codigo', 'nombre', 'enfermedad', 'dosis', 'intervalo_dias', 'especie_id'],
    properties: [
        new OA\Property(property: 'codigo', type: 'string', example: 'ESQ-001'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Esquema de Vacunación Canino'),
        new OA\Property(property: 'enfermedad', type: 'string', example: 'Rabia'),
        new OA\Property(property: 'dosis', type: 'string', example: '1 ml'),
        new OA\Property(property: 'intervalo_dias', type: 'integer', example: 30),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Esquema de vacunación para perros', nullable: true),
        new OA\Property(property: 'especie_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
    ],
    type: 'object'
)]
class CreateEsquemaVacunaDTO
{
    public function __construct(
        public readonly string $codigo,
        public readonly string $nombre,
        public readonly string $enfermedad,
        public readonly string $dosis,
        public readonly int $intervalo_dias,
        public readonly string $especie_id,
        public readonly ?string $descripcion = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            codigo: $data['codigo'],
            nombre: $data['nombre'],
            enfermedad: $data['enfermedad'],
            dosis: $data['dosis'],
            intervalo_dias: (int) $data['intervalo_dias'],
            especie_id: $data['especie_id'],
            descripcion: $data['descripcion'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'enfermedad' => $this->enfermedad,
            'dosis' => $this->dosis,
            'intervalo_dias' => $this->intervalo_dias,
            'especie_id' => $this->especie_id,
            'descripcion' => $this->descripcion,
        ];
    }
}
