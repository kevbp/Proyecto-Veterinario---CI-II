<?php

namespace App\DTOs;

use App\Models\EsquemaVacuna;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EsquemaVacunaDTO',
    title: 'EsquemaVacunaDTO',
    description: 'DTO para esquemas de vacunación',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'codigo', type: 'string', example: 'ESQ-001'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Esquema de Vacunación Canino'),
        new OA\Property(property: 'enfermedad', type: 'string', example: 'Rabia'),
        new OA\Property(property: 'dosis', type: 'string', example: '1 ml'),
        new OA\Property(property: 'intervalo_dias', type: 'integer', example: 30),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Esquema de vacunación para perros', nullable: true),
        new OA\Property(property: 'especie_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class EsquemaVacunaDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $codigo,
        public readonly string $nombre,
        public readonly string $enfermedad,
        public readonly string $dosis,
        public readonly int $intervalo_dias,
        public readonly ?string $descripcion,
        public readonly string $especie_id,
        public readonly string $created_at,
        public readonly string $updated_at,
    ) {
    }

    public static function fromModel(EsquemaVacuna $esquemaVacuna): self
    {
        return new self(
            id: $esquemaVacuna->id,
            codigo: $esquemaVacuna->codigo,
            nombre: $esquemaVacuna->nombre,
            enfermedad: $esquemaVacuna->enfermedad,
            dosis: $esquemaVacuna->dosis,
            intervalo_dias: $esquemaVacuna->intervalo_dias,
            descripcion: $esquemaVacuna->descripcion,
            especie_id: $esquemaVacuna->especie_id,
            created_at: $esquemaVacuna->created_at,
            updated_at: $esquemaVacuna->updated_at,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'enfermedad' => $this->enfermedad,
            'dosis' => $this->dosis,
            'intervalo_dias' => $this->intervalo_dias,
            'descripcion' => $this->descripcion,
            'especie_id' => $this->especie_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
