<?php

namespace App\DTOs\Adopcion;

class EstadisticaFechasDTO
{
    public function __construct(
        public readonly string $fecha_inicio,
        public readonly string $fecha_fin
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            fecha_inicio: $data['fecha_inicio'],
            fecha_fin: $data['fecha_fin']
        );
    }

    public function toArray(): array
    {
        return [
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ];
    }
}
