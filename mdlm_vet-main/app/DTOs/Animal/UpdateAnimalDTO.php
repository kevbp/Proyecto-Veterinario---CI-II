<?php

namespace App\DTOs\Animal;

class UpdateAnimalDTO
{
    public function __construct(
        public readonly ?string $propietario_id,
        public readonly ?string $nombre,
        public readonly ?string $especie_id,
        public readonly ?string $raza_id,
        public readonly ?string $sexo,
        public readonly ?string $color,
        public readonly ?bool $esterilizacion,
        public readonly ?bool $fallecido = null,
        public readonly ?string $fecha_fallecimiento = null,
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            propietario_id: $data['propietario_id'] ?? null,
            nombre: $data['nombre'] ?? null,
            especie_id: $data['especie_id'] ?? null,
            raza_id: $data['raza_id'] ?? null,
            sexo: $data['sexo'] ?? null,
            color: $data['color'] ?? null,
            esterilizacion: isset($data['esterilizacion']) ? (bool) $data['esterilizacion'] : null,
            fallecido: isset($data['fallecido']) ? (bool) $data['fallecido'] : null,
            fecha_fallecimiento: array_key_exists('fecha_fallecimiento', $data) ? $data['fecha_fallecimiento'] : null,
        );
    }

    public function toArray(): array
    {
        $array = [
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

        return array_filter($array, function ($value) {
            return $value !== null;
        });
    }
}
