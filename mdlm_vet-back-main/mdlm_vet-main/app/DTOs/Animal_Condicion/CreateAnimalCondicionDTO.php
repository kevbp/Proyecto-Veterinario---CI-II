<?php

namespace App\DTOs\Animal_Condicion;

class CreateAnimalCondicionDTO
{
    public function __construct(
        public string $animal_id,
        public string $condicion_id,
        public string $observaciones,
        public string $fecha_diagnostico,
        public string $estado_clinico,
        public string $consulta_id,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            animal_id: $data['animal_id'],
            condicion_id: $data['condicion_id'],
            observaciones: $data['observaciones'],
            fecha_diagnostico: $data['fecha_diagnostico'],
            estado_clinico: $data['estado_clinico'],
            consulta_id: $data['consulta_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'animal_id' => $this->animal_id,
            'condicion_id' => $this->condicion_id,
            'observaciones' => $this->observaciones,
            'fecha_diagnostico' => $this->fecha_diagnostico,
            'estado_clinico' => $this->estado_clinico,
            'consulta_id' => $this->consulta_id,
        ];
    }
}
