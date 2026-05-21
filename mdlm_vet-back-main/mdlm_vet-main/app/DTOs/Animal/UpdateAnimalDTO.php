<?php

namespace App\DTOs\Animal;

class UpdateAnimalDTO
{
    public function __construct(
        public readonly ?string $propietario_id,
        public readonly ?string $nombre,
        public readonly ?string $especie,
        public readonly ?string $raza,
        public readonly ?string $sexo,
        public readonly ?string $color,
        public readonly ?bool $esterilizacion,
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            propietario_id: $data['propietario_id'] ?? null,
            nombre: $data['nombre'] ?? null,
            especie: $data['especie'] ?? null,
            raza: $data['raza'] ?? null,
            sexo: $data['sexo'] ?? null,
            color: $data['color'] ?? null,
            esterilizacion: isset($data['esterilizacion']) ? (bool) $data['esterilizacion'] : null,
        );
    }

    public function toArray(): array
    {
        $array = [
            'propietario_id' => $this->propietario_id,
            'nombre' => $this->nombre,
            'especie' => $this->especie,
            'raza' => $this->raza,
            'sexo' => $this->sexo,
            'color' => $this->color,
            'esterilizacion' => $this->esterilizacion,
        ];

        return array_filter($array, function ($value) {
            return $value !== null;
        });
    }
}
