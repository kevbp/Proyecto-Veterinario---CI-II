<?php

namespace App\DTOs\Receta;

use OpenApi\Attributes as OA;

class UpdateRecetaDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?string $estado_consulta = null,
        public ?string $indicaciones_generales = null,
        public ?string $fecha_emision = null,
        public ?string $fecha_vencimiento = null,
    ){}

    public static function fromRequest(array $data): self
    {
        return new self(
            estado_consulta: $data['estado_consulta'] ?? null,
            indicaciones_generales: $data['indicaciones_generales'] ?? null,
            fecha_emision: $data['fecha_emision'] ?? null,
            fecha_vencimiento: $data['fecha_vencimiento'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'estado_consulta' => $this->estado_consulta,
            'indicaciones_generales' => $this->indicaciones_generales,
            'fecha_emision' => $this->fecha_emision,
            'fecha_vencimiento' => $this->fecha_vencimiento
        ];
    }
}
