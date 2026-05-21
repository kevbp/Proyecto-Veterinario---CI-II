<?php

namespace App\DTOs\Examen;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreateExamenDTO',
    required: ['nombre', 'tipo_examen_id', 'descripcion', 'estado', 'fecha_hora', 'consulta_id'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Hemograma'),
        new OA\Property(property: 'tipo_examen_id', type: 'string', example: 'ECO-001'),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Examen completo de sangre'),
        new OA\Property(property: 'estado', type: 'string', example: 'Pendiente'),
        new OA\Property(property: 'fecha_hora', type: 'string', format: 'date-time', example: '2024-06-01T10:00:00Z'),
        new OA\Property(property: 'fecha_resultado', type: 'string', format: 'date-time', example: '2024-06-01T10:00:00Z'),
        new OA\Property(property: 'consulta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
    ],
    type: 'object'
)]
class CreateExamenDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $tipo_examen_id,
        public readonly string $descripcion,
        public readonly string $estado,
        public readonly string $fecha_hora,
        public readonly string $fecha_resultado,
        public readonly string $consulta_id,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            tipo_examen_id: $data['tipo_examen_id'],
            descripcion: $data['descripcion'],
            estado: $data['estado'],
            fecha_hora: $data['fecha_hora'],
            fecha_resultado: $data['fecha_resultado'],
            consulta_id: $data['consulta_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'tipo_examen_id' => $this->tipo_examen_id,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'fecha_hora' => $this->fecha_hora,
            'fecha_resultado' => $this->fecha_resultado,
            'consulta_id' => $this->consulta_id,
        ];
    }
}
