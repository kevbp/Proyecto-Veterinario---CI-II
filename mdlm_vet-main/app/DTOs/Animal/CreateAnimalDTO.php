<?php

namespace App\DTOs\Animal;

class CreateAnimalDTO
{
    public function __construct(
        public readonly string $propietario_id,
        public readonly string $nombre,
        public readonly string $especie_id,
        public readonly ?string $raza_id,
        public readonly string $sexo,
        public readonly string $color,
        public readonly bool $esterilizacion,
        public readonly bool $fallecido = false,
        public readonly ?string $fecha_fallecimiento = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            propietario_id: $data['propietario_id'],
            nombre: $data['nombre'],
            especie_id: $data['especie_id'],
            raza_id: $data['raza_id'] ?? null,
            sexo: $data['sexo'],
            color: $data['color'],
            esterilizacion: (bool) $data['esterilizacion'],
            fallecido: isset($data['fallecido']) ? (bool) $data['fallecido'] : false,
            fecha_fallecimiento: $data['fecha_fallecimiento'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'propietario_id' => $this->propietario_id,
            'nombre' => $this->nombre,
            'especie_id' => $this->especie_id,
            'raza_id' => $this->raza_id,
            'sexo' => $this->sexo,
            'color' => $this->color,
            'esterilizacion' => $this->esterilizacion,
            'fallecido' => $this->fallecido,
            'fecha_fallecimiento' => $this->fecha_fallecimiento,
        ];
    }
}
