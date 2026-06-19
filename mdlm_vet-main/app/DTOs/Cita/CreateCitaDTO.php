<?php

namespace App\DTOs\Cita;

class CreateCitaDTO
{
    public function __construct(
        public readonly string $fecha_hora,
        public readonly string $motivo,
        public readonly string $estado_cita_id,
        public readonly string $animal_id,
        public readonly string $personal_id,
    ) {}

    public static function fromRequest(array $data, string $personal_id): self
    {
        return new self(
            fecha_hora: $data['fecha_hora'],
            motivo: $data['motivo'],
            estado_cita_id: $data['estado_cita_id'],
            animal_id: $data['animal_id'],
            personal_id: $personal_id,
        );
    }

    public function toArray(): array
    {
        return [
            'fecha_hora' => $this->fecha_hora,
            'motivo' => $this->motivo,
            'estado_cita_id' => $this->estado_cita_id,
            'animal_id' => $this->animal_id,
            'personal_id' => $this->personal_id,
        ];
    }
}