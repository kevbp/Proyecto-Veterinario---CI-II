<?php

namespace App\DTOs\Cita;

class UpdateCitaDTO
{
    public function __construct(
        public readonly string $fecha_hora,
        public readonly string $motivo,
        public readonly string $estado_cita_id,
        public readonly string $animal_id,
        public readonly ?string $personal_id = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            fecha_hora: $data['fecha_hora'],
            motivo: $data['motivo'],
            estado_cita_id: $data['estado_cita_id'],
            animal_id: $data['animal_id'],
            personal_id: $data['personal_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        $arr = [
            'fecha_hora' => $this->fecha_hora,
            'motivo' => $this->motivo,
            'estado_cita_id' => $this->estado_cita_id,
            'animal_id' => $this->animal_id,
        ];

        if ($this->personal_id !== null) {
            $arr['personal_id'] = $this->personal_id;
        }

        return $arr;
    }
}