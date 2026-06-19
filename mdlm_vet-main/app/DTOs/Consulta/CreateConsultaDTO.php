<?php

namespace App\DTOs\Consulta;

class CreateConsultaDTO
{
    public function __construct(
        public readonly string $motivo,
        public readonly ?string $diagnostico,
        public readonly ?string $tratamiento,
        public readonly ?float $peso_registrado,
        public readonly ?string $observaciones,
        public readonly ?string $animal_id,
        public readonly string $personal_id,
        public readonly ?string $cita_id,
    ) {}

    public static function fromRequest(array $data, string $personal_id, ?string $cita_id): self
    {
        return new self(
            motivo: $data['motivo'],
            diagnostico: $data['diagnostico'] ?? null,
            tratamiento: $data['tratamiento'] ?? null,
            peso_registrado: isset($data['peso_registrado']) ? (float) $data['peso_registrado'] : null,
            observaciones: $data['observaciones'] ?? null,
            animal_id: $data['animal_id'] ?? null,
            personal_id: $personal_id,
            cita_id: $cita_id,
        );
    }

    public function toArray(): array
    {
        return [
            'motivo' => $this->motivo,
            'diagnostico' => $this->diagnostico,
            'tratamiento' => $this->tratamiento,
            'peso_registrado' => $this->peso_registrado,
            'observaciones' => $this->observaciones,
            'animal_id' => $this->animal_id,
            'personal_id' => $this->personal_id,
            'cita_id' => $this->cita_id,
        ];
    }
}
