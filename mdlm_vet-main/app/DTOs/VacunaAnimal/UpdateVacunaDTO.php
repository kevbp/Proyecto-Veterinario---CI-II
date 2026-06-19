<?php

namespace App\DTOs\VacunaAnimal;

class UpdateVacunaDTO
{
    public function __construct(
        public string $fecha_aplicacion,
        public ?string $fecha_proxima,
        public string $dosis,
        public readonly float $cantidad,
        public string $lote,
        public ?string $fabricante,
        public ?string $observaciones,
        public string $animal_id,
        public string $esquema_vacuna_id,
        public string $medicamento_id,
        public string $personal_id,
        public ?string $consulta_id = null,
        public ?string $campania_id = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            fecha_aplicacion: $data['fecha_aplicacion'],
            fecha_proxima: $data['fecha_proxima'] ?? null,
            dosis: $data['dosis'],
            lote: $data['lote'],
            fabricante: $data['fabricante'] ?? null,
            observaciones: $data['observaciones'] ?? null,
            animal_id: $data['animal_id'],
            esquema_vacuna_id: $data['esquema_vacuna_id'],
            personal_id: $data['personal_id'],
            consulta_id: $data['consulta_id'] ?? null,
            campania_id: $data['campania_id'] ?? null,
            medicamento_id: $data['medicamento_id'],
            cantidad: (float) $data['cantidad']
        );
    }

    public function toArray(): array
    {
        return [
            'fecha_aplicacion' => $this->fecha_aplicacion,
            'fecha_proxima' => $this->fecha_proxima,
            'dosis' => $this->dosis,
            'lote' => $this->lote,
            'fabricante' => $this->fabricante,
            'observaciones' => $this->observaciones,
            'animal_id' => $this->animal_id,
            'esquema_vacuna_id' => $this->esquema_vacuna_id,
            'personal_id' => $this->personal_id,
            'consulta_id' => $this->consulta_id,
            'campania_id' => $this->campania_id,
            'medicamento_id' => $this->medicamento_id,
            'cantidad' => $this->cantidad,
        ];
    }
}