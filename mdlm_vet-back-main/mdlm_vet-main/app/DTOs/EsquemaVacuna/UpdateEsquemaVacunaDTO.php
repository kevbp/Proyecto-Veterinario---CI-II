<?php

namespace App\DTOs\EsquemaVacuna;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateEsquemaVacunaDTO',
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
class UpdateEsquemaVacunaDTO
{
    public function __construct(
        public readonly ?string $codigo = null,
        public readonly ?string $nombre = null,
        public readonly ?string $enfermedad = null,
        public readonly ?string $dosis = null,
        public readonly ?int $intervalo_dias = null,
        public readonly ?string $especie_id = null,
        public readonly ?string $descripcion = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            codigo: $data['codigo'] ?? null,
            nombre: $data['nombre'] ?? null,
            enfermedad: $data['enfermedad'] ?? null,
            dosis: $data['dosis'] ?? null,
            intervalo_dias: isset($data['intervalo_dias']) ? (int) $data['intervalo_dias'] : null,
            especie_id: $data['especie_id'] ?? null,
            descripcion: $data['descripcion'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'enfermedad' => $this->enfermedad,
            'dosis' => $this->dosis,
            'intervalo_dias' => $this->intervalo_dias,
            'especie_id' => $this->especie_id,
            'descripcion' => $this->descripcion,
        ], fn($value) => !is_null($value));
    }
}
