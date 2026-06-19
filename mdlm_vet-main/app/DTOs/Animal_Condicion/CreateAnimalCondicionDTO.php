<?php

namespace App\DTOs\Animal_Condicion;

class CreateAnimalCondicionDTO
{
    public function __construct(
        public string $animal_id,
        public string $condicion_id,
        public string $observaciones,
        public string $severidad,
        public string $estado_clinico,
        public ?string $fecha_diagnostico = null,
        public ?string $consulta_id = null,
    ) {}

    public static function fromRequest(array $data, string $animal_id): self
    {
        return new self(
            animal_id: $animal_id,
            condicion_id: $data['condicion_id'],
            observaciones: $data['observaciones'],
            severidad: $data['severidad'],
            estado_clinico: $data['estado_clinico'],
            fecha_diagnostico: $data['fecha_diagnostico'] ?? now()->format('Y-m-d'),
            consulta_id: $data['consulta_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'animal_id' => $this->animal_id,
            'condicion_id' => $this->condicion_id,
            'observaciones' => $this->observaciones,
            'severidad' => $this->severidad,
            'estado_clinico' => $this->estado_clinico,
            'fecha_diagnostico' => $this->fecha_diagnostico,
            'consulta_id' => $this->consulta_id,
        ];
    }
}
