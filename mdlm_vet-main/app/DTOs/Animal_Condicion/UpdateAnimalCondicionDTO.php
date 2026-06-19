<?php

namespace App\DTOs\Animal_Condicion;

class UpdateAnimalCondicionDTO
{
    public function __construct(
        public ?string $condicion_id = null,
        public ?string $observaciones = null,
        public ?string $fecha_diagnostico = null,
        public ?string $estado_clinico = null,
        public ?string $consulta_id = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            condicion_id: $data['condicion_id'] ?? null,
            observaciones: $data['observaciones'] ?? null,
            fecha_diagnostico: $data['fecha_diagnostico'] ?? null,
            estado_clinico: $data['estado_clinico'] ?? null,
            consulta_id: $data['consulta_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'condicion_id' => $this->condicion_id,
            'observaciones' => $this->observaciones,
            'fecha_diagnostico' => $this->fecha_diagnostico,
            'estado_clinico' => $this->estado_clinico,
            'consulta_id' => $this->consulta_id,
        ], fn($value) => !is_null($value));
    }
}
