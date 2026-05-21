<?php

namespace App\DTOs;

use App\Models\Instrumento;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'InstrumentoDTO',
    title: 'InstrumentoDTO',
    description: 'DTO para instrumentos',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'codigo', type: 'string', example: 'INS-001'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Estetoscopio'),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Estetoscopio para perros y gatos', nullable: true),
        new OA\Property(property: 'stock', type: 'integer', example: 10),
        new OA\Property(property: 'estado', type: 'string', example: 'activo', nullable: true),
        new OA\Property(property: 'foto', type: 'string', example: 'instrumentos/estetoscopio.jpg', nullable: true),
        new OA\Property(property: 'user_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class InstrumentoDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $codigo,
        public readonly string $nombre,
        public readonly ?string $descripcion,
        public readonly int $stock,
        public readonly ?string $estado,
        public readonly ?string $foto,
        public readonly ?string $user_id,
        public readonly string $created_at,
        public readonly string $updated_at,
    ) {
    }

    public static function fromModel(Instrumento $instrumento): self
    {
        return new self(
            id: $instrumento->id,
            codigo: $instrumento->codigo,
            nombre: $instrumento->nombre,
            descripcion: $instrumento->descripcion,
            stock: $instrumento->stock,
            estado: $instrumento->estado,
            foto: $instrumento->foto,
            user_id: $instrumento->user_id,
            created_at: $instrumento->created_at,
            updated_at: $instrumento->updated_at,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'stock' => $this->stock,
            'estado' => $this->estado,
            'foto' => $this->foto,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}