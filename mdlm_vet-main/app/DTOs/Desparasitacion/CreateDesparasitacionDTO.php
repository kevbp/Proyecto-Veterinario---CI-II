<?php

namespace App\DTOs\Desparasitacion;

class CreateDesparasitacionDTO
{
    public function __construct(
        public ?string $animal_id,
        public string $medicamento_id,
        public string $fecha_aplicacion,
        public string $fecha_aplicacion_sgte,
        public string $dosis,
        public string $via,
        public string $observaciones,
        public readonly float $cantidad,
        public ?string $campania_id = null,
        public string $personal_id,
        public ?string $consulta_id = null
    ) {}

    public static function fromRequest(array $data, string $personal_id, ?string $consulta_id, ?string $campania_id): self
    {
        return new self(
            fecha_aplicacion: $data['fecha_aplicacion'],
            fecha_aplicacion_sgte: $data['fecha_aplicacion_sgte'],
            observaciones: $data['observaciones'] ?? '',
            dosis: $data['dosis'],

            via: $data['via'],
            
            cantidad: (float) $data['cantidad'],
            personal_id: $personal_id,
            medicamento_id: $data['medicamento_id'],
            animal_id: $data['animal_id'] ?? null,
            consulta_id: $consulta_id ?? null,
            campania_id: $campania_id ?? null,
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
            'personal_id' => $this->personal_id,
            'consulta_id' => $this->consulta_id,
            'campania_id' => $this->campania_id
        ];
    }
}