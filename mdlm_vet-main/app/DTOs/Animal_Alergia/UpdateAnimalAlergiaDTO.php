<?php

namespace App\DTOs\Animal_Alergia;

class UpdateAnimalAlergiaDTO
{
    public function __construct(
        public ?string $alergia_id = null,
        public ?string $observaciones = null,
        public ?string $severidad = null,
        public ?string $estado_clinico = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            alergia_id: $data['alergia_id'] ?? null,
            observaciones: $data['observaciones'] ?? null,
            severidad: $data['severidad'] ?? null,
            estado_clinico: $data['estado_clinico'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'alergia_id' => $this->alergia_id,
            'observaciones' => $this->observaciones,
            'severidad' => $this->severidad,
            'estado_clinico' => $this->estado_clinico,
        ], fn($value) => !is_null($value));
    }
}
