<?php

namespace App\DTOs\Receta;

use OpenApi\Attributes as OA;

class CreateRecetaDTO
{
    public function __construct(
        public readonly string $consulta_id,
        public readonly string $estado_receta,
        public readonly string $indicaciones_generales,
        public readonly string $fecha_emision,
        public readonly string $fecha_vencimiento,
        public readonly array $lineas_medicamentos = []
    ){}

    public static function fromRequest(array $data): self
    {
        return new self(
            consulta_id: $data['consulta_id'],
            estado_receta: $data['estado_receta'],
            indicaciones_generales: $data['indicaciones_generales'],
            fecha_emision: $data['fecha_emision'],
            fecha_vencimiento: $data['fecha_vencimiento'],
            lineas_medicamentos: $data['lineas_medicamento'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'consulta_id' => $this->consulta_id,
            'estado_receta' => $this->estado_receta,
            'indicaciones_generales' => $this->indicaciones_generales,
            'fecha_emision' => $this->fecha_emision,
            'fecha_vencimiento' => $this->fecha_vencimiento,
        ];
    }
}