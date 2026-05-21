<?php

namespace App\DTOs\Animal;

class CreateAnimalDTO
{
    public function __construct(
        public readonly string $propietario_id,
        public readonly string $nombre,
        public readonly string $especie,
        public readonly ?string $raza,
        public readonly string $sexo,
        public readonly string $color,
        public readonly bool $esterilizacion,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            propietario_id: $data['propietario_id'],
            nombre: $data['nombre'],
            especie: $data['especie'],
            raza: $data['raza'] ?? null,
            sexo: $data['sexo'],
            color: $data['color'],
            esterilizacion: (bool) $data['esterilizacion'],
        );
    }

    public function toArray(): array
    {
        return [
            'propietario_id' => $this->propietario_id,
            'nombre' => $this->nombre,
            'especie' => $this->especie,
            'raza' => $this->raza,
            'sexo' => $this->sexo,
            'color' => $this->color,
            'esterilizacion' => $this->esterilizacion,
        ];
    }
}
