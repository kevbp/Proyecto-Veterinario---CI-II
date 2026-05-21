<?php

namespace App\DTOs\Desparasitacion;

class UpdateDesparasitacionDTO
{
    public function __construct(
        public readonly string $animal_id,
        public readonly string $medicamento_id,
        public readonly string $fecha_aplicacion,
        public readonly string $fecha_aplicacion_sgte,
        public readonly string $dosis,
        public readonly string $via,
        public readonly string $observaciones,
        public readonly float $cantidad,
        public readonly ?string $campania_id = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            animal_id: $data['animal_id'],
            medicamento_id: $data['medicamento_id'],
            fecha_aplicacion: $data['fecha_aplicacion'],
            fecha_aplicacion_sgte: $data['fecha_aplicacion_sgte'],
            dosis: $data['dosis'],
            via: $data['via'],
            observaciones: $data['observaciones'],
            cantidad: (float) $data['cantidad'],
            campania_id: $data['campania_id'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'animal_id' => $this->animal_id,
            'medicamento_id' => $this->medicamento_id,
            'fecha_aplicacion' => $this->fecha_aplicacion,
            'fecha_aplicacion_sgte' => $this->fecha_aplicacion_sgte,
            'dosis' => $this->dosis,
            'via' => $this->via,
            'observaciones' => $this->observaciones,
            'cantidad' => $this->cantidad,
            'campania_id' => $this->campania_id,
        ];
    }
}
