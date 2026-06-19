<?php

namespace App\DTOs\Animal_Alergia;

class CreateAnimalAlergiaDTO
{
    public function __construct(
        public string $animal_id,
        public string $alergia_id,
        public string $observaciones,
        public string $severidad,
        public string $estado_clinico,
    ) {}

    public static function fromRequest(array $data, string $animal_id): self
    {
        return new self(
            animal_id: $animal_id,
            alergia_id: $data['alergia_id'],
            observaciones: $data['observaciones'],
            severidad: $data['severidad'],
            estado_clinico: $data['estado_clinico'],
        );
    }

    public function toArray(): array
    {
        return [
            'animal_id' => $this->animal_id,
            'alergia_id' => $this->alergia_id,
            'observaciones' => $this->observaciones,
            'severidad' => $this->severidad,
            'estado_clinico' => $this->estado_clinico,
        ];
    }
}
