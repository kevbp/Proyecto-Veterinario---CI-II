<?php

namespace App\DTOs\Animal;

class RegistrarAdopcionDTO
{
    public function __construct(
        public readonly string $animal_id,
        public readonly string $propietario_nuevo_id,
        public readonly ?string $campania_id = null,
        public readonly ?string $observaciones = null,
    ){}

    public static function fromRequest(array $data, string $animal_id, string $propietario_nuevo_id): self
    {
        return new self(
            animal_id: $animal_id,
            propietario_nuevo_id: $propietario_nuevo_id,
            campania_id: $data['campania_id'] ?? null,
            observaciones: $data['observaciones'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'animal_id' => $this->animal_id,
            'propietario_nuevo_id' => $this->propietario_nuevo_id,
            'campania_id' => $this->campania_id,
            'observaciones' => $this->observaciones,
        ];
    }
}
